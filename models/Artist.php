<?php

class Artist extends BaseModel {
	// the table
	public static $_table = 'artists';

	// the primary key
	public static $_id_column = 'id';

	/**
	 * Get the list of albums by this artist
	 * 
	 * @return ORMWrapper
	 */
	public function albums()
	{
		return $this->has_many('Album');
	}

	public function songs()
	{
		return $this->has_many('Song');
	}

	public function toArray()
	{
		return array(
			'id'	=> $this->id,
			'name'	=> $this->name,
		);
	}
}