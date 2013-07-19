<?php

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

	$args = array_filter($args);


	// queries look like this:
	//		playlist 			- list of playlists
	//				ph='Playlist', lh=null
	//		playlist/1 			- list of songs for playlist=1
	//				ph=playlist.name, lh=null
	//		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying
	//
	//		artist 				- list of artists
	//				ph='Artists', lh=null
	//		artist/1 			- list of albums for artist=1
	//				ph=artist.artist, lh=null
	//		artist/1/album/2	- list of songs for artist=1, album=2
	//				ph=artist.artist, lh=album+stats
	//		artist/1/album/2/song/3 	- load all songs for artist=1, album=2, play song=3, go to nowplaying
	//
	//		song 	- list of all songs
	//				ph='Songs', lh=null
	//		song/1 	- load ALL songs, play song=1, go to nowplaying
	//
	//		album 		- list all albums
	//				ph='Albums', lh=null
	//		album/1 	- list of songs for album=1
	//				ph=album.title, lh=album+stats
	//		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
	//
	//		genre 		- list all genres
	//				ph='Genres', lh=null
	//		genre/1 	- list of artists for genre=1
	//				ph=genre.name, lh=null
	//		genre/1/artist/2 	- list of albums for genre=1, artist=2
	//				ph=artist.artist, lh=null
	//		genre/1/artist/2/album/3	- list of songs for genre=1, artist=2, album=3
	//				ph=artist.artist, lh=album+stats
	//		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying













	// imagine a query like this:
	// artist/1/album/3

	$artist = Database::voodORM()->table('artists')->where('id',1)->findOne();
	$album = Database::voodORM()->table('albums')->where('id',1)->_and()->where('artist_id',$artist->id)->findOne();
	$songs = Database::voodORM()->table('songs')->where('artist_id',$artist->id)->_and()->where('album_id',$album->id)->find();
	// Kint::dump($album);
	// die;


	return ListPage::render($album, $songs);

	// @todo do something with those arguments
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
