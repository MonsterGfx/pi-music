<!DOCTYPE html> 
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Single page template</title> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.js"></script>
</head> 

<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Single page</h1>
	</div><!-- /header -->

	<div data-role="content">

		<ul data-role="listview">
{loop="$list"}
		    <li><a href="#">{$value.title}</a></li>
{/loop}
		</ul>

	</div><!-- /content -->
	
	<div data-role="footer">
		<h4>Footer content</h4>
	</div><!-- /footer -->
	
</div><!-- /page -->

</body>
</html>
