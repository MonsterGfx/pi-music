<?php

class Config {

	/**
	 * Get a configuration value
	 *
	 * The configuration key is a string of the form "file.key" where the "file"
	 * part is the name (minus the .php extension) of a file in the "config"
	 * folder.
	 *
	 * @param type $key
	 * @return type
	 */
	public static function get($key)
	{
		// validate the key
		if(!$key || !is_string($key))
			throw new Exception("Invalid configuration key: $key");

		// parse the key
		$parts = explode('.',$key);
		$file = array_shift($parts);
		$key = implode(',',$parts);

		// load the appropriate config file
		$values = include dirname(__FILE__)."/../config/{$file}.php";

		// check to see if the key exists
		$value = false;
		if(array_key_exists($key,$values))
			$value = $values[$key];

		// return the result
		return $value;
	}
}
