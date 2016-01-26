<?php

final class Actions{
	public function __construct(){}

	/**
	 * Adds specified action with sensible defaults
	 * @aliasof  add_action
	 * @param  string  $action
	 * @param  closure  $callback
	 * @param  integer $priority
	 * @param  integer $args
	 * @return boolean
	 */
	public static function on( $action, $callback, $priority = 10, $args = 3 ){
		return add_action( $action, $callback, $priority, $args );
	}

	/**
	 * Trigger's the specified action with specified arguments
	 * @aliasof do_action
	 * @param  string $action
	 * @param  array  $args
	 * @return boolean
	 */
	public static function trigger( $action, $args = array() ){
		return do_action( $action, $args );
	}
}
