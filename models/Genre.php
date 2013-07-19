<?php

class Genre extends Model {
	// the table
	public static $_table = 'genres';

	// the primary key
	// public static $id_column = 'id';

	public function songs()
	{
		return $this->has_many('Song');
	}
}