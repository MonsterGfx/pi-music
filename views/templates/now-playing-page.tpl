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
	<script src="assets/js/msx.js"></script>

</head>

<body>

<div data-role="page" data-url='/'>

	<div data-role="header" data-position="fixed">
		{if="$previous"}
		<a href="{$previous.path}" data-role="button" data-inline="true">{$previous.text}</a>
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

</body>
</html>
