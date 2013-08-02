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

	$list = array_filter($list, function($v){ return $v['name'] ? true : false; });

	return ListPage::render('Artists', null, false, $list);
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

	// build the "previous" link data
	$previous = array(
		'path' => '/artist',
		'text' => 'Artists',
	);

	return ListPage::render($artist, $previous, false, $list);
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
			'url' => '/album/'.Music::encode($artist.'|'.$album).'/song/'.Music::encode($v['file']),
		);
	});

	// build the "previous" link data
	$previous = array(
		'path' => '/artist/'.$request->param('artist').'/album',
		'text' => $artist,
	);

	// build the shuffle link
	$shuffle = '/album/'.Music::encode($artist.'|'.$album).'/song/shuffle';

	return ListPage::render($album, $previous, $shuffle, $list);
});


// artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
//
$klein->respond('GET', '/artist/[:artist]/album/[:album]/song/[:song]', function($request, $response){
	// get the parameters
	// get the artist & album values
	$artist = Music::decode($request->param('artist'));
	$album = Music::decode($request->param('album'));
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
		Music::send('add', $songs[$i]['file']);
		if($songs[$i]['file']==$song)
			$pos = $i;
	}

	// turn off "shuffle"
	Music::shuffle(false);

	// is the current song "shuffle"
	if($song=='shuffle')
	{
		// choose a random song
		$pos = rand(0,count($songs));

		// turn on shuffle
		Music::shuffle(true);
	}

	// start playing the selected song
	Music::send('play', $pos);

	// redirect to "now playing"
	header('Location: /');
	die;
});


// artist/1/song			- list of all songs for artist=1
//
$klein->respond('GET', '/artist/[:artist]/song', function($request, $response){
	// get the parameters
	$artist = Music::decode($request->param('artist'));

	// get the song list
	$list = Artist::getSongs($artist);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($artist) {
		$v = array(
			'name' => $v['Title'],
			'url' => '/artist/'.Music::encode($artist).'/song/'.Music::encode($v['file']),
		);
	});

	// build the "previous" link data
	$previous = array(
		'path' => '/artist',
		'text' => 'Artists',
	);

	// build the shuffle link
	$shuffle = '/artist/'.Music::encode($artist).'/song/shuffle';

	return ListPage::render($artist, $previous, $shuffle, $list);
});
