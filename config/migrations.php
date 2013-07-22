<?php

return array(

	/**
	 * The migrations available
	 *
	 * When new migrations are developed, they must be added to this array. The
	 * format is simply the migration number (each of which must succeed the
	 * previous value) and the name of the migration class (which must be
	 * defined and must extend MigrationBase).
	 */
	'migrations' => array(

		'1' => 'Migration_001_add_initial_scheme',

		'2' => 'Migration_002_add_playlist_tables',

		'3' => 'Migration_003_add_artwork_filename_to_songs',

	),

);