<?php

class Song {

	public static function getList()
	{
		// get the list
		$result = Music::send('listallinfo');

		// return the values
		return Music::buildSongList($result['values']);
	}
}