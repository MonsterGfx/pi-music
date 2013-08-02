<?php

/**
 * The router for the application
 *
 * This looks like a good spot to write some TODOs
 *
 * * list page: fix toolbar at bottom
 * * list page: fix toolbar at top
 * * list page: add "back" button in upper left
 * * list page: add buttons to bottom toolbar
 * * list page: render album details when appropriate
 *
 * * ******************** bump version 0.0.2-alpha
 *
 * * implement playlists
 *
 * * ******************** bump version 0.0.3-alpha
 *
 * * scan music: remove missing files
 *
 * * ******************** bump version 0.0.4-alpha
 *
 * * album artwork
 *
 * * ******************** bump version 0.0.5-alpha
 *
 * * query caching system
 *
 * * ******************** bump version 0.0.6-alpha
 *
 * * install MPD
 * * implement MPD interface
 *
 * * ******************** bump version 0.0.7-alpha
 *
 * * bugfix: correct song order in query
 *
 * * ******************** bump version 0.0.8-alpha
 *
 * * implement "now playing" page
 * * redirect from query page to now playing page
 * * add "now playing" button to list page
 * * add "back" button to now playing page
 * * add control buttons to now playing page
 * * add volume control to now playing page
 * * toggle play/pause icons on now playing page
 *
 * * ******************** bump version 0.0.9-alpha
 *
 * * bugfix: songs not added in expected order
 *
 * * ******************** bump version 0.0.10-alpha
 *
 * * add "shuffle" buttons to song lists
 *
 * * ******************** bump version 0.0.11-alpha
 *
 * * add custom play/pause icons to play button
 *
 * * ******************** bump version 0.0.12-alpha
 *
 * * add update functionality to now-playing page
 *
 * * ******************** bump version 0.0.13-alpha
 *
 * * make "now playing" the default route
 *
 * * ******************** bump version 0.0.14-alpha
 *
 * ------------------------- this will get us to a point where the player works!
 *
 * * refactor query route handling to simplify it
 * * correct/clarify "back" route (button)
 * * rewrite to use MPD song database
 *
 * * ******************** bump version 0.0.15-alpha
 *
 * @todo playlist editor
 *
 * @todo ******************** bump version 0.0.16-alpha
 *
 * @todo testing
 *
 * @todo ******************** bump version 0.1.0-beta
 *
 * ------------------------- future enhancements
 *
 * @todo add scrubbing control to now playing page
 * @todo desktop pc layout
 *
 */

use \PHPMPDClient\MPD as MPD;


// @todo remove this line
// clear the template cache
array_map( "unlink", glob( Config::get('app.template-cache-path') . "*.rtpl.php" ) );


// instantiate the router class
//
$klein = new \Klein\Klein;

// load the artist routes
require_once 'routes/artist.php';

// load the album routes
require_once 'routes/album.php';

// load the genre routes
require_once 'routes/genre.php';

// load the song routes
require_once 'routes/song.php';

// load the playlist routes
require_once 'routes/playlist.php';

// load the "now playing" routes
require_once 'routes/now-playing.php';

















$klein->respond('GET','/show-tables', function(){
	Kint::dump(Database::query("SELECT name FROM sqlite_master WHERE type='table';"));
});

$klein->respond('GET','/nuke-db', function(){
	$tables = Database::query("SELECT name FROM sqlite_master WHERE type='table';");
	Kint::dump($tables);
	foreach($tables as $t)
	{
		Kint::dump("dropping {$t['name']}");
		Database::execute("drop table if exists {$t['name']};");
	}
	Kint::dump("database nuked");
});

$klein->respond('GET','/empty-db', function(){
	Database::execute("delete from artists;");
	Database::execute("delete from albums;");
	Database::execute("delete from genres;");
	Database::execute("delete from songs;");
	Database::execute("delete from playlists;");
	Database::execute("delete from playlists_songs;");

	Kint::dump("database emptied");
});

$klein->respond('GET','/view-db', function($request,$response){
	$tables = Database::query("SELECT name FROM sqlite_master WHERE type='table';");
	foreach($tables as $t)
	{
		Kint::dump(Database::query("SELECT * FROM {$t['name']};"));
	}
});


















$klein->respond('GET','/test-route', function($request,$response){

	// queries look like this:
	//
	//		playlist 			- list of playlists
	//				page head='Playlist', list head=null
	//
	//		playlist/1/song			- list of songs for playlist=1
	//				page head=playlist.name, list head=null
	//
	//		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying
	//
	//
	//		artist 					- list of artists
	//				page head='Artists', list head=null
	//
	//		artist/1/album			- list of albums for artist=1
	//				page head=artist.artist, list head=null
	//
	//		artist/1/album/2/song	- list of songs for artist=1, album=2
	//				page head=artist.artist, list head=album+stats
	//
	//		artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
	//
	// 		artist/1/song			- list of all songs for artist=1
	//				page head=artist.artist, list head=null
	//
	//
	//		song 	- list of all songs
	//				page head='Songs', list head=null
	//
	//		song/1 	- load ALL songs, play song=1, go to nowplaying
	//
	//
	//		album 			- list all albums
	//				page head='Albums', list head=null
	//
	//		album/1/song	- list of songs for album=1
	//				page head=album.title, list head=album+stats
	//
	//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
	//
	//
	//		genre 		- list all genres
	//				page head='Genres', list head=null
	//
	//		genre/1/artist 	- list of artists for genre=1
	//				page head=genre.name, list head=null
	//
	//		genre/1/artist/2/album 	- list of albums for genre=1, artist=2
	//				page head=artist.artist, list head=null
	//
	//		genre/1/artist/2/album/3/song	- list of songs for genre=1, artist=2, album=3
	//				page head=artist.artist, list head=album+stats
	//
	//
	//		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying

	Kint::dump(Music::send('search', 'album', 'End of the Summer', 'artist', 'Dar Williams'));

	Kint::dump(Album::getSongs('Dar Williams', 'End of the Summer'));
});





// Handle a 404 - route not found
//
$klein->respond('404', function($request){
	$r = "<h1>Uh-oh. 404!</h1>";
	$r .= "<p>The path '".$request->uri()."' does not exist.</p>";
	return $r;
});

// Execute!
//
$klein->dispatch();
