<?php

// Routes for song requests
//		song 	- list of all songs
//				page head='Songs', list head=null
//
//		song/1 	- load ALL songs, play song=1, go to nowplaying


//		song 	- list of all songs
//
$klein->respond('GET', '/song', function($request, $response){
	// get the list of songs
	$list = Song::getList();

	// walk the array and construct URLs
	// The encoded URL value is actually "artist name|album title". The artist
	// name is included to ensure that albums with the same name are not
	// conflated and the pipe character is a delimiter
	array_walk($list, function(&$v, $k){
		$v = array(
			'name' => $v['Title'],
			'url' => '/song/'.Music::encode($v['file']),
		);
	});

	$list = array_filter($list, function($v){ return $v['name'] ? true : false; });

	usort($list, function($a, $b){
		if(array_key_exists('name', $a) && array_key_exists('name', $b))
		{
			return $a['name']<$b['name'] ? -1 : 1;
		}
		return 0;
	});

	// build the shuffle link
	$shuffle = '/song/shuffle';

	return ListPage::render('Songs', null, $shuffle, $list);
});


//		song/1 	- load ALL songs, play song=1, go to nowplaying
//
$klein->respond('GET', '/song/[:song]', function($request, $response){
	// get parameter
	$song = Music::decode($request->param('song'));

	// clear the playlist
	Music::send('clear');

	// get the list of songs
	$songs = Song::getList();

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




