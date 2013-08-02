<?php

return array(

	'music-path' => '/home/local/STARKART/dthomas/Music/',

	'music-artwork-path'  => dirname(__FILE__).'/../storage/artwork/',

	'template-path' => dirname(__FILE__).'/../views/templates/',

	'template-cache-path' => dirname(__FILE__).'/../storage/cache/',

	'mpd-connection' => 'unix:///var/run/mpd/socket',

	'query-caching' => false,

	'query-cache-path' => dirname(__FILE__).'/../storage/cache/',
);