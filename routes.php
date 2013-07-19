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

	// imagine a query like this:
	// artist/1/album/3

	$artist = Database::voodORM()->table('artists')->where('id',1)->findOne();
	$album = Database::voodORM()->table('albums')->where('id',2)->_and()->where('artist_id',$artist->id)->findOne();
	$songs = Database::voodORM()->table('songs')->where('artist_id',$artist->id)->_and()->where('album_id',$album->id)->find();
	// Kint::dump($album);
	// die;


	return ListView::render($album, $songs);

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
