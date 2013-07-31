<?php

// Routes for playlist requests
//		playlist 			- list of playlists
//				page head='Playlist', list head=null
//
//		playlist/1/song			- list of songs for playlist=1
//				page head=playlist.name, list head=null
//
//		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying


//		playlist 			- list of playlists
//
$klein->respond('GET', '/playlist', function($request, $response){
	return "list of playlists";

});


//		playlist/1/song			- list of songs for playlist=1
//
$klein->respond('GET', '/playlist/[:playlist]/song', function($request, $response){
	return "songs for playlist ".$request->param('playlist');

});


//		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying
//
$klein->respond('GET', '/playlist/[:playlist]/song/[:song]', function($request, $response){
	return "play song ".$request->param('song')." for playlist ".$request->param('playlist');

});




