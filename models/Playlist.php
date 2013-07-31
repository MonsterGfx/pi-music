<?php

class Playlist {

	/**
	 * Get the list of playlists
	 * 
	 * @return array
	 */
	public static function getList()
	{
		// send the command to MPD
		$results = Music::send('listplaylists');

		// start building a list
		$list = array();

		// step through the values returned by MPD
		foreach($results['values'] as $r)
		{
			// is it a playlist line?
			if(substr($r,0,9)=='playlist:')
			{
				// yes! extract the playlist name and add it to the list
				$list[] = trim(substr($r,9));
			}
		}

		// sort the list
		sort($list);

		// and return it
		return $list;
	}
}
	public static function getSongs($playlist)
	{
		// send the command to MPD
		$results = Music::send('listplaylistinfo', $playlist);

		// return the list of songs
		return Music::buildSongList($results['values']);
	}

}
