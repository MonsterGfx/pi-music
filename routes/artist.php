<?php

// Routes for artist requests
//
//		artist 					- list of artists
//				page head='Artists', list head=null
//
//		artist/1/album			- list of albums for artist=1
//				page head=artist.artist, list head=null
//
//		artist/1/album/2/song	- list of songs for artist=1, album=2
//				page head=artist.artist, list head=album+stats
//
//		artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
// 
// 		artist/1/song			- list of all songs for artist=1
//				page head=artist.artist, list head=null



// artist 					- list of artists
//
$klein->respond('GET', '/artist', function($request, $response){
	// get the list of artists
	$list = Artist::getList();

	Kint::dump($list); die;
	return "list of artists";

});


// artist/1/album			- list of albums for artist=1
//
$klein->respond('GET', '/artist/[:artist]/album', function($request, $response){
	return "albums for artist: ".$request->param('artist');

});


// artist/1/album/2/song	- list of songs for artist=1, album=2
//
$klein->respond('GET', '/artist/[:artist]/album/[:album]/song$', function($request, $response){
	return "songs for artist: ".$request->param('artist').", album: ".$request->param('album');

});


// artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
//
$klein->respond('GET', '/artist/[:artist]/album/[:album]/song/[i:song]$', function($request, $response){
	return "play song ".$request->param('song')." for artist: ".$request->param('artist').", album: ".$request->param('album');

});


// artist/1/song			- list of all songs for artist=1
//
$klein->respond('GET', '/artist/[:artist]/song', function($request, $response){
	return "songs for artist: ".$request->param('artist');

});
