<?php

// require_once __DIR__.'/vendor/autoload.php';

$klein = new \Klein\Klein;

$klein->respond('GET','/hello',function(){
	return "Hello world!";
});

$klein->respond('404', function($request){
	$r = "<h1>Uh-oh. 404!</h1>";
	$r .= "<p>The path '".$request->uri()."' does not exist.</p>";
	return $r;
});
$klein->dispatch();

