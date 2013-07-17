<?php

class ListView
{
	private $data = null;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function render()
	{
		return "<pre>".print_r($this->data,true)."</pre>";
	}
}