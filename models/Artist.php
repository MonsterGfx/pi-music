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

	/**
	 * Get the list of albums for an artist
	 * 
	 * @param string $artist 
	 * The artist name
	 * 
	 * @return array
	 */
	public static function getAlbums($artist)
	{
		// query the MPD database
		$result = Music::send('search', 'artist', $artist);

		// get the list of songs
		$songs = Music::buildSongList($result['values']);

		// extract the album information from the list
		$list = array();

		foreach($songs as $s)
		{
			if(isset($s['Album']))
			{
				$l = array(
					'artist' => $artist,
					'album' => $s['Album'],
				);
				if(!in_array($l, $list))
					$list[] = $l;
			}
		}

		return $list;
	}

	/**
	 * Get all the songs for an artist
	 * 
	 * @param string $artist 
	 * 
	 * @return array
	 */
	public static function getSongs($artist)
	{
		// query the MPD database
		$result = Music::send('search', 'artist', $artist);

		// get the list of songs
		return Music::buildSongList($result['values']);
	}
}