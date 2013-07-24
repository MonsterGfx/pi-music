<!DOCTYPE html> 
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>{$page_title}</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.js"></script>
</head> 

<body> 

<div data-role="page">

	<div data-role="header" data-position="fixed">
		{if="$back"}
		<a href="{$back}" data-role="button" data-inline="true">Back</a>
		{/if}

		<div>
			<p style='text-align: center; color: #bbb; font-size:11px; margin: 1px 0 0;'>{$artist}</p>
			<p style='text-align: center; font-size:11px; margin: 1px 0 0;'><strong>{$title}</strong></p>
			<p style='text-align: center; color: #bbb; font-size:11px; margin: 1px 0 0;'>{$album}</p>
		</div>
			
	</div><!-- /header -->

	<div data-role="content">

		<img src='{$image}' style='position:absolute; top:44px; left:0px; opacity:0.3;' />

{*
		<div style='position:absolute; top:40px; left:0px; width:320px; height:320px; z-index:10;'>
			<h1>Some Stuff</h1>
		</div>
*}

	</div><!-- /content -->

	<div data-role="footer" data-id="list-footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#" data-role="button" id="prev" class='playback-control' data-icon="arrow-l" data-iconpos="notext" data-inline="true">Prev</a></li>
				<li><a href="#" data-role="button" id="play" class='playback-control' data-icon="gear" data-iconpos="notext" data-inline="true">Play</a></li>
				<li><a href="#" data-role="button" id="next" class='playback-control' data-icon="arrow-r" data-iconpos="notext" data-inline="true">Next</a></li>
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

		// get the ID of the item that was clicked/touched
		var action = e.currentTarget.id;

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
		}
		else
		{
			// unrecognized action. Just bail out
			return;
		}

		// some action has been submitted. Wait a brief moment and refresh this
		// page
	});

});

function refreshPage()
{
	// refresh the information on the page

	// schedule another refresh
}



})(jQuery);

</script>

</body>
</html>
