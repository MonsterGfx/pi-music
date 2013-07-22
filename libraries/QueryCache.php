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
		$filename = Config::get('app.query-cache-path').implode('-',$args);

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
		$filename = Config::get('app.query-cache-path').implode('-',$args);

		// write the value
		file_put_contents($filename, serialize($value));
	}
}
