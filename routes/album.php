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

	return ListPage::render('Albums', null, false, $list);
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

	// build the "previous" link data
	$previous = array(
		'path' => '/album',
		'text' => 'Albums',
	);

	// build the shuffle link
	$shuffle = '/album/'.Music::encode($artist.'|'.$album).'/song/shuffle';

	return ListPage::render($album, $previous, $shuffle, $list);
});


//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
//
$klein->respond('GET', '/album/[:album]/song/[:song]', function($request, $response){
	// get the parameters
	// get the artist & album values
	list($artist, $album) = explode('|',Music::decode($request->param('album')));
	$song = Music::decode($request->param('song'));

	// clear the playlist
	Music::send('clear');

	// get the list of songs
	$songs = Album::getSongs($artist,$album);

	// load the playlist with the requested songs (and figure out the current
	// song position)
	$pos = 0;
	for($i=0; $i<count($songs); $i++)
	{
		// add the current song to the current playlist
		Music::send('add', $songs[$i]['file']);

		// see if the current song is the one the user selected
		if($songs[$i]['file']==$song)
			$pos = $i;
	}

	// start playing the selected song
	Music::send('play', $pos);

	// redirect to "now playing"
	header('Location: /');
	die;
});


