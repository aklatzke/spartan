<?php

namespace Evo;

class InputRepository
{
	private $collection = array();

	public function __construct( $input )
	{
		$this->collection = $this->format($input);
	}
	/**
	 * Formats input from $_GET and $_POST vars into a stdClass object.
	 * Also creates the params object from the Wordpress query vars.
	 * Params are also valid to be fetched from the "vars" bucket.
	 * @param  array $input Wordpress query vars
	 * @return stdClass        [params => [URL Params], vars => [Request Vars]]
	 */
	private function format( $input )
	{
	        $vars = new \stdClass();

	        $vars->params = $input->query_vars;

	        $vars->vars = array();

	        foreach ( array($_GET, $_POST) as $inputVar ) {
	            $vars->vars =  array_merge($vars->vars, $inputVar);
	        }

	        $vars->vars = array_merge($vars->vars, $vars->params);

	        return $vars;
	}
	/**
	 * Returns all request variables
	 * @return stdClass
	 */
	public function all(  )
	{
		return $this->collection->vars;
	}
	/**
	 * Gets a specific query variable or input parameter
	 * @param  key $name key to fetch
	 * @return mixed       value on success, null on failure
	 */
	public function get( $name )
	{
		if( isset( $this->collection->vars[$name] ) ) return $this->collection->vars[$name];

		return null;
	}

	/**
	 * Returns a URL parameter, but will not fetch from
	 * request vars
	 * @param  key $name  parameter to fetch
	 * @return mixed      value on success, null on failure
	 */
	public function param( $name )
	{
		if( isset( $this->collection->params[$name] ) ) return $this->collection->params[$name];

		return null;
	}
}