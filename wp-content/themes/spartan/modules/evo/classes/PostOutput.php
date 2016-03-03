<?php

namespace Evo;

use Utils;

class PostOutput{
	/**
	 * Posts to be output
	 * @var array
	 */
	private $posts = array();

	/**
	 * Constructor function
	 * If provided with an array upon construction, will use them in place of
	 * the global Wordpress query
	 * @param Array $provided
	 */
	public function __construct( $provided = array() ){
		if( empty($provided) ){
			global $wp_query;

			$this->posts = $wp_query->posts;
		}
		else{
			$this->posts = $provided;
		}
	}

	/**
	 * Gets posts from PostOutput
	 * @return array $posts
	 */
	public function getContents( ){
		return $this->posts;
	}

	/**
	 * Checks to see if currently provided $posts array is not empty
	 * @return bool
	 */
	public function exists( ){
		return count($this->posts) > 0;
 	}

 	/**
 	 * Provides the array to be used for output
 	 * @param  [type] $provided
 	 * @return [type]
 	 */
	public function provide( $provided ){
		$this->posts = $provided;

		return $this;
	}

	/**
	 * Starts the output buffer that captures post content
	 * @return bool [ true ]
	 */
	public function start( ){
		ob_start();

		return true;
	}

	/**
	 * Replaces delimited '{{var}}'' variables within a given string
	 * Used as a shortcut for variable output in the post loop
	 * @param  string $template
	 * @param  Object $vars - keys correspond to vars available
	 * @return string
	 */
	public static function replaceVars( $template, $vars, $index ){
		$regex = preg_match_all('{{{(\w|\d|\s){0,}}}}', $template, $matches, PREG_SET_ORDER);
		
		$vars->index = $index;

		foreach( $matches as $rawMatch ){
			$rawMatch = $rawMatch[0];
			$match = preg_replace('({|\s|})', '', $rawMatch);

			if( isset( $vars->{$match} ) ){
				$exploded = explode($rawMatch, $template);
				$template = implode($vars->{$match}, $exploded);
			}
		}
		return $template;
	}

	public function scrubPostMarkers(  ){
		foreach( $this->posts as $index => $post ){
			foreach( $post as $key => $value ){
				if( strpos( $key, 'post_' ) > -1 ){
					$newKey = substr($key, 5);
					$post->$newKey = $value;

					unset($post->$key);
				}
			}
		}

		return $this;
	}

	/**
	 * Ends the output buffer, loops to replace variables, and then appends the post list
	 * @outputs string template
	 * @return null
	 */
	public function end( ){
		$bufferContents = ob_get_contents();

		ob_end_clean();

		$output = '';
		foreach( $this->posts as $index => $post ){
			$output .= $this::replaceVars( $bufferContents, $post, $index);
		}

		echo $output;

		return null;
	}
}
