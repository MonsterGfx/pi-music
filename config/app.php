<?php

return array(

	// 'music-path' => array('/home/local/STARKART/dthomas/Music/',),
	'music-path' => array(
			'/media/music/Kate Bush/',
			'/media/music/Billy Bragg/',
			'/media/music/Dar Williams/',
		),

	'music-artwork-path'  => dirname(__FILE__).'/../storage/artwork/',

	'template-path' => dirname(__FILE__).'/../views/templates/',

	'template-cache-path' => dirname(__FILE__).'/../storage/cache/',

	'mpd-connection' => 'unix:///var/run/mpd/socket',

	'query-caching' => false,

	'query-cache-path' => dirname(__FILE__).'/../storage/cache/',
);