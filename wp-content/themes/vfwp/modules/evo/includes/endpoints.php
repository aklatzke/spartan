<?php
# you can use this in order to hook into a custom upload action
# check shared.js for the relevant code
App::module('utils')->customMediaSend( 'exampleMediaSend', function( $args ){
	$imageMeta = get_post( $args->id );

	$url = wp_get_attachment_image_src( $args->id, 'large', true );
	$thumb = wp_get_attachment_image_src( $args->id, 'large', true );

	return array(
			"rawUrl" => $url[0],
			"thumb" => $thumb[0],
			"id" => $args->id,
			"meta" => $imageMeta
		);
});
