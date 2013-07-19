<?php

class Artist extends Model {
	// the table
	public static $_table = 'artists';

	// the primary key
	// public static $id_column = 'id';

	public function albums()
	{
		return $this->has_many('Album');
	}
}