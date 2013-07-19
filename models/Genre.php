<?php

class Genre extends Model {
	// the table
	public static $_table = 'genres';

	// the primary key
	public static $_id_column = 'id';


	/**
	 * Get the songs linked to this genre
	 * @return ORMWrapper
	 */
	public function songs()
	{
		return $this->has_many('Song');
	}
}