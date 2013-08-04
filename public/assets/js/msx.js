/**
 * Javascript code for the application
 */

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

	// start page refresh event if we're on the "now playing" page
	if($('a.playback-control').length)
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
			// update the play button toggle (with my custom icons)
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

		// update the artwork
		$('#artwork').attr('src', data['artwork']);

		// update the volume slider
		$('input#volume-slider').val(data['volume']);
		$('input#volume-slider').slider('refresh');

		// update the play state
		if(data['state']=='play')
			$('a#play span.ui-icon').removeClass('ui-icon-msx-play').addClass('ui-icon-msx-pause');
		else
			$('a#play span.ui-icon').removeClass('ui-icon-msx-pause').addClass('ui-icon-msx-play');

		// schedule another refresh if we're on the "now playing" page
		if($('a.playback-control').length)
			setTimeout(refreshPage, 200);
	});
}



})(jQuery);
