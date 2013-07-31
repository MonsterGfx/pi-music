<?php

// Routes for song requests
//		song 	- list of all songs
//				page head='Songs', list head=null
//
//		song/1 	- load ALL songs, play song=1, go to nowplaying


//		song 	- list of all songs
//
$klein->respond('GET', '/song', function($request, $response){
	// get the list of albums
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

	return ListPage::render('Songs', null, null, $list);
});


//		song/1 	- load ALL songs, play song=1, go to nowplaying
//
$klein->respond('GET', '/song/[:song]', function($request, $response){
	return "start playing song ".Music::decode($request->param('song'));

});




