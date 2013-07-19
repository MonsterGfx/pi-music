<?php

try {

	// include the autoload file, which will include any other required files
	//
	require_once __DIR__.'/../vendor/autoload.php';

	// handle any "bootstrapping" - application-wide configuration tasks
	//
	require_once __DIR__.'/../bootstrap.php';

	// start the route processor
	//
	require_once __DIR__.'/../routes.php';

}
catch(Exception $e) {
	echo "<h1>Unhandled Exception!</h1>";

	Kint::dump($e);
}
