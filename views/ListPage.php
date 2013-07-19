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


class ListPage
{

	public static function render($header_object, $list_items)
	{
		// instantiate the template engine
		$tpl = new Rain\Tpl;

		// assign some values
		// the values provided are objects. These need to be converted to array
		$list = (array)$list_items;
		array_walk($list, function(&$v, $k){ $v = $v->toArray(); });

		$tpl->assign(array(
			'head' => (array)$header_object,
			'list' => $list,
		));

		// return the HTML
		return $tpl->draw( "list-page", true );
	}
}