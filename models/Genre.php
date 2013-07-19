<?php

class Genre extends Model {
	// the table
	public static $_table = 'genres';

	// the primary key
	public static $_id_column = 'id';

	/**
	 * Get the list of artists who have songs in this genre
	 * @return ORMWrapper
	 */
	public function artists()
	{
		// build a list of artists for this genre
		$songs = $this->songs()->find_many();

		// build an array of artist IDs
		$ids = array();

		// loop through the songs
		foreach($songs as $s)
		{
			// if the artist ID is not already in the list, add it
			if(!in_array($s->artists_id,$ids))
				$ids[] = $s->artists_id;
		}

		// return the artist list corresponding to the list of IDs
		return Model::factory('Artist')->where_in('id',$ids);
	}

	/**
	 * Get the songs linked to this genre
	 * @return ORMWrapper
	 */
	public function songs()
	{
		return $this->has_many('Song');
	}
}