<?php

class Album {

	/**
	 * Get the list of all albums in the database
	 * 
	 * @return array
	 */
	public static function getList()
	{
		// get the list
		$result = Music::send('list', 'album');

		// extract the values
		$result = $result['values'];

		// now parse
		array_walk($result, function(&$val, $key){
			$a = explode(':',$val);
			array_shift($a);
			$val = trim(implode(':',$a));
		});

		// remove any empty values
		$result = array_filter(array_values($result));

		// sort it
		sort($result, SORT_NATURAL|SORT_FLAG_CASE);

		// and return the result
		return $result;
	}
}