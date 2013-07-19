<?php

class Album extends Model {
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
		return $this->has_one('Artist');
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
}