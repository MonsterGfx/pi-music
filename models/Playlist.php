<?php

class Playlist extends BaseModel {
	// the table
	public static $_table = 'playlists';

	// the primary key
	public static $_id_column = 'id';

	/**
	 * Get the songs which belong to this playlist
	 * 
	 * @return ORMWrapper
	 */
	public function songs()
	{
		return $this->has_many_through('Song');
	}

	public function toArray()
	{
		return array(
			'id'			=> $this->id,
			'name'			=> $this->name,
			'created_at'	=> $this->created_at,
			'updated_at'	=> $this->updated_at,
		);
	}
}