<?php

class Migration_001_add_initial_scheme extends MigrationBase {

	/**
	 * Migrate the database up to this version
	 */
	public static function up()
	{
		// create the artists table
		$query = <<<QUERY

CREATE TABLE artists (
	id INTEGER PRIMARY KEY ASC,
	name TEXT
);

QUERY;
		Database::execute($query);
		Database::execute("CREATE INDEX idx_artists_name ON artists( name );");

		// create the albums table
		$query = <<<QUERY

CREATE TABLE albums (
	id INTEGER PRIMARY KEY ASC,
	artist_id INTEGER,
	name TEXT,
	year INTEGER
);

QUERY;
		Database::execute($query);
		Database::execute("CREATE INDEX idx_albums_artist_id ON albums( artist_id );");
		Database::execute("CREATE INDEX idx_albums_name ON albums( name );");

		// create the genres table
		$query = <<<QUERY

CREATE TABLE genres (
	id INTEGER PRIMARY KEY ASC,
	name TEXT
);

QUERY;
		Database::execute($query);
		Database::execute("CREATE INDEX idx_genres_name ON genres( name );");

		// create the songs table
		$query = <<<QUERY

CREATE TABLE songs (
	id INTEGER PRIMARY KEY ASC,

	filenamepath TEXT,
	filesize INTEGER,
	fileformat TEXT,

	dataformat TEXT,
	codec TEXT,
	sample_rate REAL,
	channels INTEGER,
	bits_per_sample INTEGER,
	lossless TEXT,
	channelmode TEXT,
	bitrate REAL,
	playtime_seconds REAL,

	title TEXT,
	artist_id INTEGER,
	album_artist TEXT,
	album_id INTEGER,
	genre_id INTEGER,
	track_number TEXT,
	disc_number TEXT,
	compilation TEXT,
	bpm TEXT,
	rating TEXT,

	created_at INTEGER,
	updated_at INTEGER
);

QUERY;

		// execute the query
		Database::execute($query);

		// create some indices
		$indexes = array(
			// a unique index on the filenamepath column
			"CREATE UNIQUE INDEX idx_songs_filenamepath ON songs ( filenamepath );",

			// indices on important values
			"CREATE INDEX idx_songs_title ON songs ( title );",
			"CREATE INDEX idx_songs_artist_id ON songs ( artist_id );",
			"CREATE INDEX idx_songs_album_artist ON songs ( album_artist );",
			"CREATE INDEX idx_songs_album_id ON songs ( album_id );",
			"CREATE INDEX idx_songs_genre_id ON songs ( genre_id );",
		);
		foreach($indexes as $i)
			Database::execute($i);	}

	/**
	 * Revert the database to the previous version by reversing the effects of
	 * the up method.
	 */
	public static function down()
	{
		// drop the table
		Database::execute("DROP TABLE songs;");
	}
}