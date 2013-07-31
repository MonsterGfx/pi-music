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
	// get the list of playlists
	$list = Playlist::getList();

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k){
		$v = array(
			'name' => $v,
			'url' => '/playlist/'.Music::encode($v).'/song',
		);
	});

	return ListPage::render('Playlists', null, null, $list);
});


//		playlist/1/song			- list of songs for playlist=1
//
$klein->respond('GET', '/playlist/[:playlist]/song', function($request, $response){
	// get the params
	$playlist = Music::decode($request->param('playlist'));

	// get the list of songs
	$list = Playlist::getSongs($playlist);

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k) use ($playlist) {
		$v = array(
			'name' => $v['Title'],
			'url' => '/playlist/'.Music::encode($playlist).'/song/'.Music::encode($v['file']),
		);
	});

	return ListPage::render($playlist, null, null, $list);
});


//		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying
//
$klein->respond('GET', '/playlist/[:playlist]/song/[:song]', function($request, $response){
	// get the params
	$playlist = Music::decode($request->param('playlist'));
	$song = Music::decode($request->param('song'));

	// clear the playlist
	Music::send('clear');

	// get the list of songs
	$songs = Playlist::getSongs($playlist);

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




