<?php

class Migration_002_add_playlist_tables extends MigrationBase {

	/**
	 * Migrate the database up to this version
	 */
	public static function up()
	{
		// create the playlists table
		$query = <<<QUERY

CREATE TABLE playlists (
	id INTEGER PRIMARY KEY ASC,
	name TEXT,

	created_at INTEGER,
	updated_at INTEGER
);

QUERY;
		Database::execute($query);
		Database::execute("CREATE INDEX idx_playlists_name ON playlists( name );");

		// create the playlists_songs table
		$query = <<<QUERY

CREATE TABLE playlists_songs (
	id INTEGER PRIMARY KEY ASC,
	playlists_id INTEGER,
	songs_id INTEGER,
	sort_order INTEGER
);

QUERY;
		Database::execute($query);
		Database::execute("CREATE INDEX idx_playlists_songs_playlists_id ON playlists_songs( playlists_id );");
		Database::execute("CREATE INDEX idx_playlists_songs_songs_id ON playlists_songs( songs_id );");
		Database::execute("CREATE INDEX idx_playlists_songs_sort_order ON playlists_songs( sort_order );");

	}

	/**
	 * Revert the database to the previous version by reversing the effects of
	 * the up method.
	 */
	public static function down()
	{
		// drop the table
		Database::execute("DROP TABLE playlists_songs;");
		Database::execute("DROP TABLE playlists;");
	}
}