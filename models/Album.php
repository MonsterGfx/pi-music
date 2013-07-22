<?php

class Album extends BaseModel {
	// the table
	public static $_table = 'albums';

	// the primary key
	public static $_id_column = 'id';

	/**
	 * Get the artist linked to this album
	 * 
	 * @return ORMWrapper
	 */
	public function artist()
	{
		return $this->belongs_to('Artist');
	}

	/**
	 * Get the songs linked to this album
	 * 
	 * @return ORMWrapper
	 */
	public function songs()
	{
		return $this->has_many('Song');
	}

	/**
	 * Get the statistics for display at the head of a list
	 * 
	 * @return array
	 */
	public function getStats()
	{
		// get the list of songs
		$songs = $this->songs()->find_many();

		// calculate the total time
		$time = 0;
		foreach($songs as $s)
			$time += $s->playtime_seconds;
		$time = round($time/60);

		// load the image data
		$img = file_get_contents(Config::get('app.music-artwork-path').$songs[0]->artwork.'-180.jpg');
		if($img)
		{
			$img = base64_encode($img);
			$img = "data:image/png;base64,{$img}";
		}

		// return the results
		return array(
			'artist' => $this->artist()->find_one()->name,
			'artwork' => $img,
			'name' => $this->name,
			'year' => $this->year ?: null,
			'song_count' => count($songs) ?: null,
			'total_time' => $time ?: null,
		);

	}

	public function toArray()
	{
		return array(
			'id'			=> $this->id,
			'name'			=> $this->name,
			'artists_id'	=> $this->artists_id,
			'year'			=> $this->year,
		);
	}
}