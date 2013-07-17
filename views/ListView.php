<?php

/**
 * The idea here is that the controller will do the work (whatever that amounts
 * to) and will instantiate a new View of the appropriate type. That view will
 * have a render method which will render the page appropriately.
 *
 * In this way we have some separation of concerns:
 *
 * - the controller marshalls the data and passes it to a view
 *
 * - the view massages that data into the presentation form (using templates)
 *
 * The routes.php file decides which controller to use; the controller decides
 * which view class to use; the view class decides which template(s) to use.
 */


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