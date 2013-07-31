<?php

// The "Now Playing" routes

// the "now playing" page
//
$klein->respond('GET','/', function($request){
		// return the message
		return NowPlayingPage::render(Music::getCurrentSong(), $request);
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



