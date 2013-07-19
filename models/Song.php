<?php

class Song extends Model {
	// the table
	public static $_table = 'songs';

	// the primary key
	// public static $id_column = 'id';

	public function album()
	{
		return $this->has_one('Album');
	}
}