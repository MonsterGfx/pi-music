<?php

class View {
	
	protected static $base_uri = '';

	public static function setBaseUri($uri)
	{
		static::$base_uri = $uri;
	}

}