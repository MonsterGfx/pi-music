<?php

class Song extends Model {
	// the table
	public static $_table = 'songs';

	// the primary key
	// public static $id_column = 'id';

	/**
	 * Get the album for this song
	 * 
	 * @return ORMWrapper
	 */
	public function album()
	{
		return $this->has_one('Album');
	}
}