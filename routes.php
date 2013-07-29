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
 * @todo refactor query route handling to simplify it
 * @todo correct/clarify "back" route (button)
 * 
 * @todo ******************** bump version 0.0.15-alpha
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

// instantiate the router class
//
$klein = new \Klein\Klein;

// Handle the routing for queries
//
$klein->respond('GET',"@".QueryBuilder::regex(),function($request,$response){

	$args = explode('/', $request->uri());

	// get the values - the array filter function preserves array keys, even in
	// non-associative arrays; therefore, to make sure we don't have holes in
	// our numeric keys, I'm going to pull out the list of values
	$args = array_values(array_filter($args));

	// attempt to get the value from the cache
	$html = QueryCache::get($args);

	// was there anything in the cache?
	if($html!==false)
	{
		// yes! return the info from the cache rather than re-generating it
		return $html;
	}

	// set the base URI
	ListPage::setBaseUri(implode('/',$args));

	// is this a shuffle request?
	$shuffle = $args;
	$shuffle = array_pop($shuffle)=='shuffle';

	// get the results of this query
	$query = QueryBuilder::get($args);

	// is the final object is a song?
	if($shuffle || (is_object($query['items']) && get_class($query['items'])=='Song'))
	{
		// yes! we need to load the player with the current list of songs &
		// start playing
		Music::replacePlaylist($args, $shuffle);

		// // get the song info
		// $currentsong = MPD::send('currentsong');
		// $path = trim(substr($currentsong['values'][0],5));

		// // get it from the DB
		// $currentsong = Model::factory('Song')->where('filenamepath', $path)->find_one();

		// redirect to the "now playing" page
		header( 'Location: /' );
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		exit;
	}
	else if(is_array($query['items']))
	{
		// no, it's a list that we need to render
		$html = ListPage::render($query['page_title'], $query['previous'], $query['album_stats'], $query['items']);

		// otherwise, save the results in the cache
		QueryCache::save($args, $html);

		// and return the result
		return $html;
	}
	else
		throw new Exception("Oops! I don't know what went wrong!");
});

// the "now playing" page
//
$klein->respond('GET','/', function($request){

		// get the song info
		$currentsong = Music::getCurrentSong();

		// return the message
		return NowPlayingPage::render($currentsong, $request);
});

// the "skip to previous song" action
//
$klein->respond('GET','/action-prev', function(){ Music::previous(); });

// the "skip to next song" action
//
$klein->respond('GET','/action-next', function(){ Music::next(); });

// the "toggle play/pause" action
//
$klein->respond('GET','/action-toggle-play', function(){ return Music::togglePlay(); });

// the "adjust volume" action
//
$klein->respond('GET','/action-volume/[i:volume]', function($request){ Music::setVolume( $request->volume ); });

// the "now playing update" request
//
$klein->respond('GET','/now-playing-update', function(){ return json_encode(Music::updateNowPlaying()); });
















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

	// check to see if a song is currently playing
	Kint::dump(Music::getStatus());

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
