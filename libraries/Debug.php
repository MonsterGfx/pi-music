<?php

class Debug {

	public static function pre($obj)
	{
		echo "<pre>".print_r($obj,true)."</pre>";
		die;
	}

}