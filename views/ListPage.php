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
		$list = $list_items;

		// calculate the type of the next item
		$type = '';

		// some flags to indicate what types of special links to add

		// if the include_shuffle flag is set, then the view will render a
		// shuffle button at the top of the list
		$include_shuffle = false;

		// if the include_all_songs flag is set, then the view will render an
		// include all songs button at the top of the list so, for example,
		// if you're viewing an artist, you can see the list of all songs for
		// that artist
		$include_all_songs = false;

		// build the URL for all songs (just in case)
		$all_songs = explode('/',static::$base_uri);
		array_pop($all_songs);
		$all_songs[] = 'song';
		$all_songs = implode('/',$all_songs);

		// are there any items in the list?
		if(count($list_items))
		{
			// Yes! figure out what type of object we're dealing with
			$class = get_class($list_items[0]);

			// set some important values for the view rendering

			// the object type ($type) is used to determine what the final
			// segment of the URL to use in the links on the page. For example,
			// when you're on "/artist", you're seeing a list of albums and the
			// link for each album must be something like
			// "/artist/1/album/2/song". The $type value provides the third
			// segment of the URL.
			switch($class)
			{
				case 'Genre':
					$type = 'artist';
					$include_all_songs = true;
					break;
				case 'Artist':
					$type = 'album';
					$include_all_songs = true;
					break;
				case 'Playlist':
				case 'Album':
					$type = 'song';
					$include_all_songs = true;
					break;
				case 'Song':
					$include_shuffle = true;
					break;
				default:
					throw new Exception("Unrecognized object class");
			}
		}

		// assign the values to the template parser
		$parser->assign(array(
			'base_uri'			=> static::$base_uri,
			'object_type'		=> $type,
			'page_title'		=> $page_title,
			'previous'			=> $previous,
			'album_stats'		=> $album_stats,
			'list'				=> $list,
			'now_playing'		=> Music::isPlayingOrPaused(),
			'include_all_songs'	=> $include_all_songs,
			'include_shuffle'	=> $include_shuffle,
			'all_songs_uri'		=> $all_songs,
		));

		// return the HTML
		return $parser->draw( "list-page", true );
	}
}