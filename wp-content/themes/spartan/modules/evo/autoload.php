<?php
	# silence all errors by default unless ?debug_all=true is present
	error_reporting(0);

	if( isset($_GET["debug_all"]) && $_GET["debug_all"] == true ){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	# This is the bootstrap file that will load all of the required functionality
	# on both the back and front end for the module
	#-----------------------------------------
	# Load Class Assets
	#-----------------------------------------
	# \Core houses global objects
	require("classes/Core.php");
	# \TypeBuilder creates dynamic custom post types and handles
	# saving, updating, etc. automatically
	require("classes/TypeBuilder.php");
	# \Utils is a collection of utilities that create shortcuts and generally turn
	# nasty Wordpress syntax into something prettier
	require("classes/Utils.php");
	# \HTMLBuilder provides shortcut methods for creating inputs, text boxes, etc.
	# to keep the amount of HTML echoing we have to do to a minimum within the logic
	require("classes/Form.php");
	# \AjaxRouter provides an interface to use wp_ajax without the boilerplate
	# This class is mostly internal at this point - the Router class will handle ajax routing and automatically
	# encode your response.
	require("classes/AjaxRouter.php");
	# \Route provides an interface to use wp_rewrite without the boilerplate and allows URL matching
	require("classes/Route.php");
	# \Actions provides an interface to use Wordpress actions
	require("classes/Actions.php");
	# \PostOutput provides a cleaner interface than the infamous "the_loop"
	require("classes/PostOutput.php");
	# \Mailer simplifies the mail class
	require("classes/Mailer.php");
	require("classes/BaseController.php");
	require("classes/Router.php");

	# Input handling
	require("classes/InputRepository.php");
	require("classes/Input.php");

	# creates a singleton app object so that we don't have to keep reinstating the
	# global variable
	require("classes/App.php");

	App::start();

	App::instance()->autoload( dirname(__FILE__) );

	App::set( 'router', new Evo\Router(  ) );
	App::set( 'utils', new Utils(  ) );
	App::set( 'output', new Evo\PostOutput(  ) );
	App::set( 'mailer', new Evo\Mailer(  ) );

	require("includes/routes.php");
	require("includes/endpoints.php");

	#-----------------------------------------
	# Load static assets for the frontend and admin
	# You can use these same utility functions to load
	#-----------------------------------------
	App::module('utils')->registerJavascript( Utils::getThemeAsset( '/modules/example/assets/js/es5.js' ) );
	App::module('utils')->registerAdminJavascript( Utils::getThemeAsset( '/modules/example/assets/js/es5.js' ) );

	# Some default action setup
	Actions::on('parse_request', function( $queryVars ){
		# populate the input singleton
		Input::populate($queryVars);
	}, 0, 4);
