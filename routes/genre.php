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
	// get the list of albums
	$list = Genre::getList();

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k){
		$v = array(
			'name' => $v,
			'url' => '/genre/'.Music::encode($v).'/artist',
		);
	});

	return ListPage::render('Albums', null, null, $list);
});


//		genre/1/artist 	- list of artists for genre=1
//
$klein->respond('GET', '/genre/[:genre]/artist', function($request, $response){
	// get the parameter
	$genre = Music::decode($request->param('genre'));

	// get the list
	$list = Genre::getArtists($genre);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($genre) {
		$v = array(
			'name' => $v['artist'],
			'url' => '/genre/'.Music::encode($genre).'/artist/'.Music::encode($v['artist']).'/album',
		);
	});

	return ListPage::render($genre, null, null, $list);
});


//		genre/1/artist/2/album 	- list of albums for genre=1, artist=2
//
$klein->respond('GET', '/genre/[:genre]/artist/[:artist]/album', function($request, $response){
	// get the parameter
	$genre = Music::decode($request->param('genre'));
	$artist = Music::decode($request->param('artist'));

	// get the list
	$list = Genre::getAlbums($genre, $artist);
	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($genre) {
		$v = array(
			'name' => $v['album'],
			'url' => '/genre/'.Music::encode($genre).'/artist/'.Music::encode($v['artist']).'/album/'.Music::encode($v['album']).'/song',
		);
	});

	return ListPage::render($artist, null, null, $list);
});


//		genre/1/artist/2/album/3/song	- list of songs for genre=1, artist=2, album=3
//
$klein->respond('GET', '/genre/[:genre]/artist/[:artist]/album/[:album]/song', function($request, $response){
	// get the parameter
	$genre = Music::decode($request->param('genre'));
	$artist = Music::decode($request->param('artist'));
	$album = Music::decode($request->param('album'));

	// get the list
	$list = Genre::getSongs($genre, $artist, $album);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($genre) {
		$v = array(
			'name' => $v['Title'],
			'url' => '/genre/'.Music::encode($genre).'/artist/'.Music::encode($v['Artist']).'/album/'.Music::encode($v['Album']).'/song/'.Music::encode($v['file']),
		);
	});

	return ListPage::render($album, null, null, $list);
});


//		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying
//
$klein->respond('GET', '/genre/[:genre]/artist/[:artist]/album/[:album]/song/[:song]', function($request, $response){
	// get the parameter
	$genre = Music::decode($request->param('genre'));
	$artist = Music::decode($request->param('artist'));
	$album = Music::decode($request->param('album'));
	$song = Music::decode($request->param('song'));

	// clear the playlist
	Music::send('clear');

	// get the list
	$songs = Genre::getSongs($genre, $artist, $album);

	// load the playlist with the requested songs (and figure out the current
	// song position)
	$pos = 0;
	for($i=0; $i<count($songs); $i++)
	{
		Music::send('add', $songs[$i]['file']);
		if($songs[$i]['file']==$song)
			$pos = $i;
	}

	// start playing the selected song
	Music::send('play', $pos);

	// redirect to "now playing"
	header('Location: /');
	die;
});


