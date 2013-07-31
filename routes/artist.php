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

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k){
		$v = array(
			'name' => $v,
			'url' => '/artist/'.Music::encode($v).'/album',
		);
	});

	return ListPage::render('Artists', null, null, $list);
});


// artist/1/album			- list of albums for artist=1
//
$klein->respond('GET', '/artist/[:artist]/album', function($request, $response){
	// get the artist name
	$artist = Music::decode($request->param('artist'));

	// get the list of albums
	$list = Artist::getAlbums($artist);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($artist) {
		$v = array(
			'name' => $v['album'],
			'url' => '/artist/'.Music::encode($artist).'/album/'.Music::encode($v['album']).'/song',
		);
	});

	return ListPage::render($artist, null, null, $list);
});


// artist/1/album/2/song	- list of songs for artist=1, album=2
//
$klein->respond('GET', '/artist/[:artist]/album/[:album]/song', function($request, $response){
	// get the parameters
	$artist = Music::decode($request->param('artist'));
	$album = Music::decode($request->param('album'));

	// get the song list
	$list = Album::getSongs($artist, $album);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($artist, $album) {
		$v = array(
			'name' => $v['Title'],
			'url' => '/album/'.Music::encode($artist.'|'.$album).'/song/'.$v['Track'],
		);
	});

	// render($page_title, $previous, $album_stats, $list)
	return ListPage::render($album, null, null, $list);
});


// artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
//
$klein->respond('GET', '/artist/[:artist]/album/[:album]/song/[:song]', function($request, $response){
	return "play song ".Music::decode($request->param('song'))." for artist: ".$request->param('artist').", album: ".$request->param('album');

});


// artist/1/song			- list of all songs for artist=1
//
$klein->respond('GET', '/artist/[:artist]/song', function($request, $response){

	// @todo play song
	
	return "songs for artist: ".$request->param('artist');

});
