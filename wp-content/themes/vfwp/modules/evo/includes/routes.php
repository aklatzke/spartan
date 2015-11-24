<?php

App::module('router')->collection(array(
	# example normal route using
	# the controller method
	'example/test' => array(
		'action' => 'HomeController::test',
	),
	# here is an example that is both ajax
	# and uses the closure method
	'example/ajax' => array(
		'ajax' => true,
		'action' => function(  )
		{
			# this return value will be automatically encoded
			# and echoed out to the requesting server.
			# if you're using jQuery's $.getJSON, it will
			# be automatically parsed
			return "Hello World";
		}
	)
)) ;