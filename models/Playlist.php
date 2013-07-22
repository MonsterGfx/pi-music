<?php

class Playlist extends BaseModel {
	// the table
	public static $_table = 'playlists';

	// the primary key
	public static $_id_column = 'id';

	/**
	 * Get the songs which belong to this playlist
	 * 
	 * @return ORMWrapper
	 */
	public function songs()
	{
		return $this->has_many_through('Song');
	}

	/**
	 * Add a song to the playlist
	 *
	 * @param type Song $song 
	 * The song to add
	 *
	 * @param type $sort_order 
	 * The position in the list in which to place it (default = the end)
	 */
	public function addSong(Song $song, $sort_order=999999)
	{
		// add the song to the playlist
		$playlist_song = Model::factory('PlaylistSong')->create();

		$playlist_song->playlists_id = $this->id;
		$playlist_song->songs_id = $song->id;
		$playlist_song->sort_order = $sort_order;
		$playlist_song->save();

		// now normalize the sort order
		$playlist_song = Model::factory('PlaylistSong')->where('playlists_id',$this->id)->find_many();

		for($i=0; $i<count($playlist_song); $i++)
		{
			$ps = $playlist_song[$i];
			$ps->sort_order = $i*10;
			$ps->save();
		}
	}

	public function toArray()
	{
		return array(
			'id'			=> $this->id,
			'name'			=> $this->name,
			'created_at'	=> $this->created_at,
			'updated_at'	=> $this->updated_at,
		);
	}
}