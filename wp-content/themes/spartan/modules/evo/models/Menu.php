<?php

use Evo\Form as Form;
use Evo\Typebuilder as TypeBuilder;
use Evo\PostOutput;

class Menu extends TypeBuilder
{
    # this is important, as TypeBuilder uses it internally to build queries and
    # create the post type
    protected $name = 'menuItem';

    public function __invoke()
    {
        $all = $this->getAll();

        usort($all, function($a, $b){
            return $a->sortOrder > $b->sortOrder;
        });

        return "<ul>" . implode("", array_map(function($el){
            return "<li class='{$el->post_title}'> <a href='{$el->targetUrl}'> {$el->post_content} </a> </li>";
        }, $all)) . "</ul>";
    }

    public function init(){
        # these option settings are the wordpress default settings - you can
        # freely change or add anything that a normal CPT would have as an option
        $options = array(
            'description' => __('Menu Item'),
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => false,
            'show_ui' => true,
            'query_var' => true,
            # http://www.kevinleary.net/wordpress-dashicons-list-custom-post-type-icons/
            'menu_icon' => 'dashicons-image-flip-horizontal',
            'menu_position' => 47,
            'capability_type' => 'post',
            'hierarchical' => true,
            'supports' => array('title', 'editor' ),
            'taxonomies' => array('category', 'post_tag')
        );
        # these are the "labels" option when declaring a custom post type,
        # broken out here so that it's not a mess
        $labels = array(
            'name' => __('Menu Item', 'bonestheme'),
            'singular_name' => __('Menu Item', 'bonestheme'),
            'all_items' => __('Manage Menu Item ', 'bonestheme'),
            'add_new' => __('Add New', 'bonestheme'),
            'add_new_item' => __('Add New MenuItem', 'bonestheme'),
            'edit' => __('Edit', 'bonestheme'),
            'edit_item' => __('Edit Menu Item', 'bonestheme'),
            'new_item' => __('New Menu Item', 'bonestheme'),
            'view_item' => __('View Menu Item', 'bonestheme'),
            'search_items' => __('Search Menu Item', 'bonestheme'),
            'not_found' => __("No MenuItem found.", 'bonestheme'),
            'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'),
            'parent_item_colon' => '',
        );
        # the post type will initialize automatically when both of these
        # are set
        $this->labels($labels)->options($options);

        # Add categories to this post type
       Actions::on('init', function(){
         register_taxonomy_for_object_type('category', 'menuItem');
       });

        # initialize the static custom fields for this post type
        $this->initializeFields();

        $this->registerFilters();

        return $this;
    }

    public function initializeFields()
    {
        # the createFieldGroup method will automatically create the appropriate
        # post_meta entries for the post type with the names in the "fields" parameter.
        # the labels need to be in the same order
        $this->createFieldGroup(array(
            # name of the bucket in the admin. This is not taxonomical - all of the
            # fields could be in one bucket, or all in separate ones
            "name" => "Details",
            # fields that are included within this bucket
            "fields" => array( "targetUrl", "sortOrder"),
            "labels" => array( "Url", "Sort Order"),
            # where the bucket appears. In general, radio/checkboxes should be set to "side"
            # while text/textarea fields and anything more advanced should be set to "normal"
            "position" => "normal",
            "priority" => "core",
            # this is the actual render function for the fields
            "render" => function( $name, $fieldList ){
                # create an array to hold the HTML
                $html = array();
                # this is just standard markup to emulate the base wordpress styles
                $html[] = "<div class='inner-group-content'>";
                    # loop over the fieldList (if they have data already, it will have been pulled)
                    # fieldList is in the following format, referencing the above, where "exampleLink" has data:
                    /*
                        [
                            0 => [
                                    "fieldName" => "exampleLink",
                                    "label" => "Example Link",
                                    "data" => "https://google.com"
                                ],
                            1 => [
                                    "fieldName" => "author",
                                    "label" => "Author",
                                    "data" => false
                                ],
                            2 ...
                        ]
                    */
                    # You can pass the $field in this loop directly to the Form::methods()
                    # as it expects the above data-structure. Below is an example of how you can
                    # handle multiple data types and use the form class to automatically pull data
                    foreach( $fieldList as $field ){
                            $html[] = "<div>";
                                $html[] = "<label class='custom-field-label'>";
                                $html[] =   "<span class='label-wrapper'>{$field['label']}</span>";
                                switch ( $field['fieldName'] ) :
                                    default :
                                        $html[] = Form::input( $field, 'text' );
                                    break;
                                endswitch;
                                $html[] = "</label>";
                            $html[] = "</div>";
                    }

                $html[] = "</div>";
                # finally, echo out our completed HTML
                echo implode('', $html);
            }
        ));

        return $this;
    }

    public function registerFilters()
    {

    }

    protected function replaceTerm( $str, $term, $value )
    {
        return str_replace("!!{$term}", $value, $str);
    }

    public function getAll()
    {
        # set a cache key, and use the Utils library to set
        # Wordpress transients
        $cacheKey = 'allExamples';
        # this will return false if the cache is not found
        $all = Utils::cacheGet($cacheKey);
        # now, if it's not in the cache, we should go ahead and run the actual query
        if (!$all) {
            # TypeBuilder has a built-in ->get() method that automatically populates the post
            # type of the called post, saving you from specifying it within its own model. The only
            # other default defined in the query is the posts_per_page parameter which, by default,
            # is set to five.
            # You can pass it an argument with extended arguments that override the default settings.
            # In this example, we want ALL posts (which is why we're caching the result) so we override
            # the posts_per_page setting. Any of the default Wordpress query parameters can be used here.
            $extendedArgs = array("posts_per_page" => -1, "orderby" => 'post_title', 'order' => 'ASC');
            $all = $this->get($extendedArgs);
            # since this hasn't been set, set it here with the result of our query
            Utils::cacheSet($cacheKey, $all);
        }
        # return our result either from the query or
        # from the cache
        return $all;
    }

    public function getByCategory( $category )
    {
        # another example of using extended arguments
        # in this example, we're fetching all example posts
        # that have a specific category
        $extendedArgs = [
            "posts_per_page" => 10,
            "category" => $category
        ];

        return $this->get( $extendedArgs );
    }

    /**
     * Get a single post based on its id. Defaults to current global $post.
     * This can be used on single pages by just calling App::module('example')->getSingle()
     * and it will automatically pull for the queried post
     * @param  boolean $id [postID]
     * @return Array      [\WPQuery changed into an array]
     */
    public function getSingle($id = false)
    {
        if (!$id) {
            global $post;
            $id = $post->ID;
        }

        $args = array(
            'p' => $id,
            'post_status' => '*'
        );

        $get = $this->get($args);

        # this will only ever have one result, so we may as well just return that
        if( ! $get )
            return false;

        return $get[0];
    }
}
