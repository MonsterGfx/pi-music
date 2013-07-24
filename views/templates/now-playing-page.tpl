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
		<p style='text-align: center; color: #bbb; font-size:11px; margin: 1px 0 0;'>{$artist}</p>
		<p style='text-align: center; font-size:11px; margin: 1px 0 0;'><strong>{$title}</strong></p>
		<p style='text-align: center; color: #bbb; font-size:11px; margin: 1px 0 0;'>{$album}</p>
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
				<li><a href="/playlist">Playlists</a></li>
				<li><a href="/artist">Artists</a></li>
				<li><a href="/song">Songs</a></li>
				<li><a href="/album">Albums</a></li>
				<li><a href="#">More</a></li>
			</ul>
		</div><!-- /navbar -->
	</div><!-- /footer -->

</div><!-- /page -->

</body>
</html>
