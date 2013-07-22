<?php

class Migration_003_add_artwork_filename_to_songs extends MigrationBase {

	/**
	 * Migrate the database up to this version
	 */
	public static function up()
	{
		// drop the existing table
		Database::execute("DROP TABLE IF EXISTS songs;");

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

	name TEXT,
	artists_id INTEGER,
	album_artist TEXT,
	albums_id INTEGER,
	genres_id INTEGER,
	track_number TEXT,
	disc_number TEXT,
	compilation TEXT,
	bpm TEXT,
	rating TEXT,

	artwork TEXT,

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
			"CREATE INDEX idx_songs_name ON songs ( name );",
			"CREATE INDEX idx_songs_artists_id ON songs ( artists_id );",
			"CREATE INDEX idx_songs_album_artist ON songs ( album_artist );",
			"CREATE INDEX idx_songs_albums_id ON songs ( albums_id );",
			"CREATE INDEX idx_songs_genres_id ON songs ( genres_id );",
		);
		foreach($indexes as $i)
			Database::execute($i);
	}

	/**
	 * Migrate the database down to the previous
	 */
	public static function down()
	{
		// drop the existing table
		Database::execute("DROP TABLE IF EXISTS songs;");

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

	name TEXT,
	artists_id INTEGER,
	album_artist TEXT,
	albums_id INTEGER,
	genres_id INTEGER,
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
			"CREATE INDEX idx_songs_name ON songs ( name );",
			"CREATE INDEX idx_songs_artists_id ON songs ( artists_id );",
			"CREATE INDEX idx_songs_album_artist ON songs ( album_artist );",
			"CREATE INDEX idx_songs_albums_id ON songs ( albums_id );",
			"CREATE INDEX idx_songs_genres_id ON songs ( genres_id );",
		);
		foreach($indexes as $i)
			Database::execute($i);
	}
}