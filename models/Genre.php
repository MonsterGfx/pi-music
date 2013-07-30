<?php

class Genre {

	/**
	 * Get the list of genres
	 * 
	 * @return array
	 */
	public static function getList()
	{
		// get the list
		$result = Music::send('list', 'genre');

		// start building the list
		$list = array();

		// step through the results
		foreach($result['values'] as $v)
		{
			if(substr($v,0,6)=='Genre:')
				$list[] = trim(substr($v,6));
		}

		// sort the list
		sort($list);

		// and return it
		return $list;
	}
}