<?php

class PlaylistSong extends BaseModel {
	// the table
	public static $_table = 'playlists_songs';

	// the primary key
	public static $_id_column = 'id';

	public function toArray()
	{
		return array(
			'id'			=> $this->id,
			'playlists_id'	=> $this->playlists_id,
			'songs_id'		=> $this->songs_id,
			'sort_order'	=> $this->sort_order,
		);
	}
}