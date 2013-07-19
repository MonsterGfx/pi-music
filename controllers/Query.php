<?php

class Query
{

	public static function build($params)
	{
		return new ListPage($params);
	}
}
