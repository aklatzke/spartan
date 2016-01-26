<?php

namespace Evo;

class Core
{
    public $modules = array();
    private $aliases = array();

    public function __construct()
    {

    }
    /**
     * Autoloading for models
     * @param  string $name
     */
    public function loadModel($name)
    {
  	include __DIR__ . '/../models/' . $name . '.php';

  	$this->set( $name, new $name );
    }
    /**
     * Creates an alias for the specified module
     * @param  string $name      the alias you want to use
     * @param  string $reference    the module you want to alias
     * @return  Core $instance
     * * Example usage:
     *    Model 'registered-users' exists and was autoloaded.
     *    You want to reference this as "users".
     *    Call App::alias('users', 'registered-users');
     *    You can now call App::module('users') to return an instance of the
     *      registered-users model
     *     This is most useful for models as they're auto-loaded.
     */
    public function alias( $name, $alias )
    {

    	$this->aliases[$alias] = $this->modules[$name];
    	$this->{$alias} = $this->modules[$name];

    	return $this;
    }
    /**
     * Fetches an instance of the specified helper from the helper directory.
     * @param  string $name
     * @return mixed
     */
    public function getHelper( $name )
    {
        include __DIR__. '/../helpers/' . $name . ".php";

        return ( new $name );
    }
    /**
     * Autoloading for controllers
     * @param  string $name
     */
    public function loadController($name)
    {
 	include __DIR__.'/../controllers/' . $name . '.php';

 	$this->set( $name, new $name($this) );
    }
    /**
     * Returns the module at the specified name or alias
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
    	if( isset( $this->aliases[$name] ) ) return $this->aliases[$name];

        	return $this->modules[$name];
    }
    /**
     * Sets a class instance to a specific reference (useful for models, as they need to
     * retain internal state to work properly)
     * @param string $name  the reference to set
     * @param mixed $ref  object instance to be referenced
     */
    public function set($name, $ref)
    {
        $this->{$name} = $ref;

        return $this->modules[$name] = $ref;
    }
    /**
     * Walks the directory structure and autoloads all controller and model files
     * @param  string $directory  used to specify the directory that should be loaded
     * @return self
     */
    public function autoload( $directory )
    {
    	$directory .= "/";

    	$models = $directory . "models";
    	$controllers = $directory . "controllers";

    	foreach( glob( $models . "/*.php" ) as $file )
    	{
    		$exploded = explode("/", $file);
    		$modelName = rtrim( array_pop($exploded), '.php' );

    		$this->loadModel( $modelName );
    	}

    	foreach( glob( $controllers . "/*Controller.php" ) as $file )
    	{
    		$exploded = explode("/", $file);
    		$controllerName = rtrim( array_pop($exploded), '.php' );

    		$this->loadController( $controllerName );
    	}

    	return $this;
    }
}
