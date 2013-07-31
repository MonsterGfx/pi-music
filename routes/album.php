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
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k){
		$v = array(
			'name' => $v['album'],
			'url' => '/album/'.Music::encode($v['artist'].'|'.$v['album']).'/song',
		);
	});

	return ListPage::render('Albums', null, null, $list);
});


//		album/1/song	- list of songs for album=1
//
$klein->respond('GET', '/album/[:album]/song', function($request, $response){
	// get the artist & album values
	list($artist, $album) = explode('|',Music::decode($request->param('album')));

	// get the list of songs
	$list = Album::getSongs($artist, $album);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($artist, $album) {
		$v = array(
			'name' => $v['Title'],
			'url' => '/album/'.Music::encode($artist.'|'.$album).'/song/'.Music::encode($v['file']),
		);
	});

	// render($page_title, $previous, $album_stats, $list)
	return ListPage::render($album, null, null, $list);
});


//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
//
$klein->respond('GET', '/album/[:album]/song/[:song]', function($request, $response){

	// @todo start playing the requested song

	return "play song ".$request->param('song')." for album: ".$request->param('album');

});


