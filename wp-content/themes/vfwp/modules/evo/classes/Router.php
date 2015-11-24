<?php

namespace Evo;

use Route;
use App;

class Router
{
	private $collection = array();
	private $registeredRoutes = array();
	private $regexMatcher = '([^/]*)';

	  public function __construct( )
	  {

	  }
	  /**
	   * Creates a route for each key passed into the collection (check Routes.php)
	   * Creates an instance of the Route class for each route passed in
	   * @param  array  $arr array of routes
	   * @return self
	   */
	  public function collection( $arr = array() )
	  {
	  	$this->collection = $arr;

	  	foreach($arr as $regex => $routeInfo)
	  	{
	  		$route = new Route( );
	  		# clean up our input and convert to Wordpress  route matching format
	  		$cleanRegex = strtolower( str_replace('/', '', $regex ));
	  		$cleanRegex = str_replace('?', '', $cleanRegex );
	  		$regex = str_replace( '?', $this->regexMatcher, $regex );
	  		# merge the routeInfo into the defaults
	  		$routeInfo = array_merge(
	  				array(
	  					"name" => $cleanRegex,
	  					"params" => array(),
	  					"ajax" => false
	  				),
	  				$routeInfo
	  		);
	  		# if there is no action defined, this can't do anything
	  		if( ! $routeInfo["action"] )
	  		{
	  			throw new Exception("You must pass the router an action.", 1);
	  		}

	  		$this->registeredRoutes[] = $routeInfo;

	  		$route->match( $regex, $routeInfo["name"], $routeInfo["params"], $routeInfo["action"], $routeInfo['ajax'] );
	  	}

	  	return $this;
	  }
	  /**
	   * Returns a list of the registered routes for debugging
	   * @return array
	   */
	  public function inspect( )
	  {
	  	return $this->registeredRoutes;
	  }
}
