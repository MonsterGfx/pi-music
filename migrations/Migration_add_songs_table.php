<?php

class Migration_add_songs_table extends MigrationBase {

	/**
	 * Migrate the database up to this version
	 */
	public static function up()
	{
		// create the table
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