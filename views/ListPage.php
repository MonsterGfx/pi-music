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
	public static function render($page_title, $previous, $album_stats, $list)
	{

		// instantiate the template engine
		$parser = new Rain\Tpl;

		// assign the values to the template parser
		$parser->assign(array(
			'list'				=> $list,
			'base_uri'			=> static::$base_uri,
			'object_type'		=> $type,
			'page_title'		=> $page_title,
			'previous'			=> $previous,
			'album_stats'		=> $album_stats,
			'now_playing'		=> Music::isPlayingOrPaused(),
			'include_all_songs'	=> $include_all_songs,
			'include_shuffle'	=> $include_shuffle,
			'all_songs_uri'		=> $all_songs,
		));

		// return the HTML
		return $parser->draw( "list-page", true );
	}
}