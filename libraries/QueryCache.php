<?php

class QueryCache {

	/**
	 * Get a value from the query cache (or false if it doesn't exist)
	 *
	 * @param array $query
	 * The query to retrieve
	 * 
	 * @return mixed|bool
	 * The values from the cache, or false if the file does not exist
	 */
	public static function get($args)
	{
		// return false if we don't have valid arguments
		if(!$args || !is_array($args))
			return false;

		// attempt to load the query file
		$filename = Config::get('app.query-cache-path').implode('-',$args).'.qcache';

		if(file_exists($filename))
			return unserialize(file_get_contents($filename));
	}

	/**
	 * Save the results of a query to the cache
	 * 
	 * @param array $args 
	 * The query arguments
	 * 
	 * @param mixed $value
	 * The value to save
	 */
	public static function save($args, $value)
	{
		// create the filename
		$filename = Config::get('app.query-cache-path').implode('-',$args).'.qcache';

		// write the value
		file_put_contents($filename, serialize($value));
	}

	public static function cleanup($dirty)
	{
		// replace the '/'' character in $d with a '-'
		$dirty = str_replace('/','-',$dirty);

		// get the cache folder
		$folder = Config::get('app.query-cache-path');

		// build a list of files
		$files = scandir($folder);

		// step through the items in the cache folder
		foreach($files as $f)
		{
			// ignore any that do not end in '.qcache'
			if(!preg_match('/^.*\.qcache$/',$f))
				continue;

			// check each of the items in the dirty list
			foreach($dirty as $d)
			{
				if(strstr($f, $d))
				{
					if(file_exists($folder.$f))
						unlink($folder.$f);
				}
			}
		}

		// if there are any items in the dirty array, that means that there is
		// at least one song that has changed. We need to remove the cached
		// 'song', 'artist', 'album', and 'genre' queries if that's the case
		if(count($dirty))
		{
			if(file_exists($folder.'song.qcache'))
				unlink($folder.'song.qcache');
			if(file_exists($folder.'artist.qcache'))
				unlink($folder.'artist.qcache');
			if(file_exists($folder.'album.qcache'))
				unlink($folder.'album.qcache');
			if(file_exists($folder.'genre.qcache'))
				unlink($folder.'genre.qcache');
		}
	}
}
