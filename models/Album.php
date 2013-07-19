<?php

class Album extends Model {
	// the table
	public static $_table = 'albums';

	// the primary key
	public static $_id_column = 'id';

	public function artist()
	{
		return $this->has_one('Artist');
	}

	public function songs()
	{
		return $this->has_many('Song');
	}
}