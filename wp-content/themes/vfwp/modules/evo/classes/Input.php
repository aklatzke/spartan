<?php

class Input
{
	private static $repository;
	/**
	 * Populates the repository with the global variables
	 * @param  array $queryVars
	 * @return  InputRepository
	 */
	public static function populate( $queryVars )
	{
		if( isset( self::$repository ) ) return self::$repository( $queryVars );

		self::$repository = new Evo\InputRepository( $queryVars );

		return self::$repository;
	}
	/**
	 * Alias for ->get()
	 * @param  string $var   key to find
	 * @return mixed      value of the key
	 */
	public static function find( $var )
	{
		return self::$repository->get($var);
	}
	/**
	 * Fetches a parameter from the input repository
	 * @param  string $var key
	 * @return mixed      value
	 */
	public static function get( $var )
	{
		return self::find($var);
	}
	/**
	 * Fetches a URL parameter from the repository (will not return the value of a
	 * query var)
	 * @param  string $var key for URL parameter
	 * @return string      URL parameter value
	 */
	public static function param( $var )
	{
		return self::$repository->param($var);
	}
	/**
	 * Returns all query vars from the repository
	 * @return array
	 */
	public static function all(  )
	{
		return self::$repository->all();
	}
	/**
	 * Checks for the existence of a specific variable within the
	 * query parameters
	 * @param  string $var key
	 * @return boolean
	 */
	public static function exists( $var )
	{
		if( self::$repository->get($var) ) return true;

		return false;
	}
}