<?php

class Utils{
	# the queue that will hold the scripts and stylesheets
	# to be enqueue'd with Wordpress
	private $scriptQueue = array();
	# set a cache group so that we don't need to worry about
	# overriding any other plugin namespacing
	CONST CACHE_GROUP = 'evo-internal-';

	public function __construct(){
		add_action('admin_enqueue_scripts', array( $this, 'processScriptQueue' ) );
		add_action('wp_enqueue_scripts', array( $this, 'processScriptQueue' ) );
		add_action('image_send_to_editor', array( $this, 'processMediaSend' ), 0, 8);
		add_action('media_upload_default_type', array( $this, 'setMediaSendHook' ), 10);
	}

	public function customMediaSend( $id, $callback ){
		# since the media uploader uses an iFrame and we won't be able to preserve
		# object state, we're going to store them in $_SESSION
		if( !isset( $_SESSION['media_handler_' . $id] ) ){
			$_SESSION['media_handler_' . $id] = $callback;
		}

		return $this;
	}
	/**
	 * Triggers a 404 error
	 * No return value - calls Wordpress' internal get_404_template()
	 * and then exits
	 */
	public static function trigger404(){
		status_header(404);
		include( get_404_template() );
		exit;
	}
	/**
	 * Confirms that the currently global post type, or a queried post,
	 * is of a specific post type
	 * @param  string  $postType   the post type to check against
	 * @param  boolean $comparison   the post to compare against (defaults to global $post)
	 * @return  boolean             does the post type match
	 */
	public static function isPostType( $postType, $comparison = false ){
		$type = '';

		if( !$comparison ){
			global $post;

			$type = $post->post_type;
		}
		else{
			$type = $comparison->post_type;
		}

		return $postType === $type;
	}
	/**
	 * Used for Wordpress media uploads
	 */
	public function setMediaSendHook( ){
		$query = $_SERVER['QUERY_STRING'];
		$handler = $query;
		if( strpos( $query, 'media_handler' ) !== false ){
			# tb_iframe is (annoyingly) required for this
			$handler = str_replace('TB_iframe=true', '', $query);
			# remove the random int attached to the query string
			$handler = explode('&random', $query);
			$handler = $handler[0];
			# get the arg
			$handler = explode('=', $handler);
			$handler = $handler[1];
			# remove any excess
			$handler = explode('&', $handler);
			$handler = $handler[0];
			# forgive me
			echo "<script> document.onready = function(){var form = document.getElementById( 'library-form' ); if( form ){form.action += '&media_handler={$handler}'; } } </script>";
		}

		return 'file';
	}

	public function processMediaSend( $html, $id, $caption, $title, $align, $url, $size, $alt ){
		# we will need to tease the handler ID from the referrer
		$query = $_POST['_wp_http_referer'];

		if( strpos( $query, 'media_handler' ) !== false ){
			$handler = explode( 'media_handler=', $query );
			$handler = $handler[1];
			$handler = explode( '&', $handler );
			$handler = $handler[0];
			# a bit more organized args object
			$args = (object) array(
				"html" => $html,
				"id" => $id,
				"caption" => $caption,
				"title" => $title,
				"align" => $align,
				"url" => $url,
				"size" => $size,
				"alt" => $alt
			);

			# check if our handler is in session
			if( isset( $_SESSION['media_handler_' . $handler] ) ){
				$callback = $_SESSION['media_handler_' . $handler];
				# get the callback's return arguments
				$val = $callback($args);
				# json_encode it for the frontend
				$val = json_encode($val);

				return $val;
			}
		}

		# return the default if we haven't returned anything else
		return $html;
	}
	/**
	 * Gets a custom meta value, and executes the callback function
	 * based on postID
	 * @param  int $postID
	 * @param  string $field    field value to fetch
	 * @param  callable $callback  callback to be run after fetch
	 * @return            [description]
	 */
	public static function getCustomOption( $postID, $field, $callback ){
	  $meta = get_post_custom( $postID );
	  $post = get_post( $postID );
	  $option = isset($meta[$field]) ? $meta[$field][0] : '';
	  return $callback ? $callback($option, $meta, $post) : $option;
	}
	/**
	 * Registers a stylesheet to be loaded into the admin area
	 * @param  string $path   path to the CSS file
	 * @param  array $restrictions    array of restrictions that the page must pass before the script is loaded (e.g. postID)
	 * @return true
	 */
	public function registerAdminStylesheet( $path, $restrictions = null ){
		$script = array( "type" => "stylesheet", "path" => $path, "target" => "admin", "restrictions" => $restrictions  );

		$this->queueScript( $script );

		return true;
	}
	/**
	 * Registers a javascript file to be loaded into the admin area
	 * @param  string $path   path to the CSS file
	 * @param  array $restrictions    array of restrictions that the page must pass before the script is loaded (e.g. postID)
	 * @return true
	 */
	public function registerAdminJavascript( $path, $restrictions = null ){
		$script = array( "type" => "javascript", "path" => $path, "target" => "admin", "restrictions" => $restrictions  );

		$this->queueScript( $script );

		return true;
	}
	/**
	 * Registers a stylesheet to be loaded into the frontend area
	 * @param  string $path   path to the CSS file
	 * @param  array $restrictions    array of restrictions that the page must pass before the script is loaded (e.g. postID)
	 * @return true
	 */
	public function registerStylesheet( $path, $restrictions = null ){
		$script = array( "type" => "stylesheet", "path" => $path, "target" => "public", "restrictions" => $restrictions  );

		$this->queueScript( $script );

		return true;
	}
	/**
	 * Registers a javascript file to be loaded into the frontend area
	 * @param  string $path   path to the CSS file
	 * @param  array $restrictions    array of restrictions that the page must pass before the script is loaded (e.g. postID)
	 * @return true
	 */
	public function registerJavascript( $path, $restrictions = null ){
		$script = array( "type" => "javascript", "path" => $path, "target" => "public", "restrictions" => $restrictions );

		$this->queueScript( $script );

		return true;
	}
	/**
	 * Loops through the registered scripts, checks any restrictions,
	 * and loads them into the current page
	 */
	public function processScriptQueue(){
		global $post;

		$scripts = $this->scriptQueue;

		foreach( $scripts as $script ){
			if( isset( $script["restrictions"] ) && $script["restrictions"] ){
				# loop through the restrictions and make certain
				# current post data fits the restrictions
				# otherwise, skip the script
				foreach( $script["restrictions"] as $restriction => $value ){
					if( $post && ! $post->{$restriction} === $value ){
						continue;
					}
				}
			}

			if( $script["type"] === "stylesheet" && ( $script["target"] === "admin" && is_admin() ) ){
				wp_enqueue_style( $script["path"], $script["path"], false );
			}
			elseif( $script["type"] === "javascript" && ( $script["target"] === "admin" && is_admin() )  ){
				wp_enqueue_script( $script["path"], $script["path"], false );
			}
			elseif( $script["type"] === "stylesheet" && ( $script["target"] === "public" && !is_admin() )  ){
				wp_enqueue_style( $script["path"], $script["path"], false );
			}
			elseif( $script["type"] === "javascript" && ( $script["target"] === "public" && !is_admin() )  ){
				wp_enqueue_script( $script["path"], $script["path"], false );
			}
		}
	}

	# Auxiliary/Dev Methods
	/**
	 * dd = dump and die
	 */
	public static function dd($var, $name = 'Output End'){
		self::dump($var, $name);
		die();
	}
	/**
	 * Prettier dump output that's formatted and easier to read
	 */
	public static function dump($var, $name = 'Output'){
		echo "<style>body{background-color:#444;padding:45px;}pre{box-shadow: 0px 4px 10px 0px rgba(0, 0, 0, 0.75);margin:0px;padding:25px;background-color:white;font-size:14px;min-height:400px;max-height:700px;overflow-y:scroll;}h1,h2{font-size:18px;font-family:Helvetica, Arial;margin:0px;padding: 15px 15px;background-color:#222;color:white;}</style>";
		echo "<h1>" . $name . "<span style='float:right;'></span></h1><pre>";
			var_dump($var);
		echo "</pre>";
	}

	# Theme Componentry
	# These each fetch a specific type of content and complete the template path

	# Returns file path path to any theme component
	public static function getThemeComponent( $extraPath = '' ){
		return get_template_directory() . $extraPath;
	}
	# Returns URL path for frontend assets (images, javascript, css)
	public static function getThemeAsset( $extraPath = '' ){
		return get_template_directory_uri() . $extraPath;
	}
	# Returns path to a template component located in {moduleName}/assets/templates/{fileName}.php
	# defaults to constant MODULE_NAME if not provided
	public static function getThemePartial( $filename, $moduleName = MODULE_NAME ){
		return self::getThemeComponent( '/modules/' . $moduleName . '/assets/templates/' . $filename . '.php' );
	}
	# Returns path to an image located in {moduleName}/assets/images/{fileName}
	# defaults to constant MODULE_NAME if not provided
	public static function getModuleImage( $filename, $moduleName = MODULE_NAME ){
		return self::getThemeAsset() . '/modules/' . $moduleName . '/assets/images/' . $filename;
	}
	# End Theme Componentry

	# Caching Methods
	/**
	 * Puts a value into the cache if it does not already exist
	 * @param  string  $key
	 * @param  mixed  $value
	 * @param  integer $duration  		cache duration
	 * @return mixed            true or false, depending on success
	 */
	public static function cachePut( $key, $value, $duration = 60 ){
		if( ! self::cacheGet( $key ) ){
			self::cacheSet( $key, $duration );
		}

		return false;
	}
	/**
	 * Sets a value to the cache regardless of its existence
	 * @param  $key
	 * @param  $value
	 * @param  integer $duration    cache duration
	 * @return boolean
	 */
	public static function cacheSet( $key, $value, $duration = 60 ){
		return set_transient( self::CACHE_GROUP . $key, json_encode( $value ), $duration );
	}
	/**
	 * Gets a value from the cache.
	 * Can be disabled by settings the disable_cache parameter to true
	 * @param  $key
	 * @return  mixed   cache value
	 */
	public static function cacheGet( $key ){
		if( isset($_GET['disable_cache']) ) return false;

		return json_decode( get_transient( self::CACHE_GROUP . $key ) );
	}
	/**
	 * Deletes the key and associated data from the cache
	 * @param $key
	 * @return boolean
	 */
	public static function cacheDelete( $key ){
		return delete_transient( self::CACHE_GROUP . $key );
	}
	/**
	 * Gets a value from the cache and then deletes
	 * the cache entry
	 * @param $key
	 * @return mixed   cache value
	 */
	public static function cachePull( $key ){
		$val = self::cacheGet( $key );

		self::cacheDelete( $key );

		return $val;
	}

	# End Caching Methods
	/**
	 * Redirects to a either a full URL (e.g. http://google.com) or to a sub-page when
	 * passed something without an HTTP prefix (e.g. /route/example)
	 * @param  string $location   location to redirect to
	 * @param  string $statusCode    status code to send with the redirect (e.g. 301)
	 */
	public static function redirect( $location, $statusCode = '200' ){
		$baseURL = "";

		if( ! (bool) preg_match("#^http(s)?#", $location) ) $baseURL = get_site_url();

		header( 'Location: ' . $baseURL . $location, $statusCode );
		die();
	}
	/**
	 * Converts XML data to JSON data
	 * @param  XML $doc     XML document to be parsed
	 * @return JSON    json data result of parsing
	 */
	public static function xmlToJson( $doc ){
		$xml = simplexml_load_string($doc);
		$json = json_encode($xml);
		$json = json_decode($json,TRUE);

		return $json;
	}
	/**
	 * Wrapper of wp_remote_get. Functions as a server side GET request.
	 * @param  string $url     url to query
	 * @return mixed       endpoint response
	 */
	public static function http( $url  ){
		$response = wp_remote_get($url);

		return $response;
	}

	/**
	 * Internal use only - queues a script into the script queue
	 * @param  array $script    script passed from the register* functions
	 * @return true
	 */
	private function queueScript( $script ){
		array_push($this->scriptQueue, $script);

		return true;
	}
}