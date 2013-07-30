<?php

class Artist {

	/**
	 * Get the list of artists
	 * 
	 * @return array
	 */
	public static function getList()
	{
		// get the list
		$result = Music::send('list', 'artist');

		// start building the list
		$list = array();

		// step through the results
		foreach($result['values'] as $v)
		{
			if(substr($v,0,7)=='Artist:')
				$list[] = trim(substr($v,7));
		}

		// sort the list
		sort($list);

		// and return it
		return $list;
	}

}