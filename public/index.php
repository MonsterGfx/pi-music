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
	// oh no!
	echo "<h1>Unhandled Exception!</h1>";

	echo "\n<!--\n\n";
	print_r($e);
	echo "-->\n\n";

	// dump the exception
	Kint::dump($e);

	// if database logging is enabled...
	if(Config::get('database.logging'))
	{
		// try to get the last query (or a "none" message if there isn't one)
		$last = ORM::get_last_query() ?: '-- none --';

		// report the last query
		echo "<h2>Last Query</h2>";
		echo "<pre>$last</pre>";
	}
}
