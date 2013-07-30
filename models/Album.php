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
		$result = Music::send('listallinfo');

		// extract the values
		$result = Music::buildSongList($result['values']);

		// build an array of artists & albums
		$list = array();

		foreach($result as $s)
		{
			if(isset($s['Artist']) && isset($s['Album']))
			{
				$l = array(
					'artist' => $s['Artist'],
					'album' => $s['Album'],
				);

				if(!in_array($l,$list))
					$list[] = $l;
			}
		}

		// and return the result
		return $list;
	}

	/**
	 * Get the list of songs for the requested album
	 * 
	 * @param string $artist 
	 * The encoded name of the artist
	 * 
	 * @param string $album 
	 * The encoded name of the album
	 * 
	 * @return array
	 */
	public static function getSongs($artist, $album)
	{
		// query the MPD database
		$result = Music::send('search', 'artist', $artist, 'album', $album);

		// get the list of songs
		return Music::buildSongList($result['values']);
	}
}