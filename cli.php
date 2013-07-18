<?php

// load the autoloader
require_once 'vendor/autoload.php';

// get the arguments from the command line
$args = $argv;

// remove the first element (which is the script name)
array_shift($args);

// load the list of commands
$commands = Config::get('cli.commands');

// build the request
// here I'm "tricking" the routing system into routing a non-HTTP request
$request = new \Klein\Request(array(), array(), array(), array('REQUEST_URI' => '/'.implode('/',$args)));

// instantiate the Klein router
$klein = new \Klein\Klein;

// now add the commands to the router
foreach($commands as $value=>$method)
{
	// set up a responder
	// This responder will handle any URI of the form /$value/x/y/z/.... and
	// will pass any arguments to the specified method.
	$klein->respond('GET', "/{$value}/[*]?", function($request,$response) use ($method) {

		// get the arguments
		$args = array();
		if($request->uri())
		{
			// explode the arguments
			$args = explode('/',$request->uri());

			// remove any empty values
			$args = array_values(array_filter($args));

			// remove the first element (which is the command)
			array_shift($args);
		}

		// call the requested method with any arguments that were passed
		call_user_func_array($method, $args);
	});
}

// add a "not found" response in case it's an invalid command
$klein->respond('404', function($request){

	// explode the arguments
	$args = explode('/',$request->uri());

	// remove any empty values
	$args = array_values(array_filter($args));

	// output the error message
	echo "\nCommand not found: {$args[0]}\n";
});

$klein->dispatch($request);
