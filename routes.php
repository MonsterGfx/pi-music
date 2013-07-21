<?php

/**
 * The router for the application
 * 
 * This looks like a good spot to write some TODOs
 * 
 * currently at version 0.0.1-alpha
 * * list page: fix toolbar at bottom
 * @todo list page: fix toolbar at top
 * @todo list page: add buttons to bottom toolbar
 * @todo list page: add "back" button in upper left
 * @todo list page: render album details when appropriate
 * 
 * @todo ******************** bump version 0.0.2-alpha
 * 
 * @todo implement playlists
 * 
 * @todo ******************** bump version 0.0.3-alpha
 * 
 * @todo install MPD
 * @todo implement MPD interface
 * 
 * @todo ******************** bump version 0.0.4-alpha
 * 
 * that will get us to a point where the player works!
 * 
 * @todo album artwork
 * 
 * @todo ******************** bump version 0.0.5-alpha
 * 
 * @todo playlist editor
 * 
 * @todo ******************** bump version 0.0.6-alpha
 * 
 * @todo desktop pc layout
 * 
 * @todo ******************** bump version 0.0.7-alpha
 * 
 * @todo testing
 * 
 * @todo ******************** bump version 0.1.0-beta
 * 
 */


// instantiate the router class
//
$klein = new \Klein\Klein;

// Handle the routing for queries
//
// a regular expression for parsing queries
$query_regex = "^(/([a-zA-Z]+)/([0-9]+)){0,5}(/([a-zA-Z]+)(/[0-9]+)?)[/]?$";
// set up the route
$klein->respond('GET',"@{$query_regex}",function($request,$response){

	$args = explode('/', $request->uri());

	$args = array_values(array_filter($args));

	// set the base URI
	ListPage::setBaseUri(implode('/',$args));


	// queries look like this:
	//
	//		playlist 			- list of playlists
	//				ph='Playlist', lh=null
	//
	//		playlist/1/song			- list of songs for playlist=1
	//				ph=playlist.name, lh=null
	//
	//		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying
	//
	//
	//		artist 					- list of artists
	//				ph='Artists', lh=null
	//
	//		artist/1/album			- list of albums for artist=1
	//				ph=artist.artist, lh=null
	//
	//		artist/1/album/2/song	- list of songs for artist=1, album=2
	//				ph=artist.artist, lh=album+stats
	//
	//		artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
	//
	//
	//		song 	- list of all songs
	//				ph='Songs', lh=null
	//
	//		song/1 	- load ALL songs, play song=1, go to nowplaying
	//
	//
	//		album 			- list all albums
	//				ph='Albums', lh=null
	//
	//		album/1/song	- list of songs for album=1
	//				ph=album.title, lh=album+stats
	//
	//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
	//
	//
	//		genre 		- list all genres
	//				ph='Genres', lh=null
	//
	//		genre/1/artist 	- list of artists for genre=1
	//				ph=genre.name, lh=null
	//
	//		genre/1/artist/2/album 	- list of albums for genre=1, artist=2
	//				ph=artist.artist, lh=null
	//
	//		genre/1/artist/2/album/3/song	- list of songs for genre=1, artist=2, album=3
	//				ph=artist.artist, lh=album+stats
	//
	//
	//		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying


	// instantiate the query object
	$obj = null;

	// the page title
	$page_title = '';

	// the album (if any)
	$album = null;

	// loop through the arguments
	while(count($args))
	{

		// get the next argument
		$a = array_shift($args);

		// check to see if the object has been created yet
		if(!$obj)
		{
			// instantiate a new object
			$obj = Model::factory(ucfirst(strtolower($a)));

			// set the page title
			$page_title = ucfirst(strtolower($a)).'s';
		}
		else
		{
			//otherwise, we're looking for a relationship
			// $a currently is something like "album"; the relationship looks
			// something like $obj->albums(). Fix up $a
			$a = $a.'s';
			$obj = $obj->$a();
		}

		// if we have more arguments, then the next one must be an ID value
		if(count($args)) {
			// get the ID
			$id = array_shift($args);

			// find the single object corresponding to that ID
			$obj = $obj->find_one($id);

			// if $obj is an Album, then collect the album stats
			if(get_class($obj)=='Album')
				$album_stats = $obj->getStats();

			// update the page title
			$page_title = $obj->name;
		}
		else
		{
			// no more arguments, so we want to find the "many" elements at this
			// point
			$obj = $obj->find_many();
		}

	}

	// check if the final object is a song
	if($obj && get_class($obj)=='Song')
	{
		return "It looks like you want '".$obj->name."'' to start playing.";
	}
	else if(is_array($obj))
	{
		// otherwise, render the list
		return ListPage::render($page_title, $album_stats, $obj);
	}
	else
		throw new Exception("Oops! I don't know what went wrong!");
});

$klein->respond('GET','/nuke-db', function(){
	Database::execute("drop table sys_migrations;");
	Database::execute("drop table artists;");
	Database::execute("drop table albums;");
	Database::execute("drop table genres;");
	Database::execute("drop table songs;");

	Kint::dump("database nuked");
});

$klein->respond('GET','/empty-db', function(){
	Database::execute("delete from artists;");
	Database::execute("delete from albums;");
	Database::execute("delete from genres;");
	Database::execute("delete from songs;");

	Kint::dump("database emptied");
});

$klein->respond('GET','/view-db', function($request,$response){

	Kint::dump(Database::query("SELECT * FROM songs;"));
	Kint::dump(Database::query("SELECT * FROM artists;"));
	Kint::dump(Database::query("SELECT * FROM albums;"));
	Kint::dump(Database::query("SELECT * FROM genres;"));
	die;

});

$klein->respond('GET','/test-route', function($request,$response){



	Kint::dump(Database::query("SELECT * FROM songs;"));
	Kint::dump(Database::query("SELECT * FROM artists;"));
	Kint::dump(Database::query("SELECT * FROM albums;"));
	Kint::dump(Database::query("SELECT * FROM genres;"));
	die;

	// attempt to use get_id3 to scan an MP3 file
	// $filename = '/home/local/STARKART/dthomas/Music/James Keelaghan - Princes of the Clouds.m4a';
	// $filename = '/home/local/STARKART/dthomas/Music/James Keelaghan - Cold Missouri Waters.mp3';

	$filename = '/media/music/Abba/The Albums/18 Dance (While The Music Still Goes.mp3';

	$getID3 = new getID3;

	// Analyze file and store returned data in $ThisFileInfo
	$file_info = $getID3->analyze($filename);

	return "<pre>".print_r($file_info,true)."</pre>";
	// Kint::dump($file_info['tags']);

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
