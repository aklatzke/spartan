<?php

namespace Evo;

use \WP_Query;

class TypeBuilder
{
    # Initialization
    protected $fieldList = array();
    protected $metaQueue = array();
    protected $metaValues = array();
    protected $name = '';
    protected $postType = array();

    public $filters = array();
    public $saveFilters = array();

    /**
     * Registers the new post type with its labels and options
     * Adds appropriate actions to help with save/get of metadata
     * @param Array $options - add_post_type() arguments
     * @param Array $labels - add_post_type() label arguments
     * @return \TypeBuilder [ self ]
     */
    public function __construct($options = array(), $labels = array())
    {
        # merge labels with the options block
        $this->options = $options;
        $this->labels = $labels;

        add_action('save_post', array($this, 'saveMeta'));
        add_action('admin_head', array($this, 'getExistingData'), 0);
        add_action('admin_head', array($this, 'processQueue'));
        add_action('wp_insert_post', array($this, 'addMetaListToNewPost'), 0);

        $this->init();

        return $this->checkForInit();
    }

    public function labels($labels)
    {
        $this->labels = $labels;

        return $this->checkForInit();
    }

    public function options($options)
    {
        $this->options = $options;

        return $this->checkForInit();
    }

    private function checkForInit()
    {
        if (count($this->options) && count($this->labels)) {
            $this->options['labels'] = $this->labels;
            $this->postType = register_post_type($this->name, $this->options);
            $this->name = $this->postType->name;
        }

        return $this;
    }

    /**
     * Placeholder method for inheritance
     * @return null
     */
    protected function init()
    {}

    /**
     * Adds the appropriate meta fields to a newly created admin post if it doesn't have
     * fields already assigned
     * @param int $pID - postID
     */
    public function addMetaListToNewPost($pID)
    {
        $type = get_post_type($pID);

        # see if it has a meta list - if it doesn't, initialize
        if ($type === $this->name) {
            if (!get_post_meta($pID, 'metaList')) {
                update_post_meta($pID, 'metaList', json_encode($this->fieldList));

                # initialize these to empty values so that it plays nice
                # since admin-created items already have empty fields
                foreach ($this->fieldList as $field) {
                    update_post_meta($pID, $field, '');
                }
            }
        }

        return $pID;
    }

    /**
     * Adds a filter to be run when item is saved
     * @param string $filterKey the key corresponding to a meta item
     * @param Closure $callback  closure to be run against the value
     */
    public function registerSaveFilter($filterKey, $callback)
    {
        return $this->saveFilters[$this->metaToFieldName($filterKey)] = $callback;
    }

    /**
     * Updates all meta fields for a given post based on the metaList property
     * @param  integer $id - postID
     * @param  Array $newMeta - meta keys to be updated with corresponding value
     * @return Array [ postMeta ]
     */
    public function updateMetaFields($id, $newMeta)
    {
        $oldMeta = $this->getPostMeta($id);

        foreach ($oldMeta as $key => $value) {
            if ($value !== $newMeta[$key]) {update_post_meta($id, $key, $newMeta[$key]);
            }
        }

        return $newMeta;
    }

    /**
     * Creates a group of meta fields out of an array
     * @param  Array  $options
     * @return null
     */
    public function createFieldGroup(array $options)
    {
        if (!isset($options['labels'])) {$options['labels'] = array();
        }

        $name = implode('', explode(' ', $options["name"]));
        $formattedFieldInfo = $this->formatMultiArgs($options["fields"], $options["labels"]);

        $this->queueMeta(array(
            $this->name . ucfirst($name),
            ucfirst($options["name"]),
            array($this, 'registerField'),
            $this->name,
            isset($options['position']) ? $options['position'] : 'normal',
            isset($options['priority']) ? $options['priority'] : 'normal',
            array(
                "name" => $name,
                "callback" => $options["render"],
                "type" => 'multi',
                "multi" => $formattedFieldInfo,
            )
        ));
    }

    /**
     * Creates a single meta box
     * @param  string  $name
     * @param  Closure  $callback
     * @param  string  $position
     * @param  string  $priority
     * @param  string  $type
     * @param  boolean $additional
     * @return \Typebuilder [ self ]
     */
    public function createMetaBox($name, $callback = '', $position = 'normal', $priority = 'low', $type = 'single', $additional = false)
    {
        $formattedName = implode('', explode(' ', $name));

        $this->queueMeta(
            array(
                $this->name . ucfirst($formattedName),
                ucfirst($name),
                array($this, 'registerField'),
                $this->name,
                $position,
                $priority,
                array(
                    "name" => $name,
                    "callback" => $callback,
                    "type" => $type,
                    "additional" => $additional,
                )
            )
        );

        return $this;
    }

    /**
     * Fetches posts of child type from the database
     * @param  Array  $extendedArgs - WP_Query arguments
     * @return Array
     */
    public function get($extendedArgs = array())
    {
        $args = array(
            'post_type' => array($this->name),
            'posts_per_page' => 5
        );
        # check if this is a multidimensional array
        # this check works because:
        # count($extendedArgs) will return the count for the number of top-level items
        # and count($extendedArgs, 1 ) will return the count for ALL arrays within that array
        # if they're different, we know it's multi-dimensional
        if( count( $extendedArgs ) === count( $extendedArgs, COUNT_RECURSIVE) )
        {
            $args = array_merge($args, $extendedArgs);
        }
        else
        {
            # array_merge_recursive will trash top-level keys that are
            # not multidimensional
            foreach ($extendedArgs as $key => $value) {
                    $args[$key] = $value;
            }
        }

        $query = new WP_Query($args);

        $posts = $query->posts;
        $postList = array();

        foreach ($posts as $post) {
            $postList[] = $this->prepareCustom($post);
        }

        return $postList;
    }
    /**
     * Pulls existing data for a post to reinitialize
     * @return null
     */
    public function getExistingData()
    {
        global $post;

        if (isset($post->ID)) {
            $id = $post->ID;

            $custom = get_post_custom($id);

            if ($custom && isset($custom["metaList"])) {
                $this->fieldList = $this::getPostMetaList($id);
                $this->metaValues = $this::getPostMeta($id);
            }
        }
    }

    /**
     * Converts the meta key to a field name for submission
     * @param  string $metaName
     * @return string
     */
    public function metaToFieldName($metaName)
    {
        return $this->name . ucfirst($metaName) . "Field";
    }

    /**
     * Processes the meta queue and executes all callbacks with their appropriate targets
     * @return boolean [ true ]
     */
    public function processQueue()
    {
        foreach ($this->metaQueue as $meta) {
            call_user_func_array('add_meta_box', $meta);
        }

        return true;
    }

    /**
     * This function does the heavy lifting of processing the meta
     * field initialization upon entering the admin
     * @outputs string [ HTML ]
     * @exits - if ajax
     * @param  \Post $post
     * @param  Array $additional - additional arguments supplied through the match functionality
     * @return mixed
     */
    public function registerField($post, $additional)
    {
        $name = $additional["args"]["name"];
        $custom = get_post_custom($post->ID);

        if ($additional["args"]["callback"] !== '') {$callback = $additional["args"]["callback"];
        }

        if ($additional["args"]["type"] === 'single') {
            $this->addToFieldList($name);

            $data = isset($this->metaValues[$name]) ? $this->metaValues[$name] : '';

            $expectedFieldName = $metaToFieldName($name);
            echo isset($callback) ? $callback($name, $data, $expectedFieldName) : "<input name='{$expectedFieldName}' value='{$data}' />";
        } else {
            $fieldInfo = $additional["args"]["multi"];

            foreach ($fieldInfo as $field => $info) {
                $info["data"] = isset($this->metaValues[$field]) ? $this->metaValues[$field] : '';
                $fieldInfo[$field] = $info;
            }

            $additional["args"]["callback"]($name, $fieldInfo);

            return true;
        }
    }

    /**
     * Adds a filter to the post type based on one of its meta keys
     * @param  string $filterKey
     * @param  Closure $callback
     * @return null
     */
    public function registerFilter($filterKey, $callback)
    {
        $this->filters[$filterKey] = $callback;
    }

    /**
     * Automatically saves meta information upon post save
     * @return null;
     */
    public function saveMeta()
    {
        global $post;

        $rawPost = $_POST;
        if ($post && $post->post_type === $this->name) {

            foreach ($this->fieldList as $field) {
                if (strpos($field, '[]') !== false) {
                    $this->fieldList[] = str_replace('[]', '', $field);
                }
            }

            update_post_meta($post->ID, "metaList", json_encode($this->fieldList));

            foreach ($this->fieldList as $metaField) {
                $fieldName = $this->metaToFieldName($metaField);

                if (isset($_POST[$fieldName])) {
                    $val = $_POST[$fieldName];
                    if (isset($this->saveFilters[$fieldName])) {
                        $val = $this->saveFilters[$fieldName]($val);
                    }

                    update_post_meta($post->ID, $metaField, $val);
                }
                # catch manually named fields
                elseif (isset($_POST[$metaField])) {
                    $val = $_POST[$metaField];
                    if (isset($this->saveFilters[$metaField])) {
                        $val = $this->saveFilters[$metaField]($val);
                    }

                    update_post_meta($post->ID, $metaField, $val);
                } else {
                    # to catch checkboxes
                    update_post_meta($post->ID, $metaField, 'false');
                }
            }
        }
    }

    /**
     * Gets all meta for a specific post id
     * @param  integer  $id
     * @param  Array $filters
     * @return Array
     */
    public static function getPostMeta($id, $filters = false)
    {
        $custom = get_post_custom($id);
        $list = array();

        $resolvedCustom = array();

        if ($custom && isset($custom["metaList"])) {
            $list = self::getPostMetaList($id);

            foreach ($list as $metaItem) {
                if (isset($custom[$metaItem])) {
                    # unserialize any arrays
                    $resolvedCustom[$metaItem] = maybe_unserialize($custom[$metaItem][0]);

                    if (isset($filters[$metaItem])) {
                        $resolvedCustom[$metaItem] = $filters[$metaItem]($resolvedCustom[$metaItem]);
                    }
                }
            }
        }

        return $resolvedCustom;
    }

    /**
     * Internal method to add a field to the interal field list
     * @param string $field
     */
    private function addToFieldList($field)
    {
        if (!in_array($field, $this->fieldList)) {array_push($this->fieldList, $field);
        }
    }

    /**
     * Formats arguments to a less human readable but more easily
     * traversed state. Matches label and name for each field.
     * @param  Array $fields
     * @param  Array $labels
     * @return Array
     */
    private function formatMultiArgs($fields, $labels)
    {
        $argList = array();

        if (empty($fields)) {return $argList;
        }

        $i = 0;

        foreach ($fields as $field) {
            $label = isset($labels[$i]) ? $labels[$i] : ucfirst(preg_replace('/(?<=[a-z])[A-Z]/', ' $0', $field));

            $argList[$field] = array(
                "name" => $field,
                "fieldName" => $this->name . ucfirst($field) . "Field",
                "label" => $label,
            );

            $this->addToFieldList($field);

            $i++;
        }

        return $argList;
    }

    /**
     * Returns the post meta list which contains all meta fields as an array
     * @param  integer $id
     * @return Array
     */
    protected static function getPostMetaList($id)
    {
        $custom = get_post_custom($id);

        if ($custom && isset($custom["metaList"])) {
            return json_decode($custom["metaList"][0]);
        } else {
            return array();
        }
    }

    protected function prepareCustom($post)
    {
        $postID = $post->ID;
        $url = get_permalink($postID);
        $featured = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'large');

        $customMeta = $this::getPostMeta($post->ID, $this->filters);

        $customMeta["featuredImage"] = $featured;
        $customMeta["url"] = $url;

        // foreach( $customMeta as $name => $data ){
        //     $customMeta[$name] = $this->checkFilters( $name, $data );
        // }

        return (object) array_merge((array) $post, $customMeta);
    }

    private function queueMeta($arr)
    {
        array_push($this->metaQueue, $arr);

        return true;
    }
}
