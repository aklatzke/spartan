<?php

class AjaxRouter
{
    /**
     * The `name` of the route, used to confirm identity
     * @var string
     */
    private $routeKey = '';

    /**
     * Callback action for this route
     * @var closure
     */
    private $action = false;

    /**
     * Constructor
     * @param string $routeKey
     * @param closure $action
     */
    public function __construct($routeKey, $action)
    {
        $this->routeKey = $routeKey;
        $this->action = $action;
        # run with top priority
        Actions::on("wp_ajax_nopriv_{$routeKey}", array($this, 'processRequest'), 0);
        Actions::on("wp_ajax_{$routeKey}", array($this, 'processRequest'), 0);
    }

    /**
     * Processes the specified route
     * @exits
     * @return null
     */
    public function processRequest()
    {
        Actions::trigger("{$this->routeKey}AjaxStart");

        $response = call_user_func($this->action);

        Actions::trigger("{$this->routeKey}AjaxEnd");

        echo json_encode($response);

        die();
    }

    /**
     * Returns appropriate URL for this route object
     * @return string [ URI ]
     */
    public function getRouteUrl()
    {
        return '/wp-admin/admin-ajax.php?action=' . $this->routeKey;
    }
}
