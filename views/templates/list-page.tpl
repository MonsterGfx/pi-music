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
	<link rel="stylesheet" href="assets/css/msx.css" />
	<script src="assets/js/msx.js"></script>

</head>

<body>

<div data-role="page">

	<div data-role="header" data-position="fixed">
{if="$previous"}
		<a href="{$previous.path}" data-role="button" data-inline="true" class='ui-btn-left'>{$previous.text}</a>
{/if}
		<h1>{$page_title}</h1>
{if="$now_playing"}
		<a href="/" data-role="button" data-inline="true" class='ui-btn-right'>Now Playing</a>
{/if}
	</div><!-- /header -->

	<div data-role="content">

		<ul data-role="listview">
{* add the album stats (if any) *}
	{if="$album_stats"}
			<li>
		{if="$album_stats.artwork"}<img src='{$album_stats.artwork}' />{/if}
				<p>{$album_stats.artist}</p>
				<h1>{$album_stats.name}</h1>
				<p>{if="$album_stats.year"}Released {$album_stats.year}{/if}</p>
		{if="$album_stats.song_count && $album_stats.total_time"}
				<p>
					{if="$album_stats.song_count>0"}{$album_stats.song_count} songs. {/if}
					{if="$album_stats.total_time"}{$album_stats.total_time} minutes.{/if}
				</p>
		{/if}
			</li>
	{/if}

{if="$all_songs"}
			<li><a href="{$all_songs}"><em>All Songs</em></a></li>
{/if}

{if="$shuffle"}
			<li><a href="{$shuffle}"><em>Shuffle</em></a></li>
{/if}

{loop="$list"}
			<li><a href="{$base_uri}{$value.url}">{$value.name}</a></li>
{/loop}
		</ul>

	</div><!-- /content -->

	<div data-role="footer" data-id="list-footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="/playlist">Playlists</a></li>
				<li><a href="/artist">Artists</a></li>
				<li><a href="/song">Songs</a></li>
				<li><a href="/album">Albums</a></li>
				<li><a href="/genre">Genres</a></li>
{*
				<li><a href="#">More</a></li>
*}
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->

</div><!-- /page -->

</body>
</html>
