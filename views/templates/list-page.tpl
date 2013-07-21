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
		{if="$previous"}
		<a href="{$previous.path}" data-role="button" data-inline="true">{$previous.text}</a>
		{/if}
		<h1>{$page_title}</h1>
	</div><!-- /header -->

	<div data-role="content">

		<ul data-role="listview">
{loop="$list"}
			<li><a href="/{$base_uri}/{$value.id}/{$object_type}">{$value.name}</a></li>
{/loop}
		</ul>

	</div><!-- /content -->

	<div data-role="footer" data-id="list-footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="#">Playlists</a></li>
				<li><a href="/artist">Artists</a></li>
				<li><a href="#">Songs</a></li>
				<li><a href="/album">Albums</a></li>
				<li><a href="#">More</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->

</div><!-- /page -->

</body>
</html>
