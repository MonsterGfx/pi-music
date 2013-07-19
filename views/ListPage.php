<?php

/**
 * The view class for a page containing a list of items.
 */


class ListPage
{

	public static function render($page_head, $list_head, $list_items)
	{

		// instantiate the template engine
		$tpl = new Rain\Tpl;

		// assign some values
		// the values provided are objects. These need to be converted to array
		$list = (array)$list_items;
		array_walk($list, function(&$v, $k){ $v = $v->toArray(); });

		$page_head = $page_head->toArray();

		$list_head = $list_head->toArray();

Kint::dump($list_head); die;
		$tpl->assign(array(
			'page_head' => $page_head,
			'list_head' => $list_head,
			'list' => $list,
		));

		// return the HTML
		return $tpl->draw( "list-page", true );
	}
}