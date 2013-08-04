<?php

return array(

	/**
	 * The path to the music files 
	 */
	'music-path' => '/media/music/',

	/**
	 * The path to the folder where artwork will be saved
	 */
	'music-artwork-path'  => dirname(__FILE__).'/../storage/artwork/',

	/**
	 * The path to templates
	 */
	'template-path' => dirname(__FILE__).'/../views/templates/',

	/**
	 * The path to the template cache
	 */
	'template-cache-path' => dirname(__FILE__).'/../storage/cache/',

	/**
	 * The MPD connection string
	 */
	'mpd-connection' => 'unix:///var/run/mpd/socket',

	/**
	 * Should the system cache queries?
	 * 
	 * This is set to false while developing. It should be set to true in a
	 * production system.
	 */
	'query-caching' => false,

	/**
	 * The query cache storage location
	 */
	'query-cache-path' => dirname(__FILE__).'/../storage/cache/',
);