<?php

// Routes for genre requests
//		genre 		- list all genres
//				page head='Genres', list head=null
//
//		genre/1/artist 	- list of artists for genre=1
//				page head=genre.name, list head=null
//
//		genre/1/artist/2/album 	- list of albums for genre=1, artist=2
//				page head=artist.artist, list head=null
//
//		genre/1/artist/2/album/3/song	- list of songs for genre=1, artist=2, album=3
//				page head=artist.artist, list head=album+stats
//
//
//		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying


//		genre 		- list all genres
//
$klein->respond('GET', '/genre', function($request, $response){
	return "list of genres";

});


//		genre/1/artist 	- list of artists for genre=1
//
$klein->respond('GET', '/genre/[:genre]/artist', function($request, $response){
	return "artist for genre ".$request->param('genre');

});


//		genre/1/artist/2/album 	- list of albums for genre=1, artist=2
//
$klein->respond('GET', '/genre/[:genre]/artist/[:artist]/album', function($request, $response){
	return "albums for genre ".$request->param('genre')." and artist ".$request->param('artist');

});


//		genre/1/artist/2/album/3/song	- list of songs for genre=1, artist=2, album=3
//
$klein->respond('GET', '/genre/[:genre]/artist/[:artist]/album/[:album]/song', function($request, $response){
	return "songs for genre ".$request->param('genre')." and artist ".$request->param('artist')." and album ".$request->param('album');

});


//		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying
//
$klein->respond('GET', '/genre/[:genre]/artist/[:artist]/album/[:album]/song/[i:song]', function($request, $response){
	return "play song ".$request->param('song')." for genre ".$request->param('genre')." and artist ".$request->param('artist')." and album ".$request->param('album');

});


