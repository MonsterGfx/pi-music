<?php

class Song extends BaseModel {
	// the table
	public static $_table = 'songs';

	// the primary key
	public static $_id_column = 'id';

	/**
	 * Get the album for this song
	 * 
	 * @return ORMWrapper
	 */
	public function album()
	{
		return $this->has_one('Album');
	}

	public function toArray()
	{
		return array(
			'id'				=> $this->id,
			'filenamepath'		=> $this->filenamepath,
			'filesize'			=> $this->filesize,
			'fileformat'		=> $this->fileformat,
			'dataformat'		=> $this->dataformat,
			'codec'				=> $this->codec,
			'sample_rate'		=> $this->sample_rate,
			'channels'			=> $this->channels,
			'bits_per_sample'	=> $this->bits_per_sample,
			'lossless'			=> $this->lossless,
			'channelmode'		=> $this->channelmode,
			'bitrate'			=> $this->bitrate,
			'playtime_seconds'	=> $this->playtime_seconds,
			'name'				=> $this->name,
			'artists_id'		=> $this->artists_id,
			'album_artist'		=> $this->album_artist,
			'albums_id'			=> $this->albums_id,
			'genres_id'			=> $this->genres_id,
			'track_number'		=> $this->track_number,
			'disc_number'		=> $this->disc_number,
			'compilation'		=> $this->compilation,
			'bpm'				=> $this->bpm,
			'rating'			=> $this->rating,
			'created_at'		=> $this->created_at,
			'updated_at'		=> $this->updated_at,
		);
	}
}