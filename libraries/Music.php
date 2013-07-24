<?php

use \PHPMPDClient\MPD as MPD;

class Music {

	private static function getSongList($args)
	{
		// discard the last item in the args, since that's the actual song ID
		array_pop($args);

		// loop through the arguments
		while(count($args))
		{

			// get the next argument
			$a = array_shift($args);

			// check to see if the object has been created yet
			if(!$obj)
			{
				// instantiate a new object
				$obj = Model::factory(ucfirst(strtolower($a)));
			}
			else
			{
				//otherwise, we're looking for a relationship
				// $a currently is something like "album"; the relationship looks
				// something like $obj->albums(). Fix up $a
				$func = $a.'s';
				$obj = $obj->$func();
			}

			// if we have more arguments, then the next one must be an ID value
			if(count($args)) {
				// get the ID
				$id = array_shift($args);

				// find the single object corresponding to that ID
				$obj = $obj->find_one($id);
			}
			else
			{
				// no more arguments, so we want to find the "many" elements at this
				// point
				$obj = $obj->find_many();
			}

		}

		// and return the results
		return $obj;
	}

	public static function replacePlaylist($args)
	{
		// connect to MPD
		MPD::connect('', Config::get('app.mpd-connection'), null);

		// get the songs as defined by the arguments
		$songs = Music::getSongList($args);

		// get the ID of the song to play
		$song_id = array_pop($args);

		// clear the current playlist
		MPD::clear();

		// a variable to save the ID of the song to play
		$mpd_id = null;

		// step through the songs
		foreach($songs as $s)
		{
			// add each song to the playlist
			$values = MPD::send('addid', 'file://'.$s->filenamepath);

			// does this song correspond to the the song to play?
			if($values['status']=='OK' && $s->id==$song_id)
				$mpd_id = trim(substr($values['values'][0],3));

		}
		// start playing the selected song
		if($mpd_id)
			MPD::send('playid', $mpd_id);
	}

	public static function getCurrentSong()
	{
		// connect to MPD
		MPD::connect('', Config::get('app.mpd-connection'), null);

		// get the song info
		$currentsong = MPD::send('currentsong');
		$path = trim(substr($currentsong['values'][0],5));

		// get it from the DB
		$currentsong = Model::factory('Song')->where('filenamepath', $path)->find_one();

		return $currentsong;
	}

}
