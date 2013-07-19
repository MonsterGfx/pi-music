<?php

class Artist extends Model {
	// the table
	public static $_table = 'artists';

	// the primary key
	// public static $id_column = 'id';

	/**
	 * Get the list of albums by this artist
	 * 
	 * @return ORMWrapper
	 */
	public function albums()
	{
		return $this->has_many('Album');
	}
}