<?php

// Routes for song requests
//		song 	- list of all songs
//				page head='Songs', list head=null
//
//		song/1 	- load ALL songs, play song=1, go to nowplaying


//		song 	- list of all songs
//
$klein->respond('GET', '/song', function($request, $response){
	return "list of all songs";

});


//		song/1 	- load ALL songs, play song=1, go to nowplaying
//
$klein->respond('GET', '/song/[:song]', function($request, $response){
	return "start playing song ".$request->param('song');

});




