<?php

use \Actions;
use \AjaxRouter;
use \Input;

class Route
{
    /**
     * The name of the route - used to confirm identity
     * @var string
     */
    public $target = '';

    /**
     * The route callback
     * @var Closure
     */
    public $callback = false;

    /**
     * Flag designating whether this is an AJAX route
     * @var boolean
     */
    public $ajax = false;

    /**
     * Constructor
     */
    public function __construct(  )
    {
    }

    /**
     * Returns a new AjaxRouter through the Route interface
     * @param  string $route
     * @param  Closure $callback
     * @return \AjaxRouter
     */
    public static function ajax($route, $callback)
    {
        return new AjaxRouter($route, $callback);
    }

    /**
     * Matches a given route with {routeRegex} and runs the provided {callback}
     * @param  string [ RegularExpression ]  $routeRegex
     * @param  string  $name - Unique key to identify this route
     * @param  Array   $queryVars - provided key for each match specified in {routeRegex}
     * @param  Closure  $callback
     * @param  boolean $ajax
     * @param  boolean $admin
     * @return null
     */
    public function match($routeRegex, $name, $queryVars, $callback, $ajax = false, $admin = false)
    {
        Actions::on('parse_request', array($this, 'checkRoute'), 1, 4);

        # build query string
        $c = 0;
        $queryString = $admin ? '/wp-admin/admin.php?page=' . $name . '&' : 'index.php?';

        foreach ($queryVars as $var) {
            $queryString .= $var . '=$matches[' . ++$c . ']&';
        }

        add_rewrite_rule($routeRegex, $queryString . 'routeName=' . $name, 'top');
        add_rewrite_tag('%routeName%', '([^&]*)');

        foreach ($queryVars as $var) {
            add_rewrite_tag('%' . $var . '%', '([^&]*)');
        }

        $this->target = $name;
        $this->callback = $callback;
        $this->ajax = $ajax;

        return array("target" => $this->target, "callback" => $this->callback, "ajax" => $this->ajax);
    }

    /**
     * Matches an admin route by regex and adds a menu item
     * @param  string [ Regular Expression ] $routeRegex
     * @param  string $name
     * @param  string $queryVars
     * @param  Closure $callback
     * @param  Array  $options - array of options for add_menu_page
     * @return null
     */
    public function adminRoute($routeRegex, $name, $queryVars, $callback, $options = array())
    {

        $defaults = array(
            'capability' => 'administrator',
            'page_title' => $name,
            'menu_title' => $name,
            'menu_slug' => $name . '_route',
            'icon_url' => '',
            'position' => 1,
        );

        $options = (object) array_merge($defaults, $options);

        $this->match($routeRegex, $options->menu_slug, $queryVars, function ($input) use ($options) {
            $input['routeAlias'] = true;

            $var = Utils::cacheSet('matchData', json_encode($input));
            Utils::redirect('/wp-admin/admin.php?page=' . $options->menu_slug);
        }, false, true);

        Actions::on('admin_menu', function () use ($callback, $options) {
            $input = json_decode(Utils::cacheGet('matchData'));

            add_menu_page(
                $options->page_title,
                $options->menu_title,
                $options->capability,
                $options->menu_slug,
                function () use ($input, $callback) {
                    return call_user_func($callback, $input);
                },
                $options->icon_url,
                $options->position
            );
        });

        return null;
    }

    /**
     * Checks that a given route is matched and valid. Delegates arguments
     * If this is an ajax route, this outputs and exits, otherwise returns value
     * @exits
     * @outputs Closure
     * @param  \WPQuery $input
     * @return Closure
     */
    public function checkRoute($input)
    {

        $name = Input::param('routeName');

        if( ! $name )
        {
            return $input;
        }

        $vars = Input::all();

        if ($this->target === $name) {

            if ($this->callback) {
                $callback = $this->callback;

                if( is_string($callback) ){
                    $temp = explode('::', $callback);

                    $callback = array();

                    $callback[0] = App::module($temp[0]);
                    $callback[1] = $temp[1];
                }

                if ($this->ajax) {
                    echo json_encode(call_user_func($callback, $vars));
                    die();
                }

                return call_user_func($callback, $vars);
                die();
            }

            return '';
        }

        return $input;
    }
}
