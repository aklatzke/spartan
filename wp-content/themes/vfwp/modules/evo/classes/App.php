<?php

use Evo\Core;
/**
 * Static interface overlying the Core class.
 * Grants access to underlying modules, initalizes the autoloader, and
 * bootstraps the application. Holds a single instance of the Core class (which functions much like a repository).
 */
final class App
{
	private static $instance;
	/**
	 * Initialize the singleton
	 */
	public static function start()
	{
		if( ! isset( self::$instance ) ){
			self::$instance = new Core();
		}

		return self::$instance;
	}
	/**
	 * Returns the underlying instance
	 */
	public static function get(  )
	{
		if( ! isset( self::$instance ) ) self::$instance = new Core();

		return self::$instance;
	}
	/**
	 * Loads the specified module based on its name or alias
	 * @param  string $name name or alias of the module
	 * @return mixed       false if not found, otherwise a new instance
	 */
	public static function module( $name )
	{
		if( self::$instance->get($name) !== null ) return self::$instance->get($name);

		return false;
	}
	/**
	 * Creates an alias for the specified module
	 * @param  string $name      the alias you want to use
	 * @param  string $reference 	the module you want to alias
	 * @return  Core $instance
	 * * Example usage:
	 *    Model 'registered-users' exists and was autoloaded.
	 *    You want to reference this as "users".
	 *    Call App::alias('users', 'registered-users');
	 *    You can now call App::module('users') to return an instance of the
	 *    	registered-users model
	 *     This is most useful for models as they're auto-loaded.
	 */
	public static function alias( $name, $reference )
	{
		return self::$instance->alias($name, $reference);
	}
	/**
	 * Sets a specific module to a reference
	 * Used mostly internally, but can be used to register an external
	 * library under a specific namespace.
	 * @param string $name
	 * @param mixed $reference 	the object you want to return upon this reference
	 * * Example Usage:
	 * 	We load in the AWS library through composer, but its
	 *  		namespace is Some\Really\Long\Namespace;
	 *  	We can use the set method to create a shortcut:
	 *   		App::set("AWS", new Some\Really\Long\Namespace);
	 *   	This also allows us to switch references without changing them
	 *    		application-wide by just changing the reference.
	 */
	public static function set( $name, $reference )
	{
		return self::$instance->set($name, $reference);
	}
	/**
	 * Returns the instance (whether or not it's initialized)
	 * @return Core $instance
	 */
	public static function instance()
	{
		return self::$instance;
	}
	/**
	 * Fetches a class in the helpers/ directory since they're not autoloaded
	 * @param  string $name  matching class/file name of helper to fetch
	 * @return mixed
	 */
	public static function helper( $name )
	{
		return self::$instance->getHelper($name);
	}
}