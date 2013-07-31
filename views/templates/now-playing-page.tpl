<!DOCTYPE html> 
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>{$page_title}</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.js"></script>

	<link rel="stylesheet" href="assets/css/msx-icons.css" />

<style>

div#volume-slider-div .ui-slider-input {
	display: none;
}

div#volume-slider-div .ui-slider-track {
	margin-left: 15px;
}

div#volume-slider-div .ui-slider {
	margin-left: 5%;
	margin-right: 5%;
	width: 90%;
}

</style>

</head> 

<body> 

<div data-role="page">

	<div data-role="header" data-position="fixed">
		{if="$back"}
		<a href="{$back}" data-role="button" data-inline="true">Back</a>
		{/if}

		<div>
			<p id='artist-name' style='text-align: center; color: #bbb; font-size:11px; margin: 1px 0 0;'>{$song.Artist}</p>
			<p id='title-name' style='text-align: center; font-size:11px; margin: 1px 0 0;'><strong>{$song.Title}</strong></p>
			<p id='album-name' style='text-align: center; color: #bbb; font-size:11px; margin: 1px 0 0;'>{$song.Album}</p>
		</div>
			
	</div><!-- /header -->

	<div data-role="content">

		<img src='{$image}' style='position:absolute; top:44px; left:0px;' />

		<div style='position:absolute; top:40px; left:0px; width:320px; height:320px; z-index:10;'>

			<div id='volume-slider-div' style='position:absolute; top: 270px; left: 0px; width: 320px;'>
				<input type="range" name="volume-slider" id="volume-slider" data-highlight="true" min="0" max="100" value="{$volume}">
			</div>

		</div>

	</div><!-- /content -->

	<div data-role="footer" data-id="list-footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#" data-role="button" id="prev" class='playback-control' data-icon="msx-prev" data-iconpos="notext" data-inline="true">Prev</a></li>
				<li><a href="#" data-role="button" id="play" class='playback-control' data-icon="msx-pause" data-iconpos="notext" data-inline="true">Play</a></li>
				<li><a href="#" data-role="button" id="next" class='playback-control' data-icon="msx-next" data-iconpos="notext" data-inline="true">Next</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->

</div><!-- /page -->

<script type='text/javascript'>

(function($){

// call the following as soon as the page is initialized
$(document).on('pageinit', function(){

	// bind events for the prev/play/next buttons
	$('a.playback-control').on('vclick', function(e){
		// stop event propagation
		e.stopPropagation();

		// handle the event
		clickControlButton(e.currentTarget.id);
	});

	// bind events for the volume slider
	$('input#volume-slider').on('slidestop', function(){
		console.log('vol = '+$('input#volume-slider').val());
		$.get('/action-volume/'+$('input#volume-slider').val() );
	});

	// start page refresh event
	refreshPage();
});

function clickControlButton(action)
{
	// figure out what action it is
	if(action=='prev')
	{
		// submit request for "previous" action
		$.get('/action-prev');
	}
	else if(action=='next')
	{
		// submit request for "next" action
		$.get('/action-next');
	}
	else if(action=="play")
	{
		// submit request for "play" action
		$.get('/action-toggle-play', { }, function(data){
			// @todo update the play button toggle to use custom icons
			if(data=='play')
				$('a#play span.ui-icon').removeClass('ui-icon-msx-play').addClass('ui-icon-msx-pause');
			else
				$('a#play span.ui-icon').removeClass('ui-icon-msx-pause').addClass('ui-icon-msx-play');
		});
	}
	else
	{
		// unrecognized action. Just bail out
		return;
	}
}

function refreshPage()
{
	// refresh the information on the page
	// submit an AJAX request for update info
	$.get('/now-playing-update', { }, function(data){
		// parse the result
		data = $.parseJSON(data);

		// insert info into the DOM
		$('#artist-name').text(data['artist']);
		$('#title-name').text(data['title']);
		$('#album-name').text(data['album']);

		// update the volume slider
		$('input#volume-slider').val(data['volume']);
		$('input#volume-slider').slider('refresh');

		// update the play state
		if(data['state']=='play')
			$('a#play span.ui-icon').removeClass('ui-icon-msx-play').addClass('ui-icon-msx-pause');
		else
			$('a#play span.ui-icon').removeClass('ui-icon-msx-pause').addClass('ui-icon-msx-play');

		// schedule another refresh
		setTimeout(refreshPage, 200);
	});
}



})(jQuery);

</script>

</body>
</html>
