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

	/**
	 * Get the list of artists for the genre
	 * 
	 * @param string $genre 
	 * The genre
	 * 
	 * @return array
	 */
	public static function getArtists($genre)
	{
		// query the MPD database
		$result = Music::send('search', 'genre', $genre);

		// get the list of songs
		$songs = Music::buildSongList($result['values']);

		// extract the album information from the list
		$list = array();

		foreach($songs as $s)
		{
			if(isset($s['Artist']))
			{
				$l = array(
					'artist' => $s['Artist'],
				);
				if(!in_array($l, $list))
					$list[] = $l;
			}
		}

		return $list;
	}
}