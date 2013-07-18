<?php

class Migration_add_songs_table extends MigrationBase {

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

		Database::execute($query);
	}

	public static function down()
	{
		// drop the table
		Database::execute("DROP TABLE songs;");
	}
}