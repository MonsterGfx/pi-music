<?php

// Routes for album requests
//
//		album 			- list all albums
//				page head='Albums', list head=null
//
//		album/1/song	- list of songs for album=1
//				page head=album.title, list head=album+stats
//
//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying


//		album 			- list all albums
//
$klein->respond('GET', '/album', function($request, $response){
	// get the list of albums
	$list = Album::getList();

	// walk the array and construct URLs
	array_walk($list, function(&$v, $k){
		$v = array(
			'name' => $v,
			'url' => '/album/'.Music::encode($v).'/song',
		);
	});

Kint::dump($list); die;
	return ListPage::render('Albums', null, null, Album::getList());
});


//		album/1/song	- list of songs for album=1
//
$klein->respond('GET', '/album/[:album]/song', function($request, $response){
	return "songs for album: ".$request->param('album');

});


//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
//
$klein->respond('GET', '/album/[:album]/song/[i:song]', function($request, $response){
	return "play song ".$request->param('song')." for album: ".$request->param('album');

});


