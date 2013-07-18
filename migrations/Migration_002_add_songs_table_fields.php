<?php

class Migration_002_add_songs_table_fields extends MigrationBase {

	/**
	 * Migrate the database up to this version
	 */
	public static function up()
	{
		// drop the old table
		Database::execute("DROP TABLE songs;");

		// update the table
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

	title TEXT,
	artist TEXT,
	album_artist TEXT,
	album TEXT,
	genre TEXT,
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
			"CREATE INDEX unq_filenamepath ON songs ( filenamepath );",

			// indices on important values
			"CREATE INDEX idx_title ON songs ( title );",
			"CREATE INDEX idx_artist ON songs ( artist );",
			"CREATE INDEX idx_album_artist ON songs ( album_artist );",
			"CREATE INDEX idx_album ON songs ( album );",
			"CREATE INDEX idx_genre ON songs ( genre );",
		);
	}

	/**
	 * Revert the database to the previous version by reversing the effects of
	 * the up method.
	 */
	public static function down()
	{
		// drop the table
		Database::execute("DROP TABLE songs;");

		// recreate the table
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

	title TEXT,
	artist TEXT,
	album_artist TEXT,
	album TEXT,
	genre TEXT,
	track_number TEXT,
	disc_number TEXT,
	compilation TEXT,
	bpm TEXT,
	rating TEXT

);

QUERY;

		// execute the query
		Database::execute($query);
	}
}