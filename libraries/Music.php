<?php

use \PHPMPDClient\MPD as MPD;

class Music {

	/**
	 * A flag to indicate whether we're connected to MPD
	 */
	private static $is_connected = false;

	private static function connect()
	{
		if(!static::$is_connected)
		{
			// connect to MPD
			MPD::connect('', Config::get('app.mpd-connection'), null);

			static::$is_connected = true;
		}
	}

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
		static::connect();

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
		static::connect();

		// get the song info
		$currentsong = MPD::send('currentsong');
		$path = trim(substr($currentsong['values'][0],5));

		// get it from the DB
		$currentsong = Model::factory('Song')->where('filenamepath', $path)->find_one();

		return $currentsong;
	}

	/**
	 * Get the status of MPD
	 * 
	 * @return array
	 * The array of status values
	 */
	public static function getStatus()
	{
		// connect to MPD
		static::connect();

		// get the status
		$status = MPD::status();

		// now parse the values so they're a little more usable
		if(isset($status['values']))
		{
			// the status values are strings in the format "key: value". I'm 
			// going to parse this into an associative array
			$newvalues = array();

			foreach($status['values'] as $v)
			{
				$new = explode(':', $v);
				$newvalues[trim($new[0])] = trim($new[1]);
			}

			$status['values'] = $newvalues;
		}

		// and return the results
		return $status;
	}

	/**
	 * Check to see if MPD is currently playing a song
	 * 
	 * @return bool
	 * True if a song is playing, false otherwise
	 */
	public static function isPlaying()
	{
		// get the status
		$status = static::getStatus();

		// do we have some status values?
		if( isset($status['values']) && isset($status['values']['state']) && $status['values']['state']=='play')
			return true;

		// the status does not include "state: play"
		return false;
	}

	/**
	 * Check to see if MPD is currently paused
	 * 
	 * @return bool
	 * True if a song is paused, false otherwise
	 */
	public static function isPaused()
	{
		// get the status
		$status = static::getStatus();

		// do we have some status values?
		if( isset($status['values']) && isset($status['values']['state']) && $status['values']['state']=='pause')
			return true;

		// the status does not include "state: play"
		return false;
	}

	/**
	 * Check to see if MPD is currently playing or paused
	 * 
	 * @return bool
	 * True if a song is playing or paused, false otherwise
	 */
	public static function isPlayingOrPaused()
	{
		// get the status
		$status = static::getStatus();

		// do we have some status values?
		if( isset($status['values']) && isset($status['values']['state']) && ($status['values']['state']=='play' || $status['values']['state']=='pause'))
			return true;

		// the status does not include "state: play"
		return false;
	}

	/**
	 * Jump to the previous song
	 */
	public static function previous()
	{
		// connect to MPD
		static::connect();

		MPD::send('previous');
	}

	/**
	 * Jump to the next song
	 */
	public static function next()
	{
		// connect to MPD
		static::connect();

		MPD::send('next');
	}

	/**
	 * Toggle between play/pause states
	 */
	public static function togglePlay()
	{
		static::connect();

		MPD::send('pause', static::isPlaying() ? 1 : 0);

		return static::isPlaying() ? 'play' : 'pause';
	}

	/**
	 * Get the current volume
	 * 
	 * @return int
	 */
	public static function getVolume()
	{
		// get the player status
		$status = static::getStatus();

		if(isset($status['values']) && isset($status['values']['volume']))
			return $status['values']['volume'];

		return false;
	}
}
