<?php

/**
 * The view class for a page containing a list of items.
 */
class ListPage extends View {

{

	public static function render($page_head, $list_head, $list_items)
	/**
	 * Render a list page
	 * 
	 * @param string $page_title 
	 * The page title (displayed in the top bar)
	 * 
	 * @param array|null $album_stats 
	 * The album stats, if this is a list of songs from an album
	 * 
	 * @param array $list_items 
	 * The list of items to display
	 * 
	 * @return string
	 * The HTML of the rendered page
	 */
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