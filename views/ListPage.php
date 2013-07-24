<?php

/**
 * The view class for a page containing a list of items.
 */
class ListPage extends View {

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
	public static function render($page_title, $previous, $album_stats, $list_items)
	{

		// instantiate the template engine
		$parser = new Rain\Tpl;

		// assign some values
		// the list of items is an array of objects. These need to be converted
		// to an array.
		$list = (array)$list_items;
		array_walk($list, function(&$v, $k){ $v = $v->toArray(); });

		// calculate the type of the next item
		$type = '';
		if(count($list_items))
		{
			$class = get_class($list_items[0]);
			switch($class)
			{
				case 'Genre':
					$type = 'artist';
					break;
				case 'Artist':
					$type = 'album';
					break;
				case 'Playlist':
				case 'Album':
					$type = 'song';
					break;
				case 'Song':
					break;
				default:
					throw new Exception("Unrecognized object class");

			}
		}

		// assign the values to the template parser
		$parser->assign(array(
			'base_uri'		=> static::$base_uri,
			'object_type'	=> $type,
			'page_title'	=> $page_title,
			'previous'		=> $previous,
			'album_stats'	=> $album_stats,
			'list'			=> $list,
			'now_playing'	=> Music::isPlaying(),
		));

		// return the HTML
		return $parser->draw( "list-page", true );
	}
}