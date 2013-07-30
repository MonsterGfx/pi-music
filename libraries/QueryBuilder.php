<?php

class QueryBuilder {

	/**
	 * The regex used for routing queries 
	 * 
	 * queries look like this:
	 *
	 *		playlist 			- list of playlists
	 *				page head='Playlist', list head=null
	 *
	 *		playlist/1/song			- list of songs for playlist=1
	 *				page head=playlist.name, list head=null
	 *
	 *		playlist/1/song/2 	- load all songs for playlist=1, start playing song=2, go to nowplaying
	 *
	 *
	 *		artist 					- list of artists
	 *				page head='Artists', list head=null
	 *
	 *		artist/1/album			- list of albums for artist=1
	 *				page head=artist.artist, list head=null
	 *
	 *		artist/1/album/2/song	- list of songs for artist=1, album=2
	 *				page head=artist.artist, list head=album+stats
	 *
	 *		artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
	 * 
	 * 		artist/1/song			- list of all songs for artist=1
	 *				page head=artist.artist, list head=null
	 *
	 *
	 *		song 	- list of all songs
	 *				page head='Songs', list head=null
	 *
	 *		song/1 	- load ALL songs, play song=1, go to nowplaying
	 *
	 *
	 *		album 			- list all albums
	 *				page head='Albums', list head=null
	 *
	 *		album/1/song	- list of songs for album=1
	 *				page head=album.title, list head=album+stats
	 *
	 *		album/1/song/2	- load all songs for album=1, play song=2, go to nowplaying
	 *
	 *
	 *		genre 		- list all genres
	 *				page head='Genres', list head=null
	 *
	 *		genre/1/artist 	- list of artists for genre=1
	 *				page head=genre.name, list head=null
	 *
	 *		genre/1/artist/2/album 	- list of albums for genre=1, artist=2
	 *				page head=artist.artist, list head=null
	 *
	 *		genre/1/artist/2/album/3/song	- list of songs for genre=1, artist=2, album=3
	 *				page head=artist.artist, list head=album+stats
	 *
	 *
	 *		genre/1/artist/2/album/3/song/4	- load all songs for genre=1, artist=2, album=3, play song=4, go to nowplaying
	 * 
	 * In addition, any path ending in song/# can also end song/shuffle, in
	 * which case all songs are loaded and play shuffled.
	 */	
	private static $allowed_query_regex = "^(/([a-zA-Z]+)/([0-9]+)){0,5}(/([a-zA-Z]+)(/([0-9]+|shuffle))?)[/]?$";

	/**
	 * A "getter" for the routing regex
	 * @return string
	 */
	public static function regex() { return static::$allowed_query_regex; }

	/**
	 * Get the results of a "query"
	 * 
	 * A "query" in this context is the sequence of arguments passed to the
	 * router which describes the information the user is looking for. Above is
	 * a set of examples of all the possible types of queries.
	 * 
	 * @param type $arguments 
	 * @return type
	 */
	public static function get($arguments)
	{
		// instantiate the result array
		$results = array(
			'items' => null,
			'page_title' => null,
			'previous_page' => null,
			'album_stats' => null,
		);

		// save the original arguments
		$original_args = $arguments;

		// the Model object
		$obj = null;

		// the list of pages we pass through on the way. This will be used to
		// determine the title of the previous page for the "back" button
		$pages = array();

		// loop through the arguments
		while(count($arguments))
		{
			// get the next argument off the front of the array
			$arg = array_shift($arguments);

			// has the model already been created?
			if(!$obj)
			{
				// no. I need to instantiate an object
				$obj = Model::factory(ucfirst(strtolower($arg)));

				// set the page title
				$results['page_title'] = ucfirst(strtolower($arg)).'s';
			}
			else
			{
				// yes. we have an object, so the next step is to look for a
				//relationship
				$method = $arg.'s';
				$obj = $obj->$method();
			}

			// add this argument to the list of pages
			$pages[] = ucfirst(strtolower($a)).'s';

			// are there any more items on the stack?
			if(count($arguments))
			{
				// Yes! The next one must be an ID value
				$id = array_shift($arguments);

				// is it "shuffle"?
				if($id=='shuffle')
				{
					// Yes! we're shuffling a list of songs. Order it by track
					// number then alphabetically by name
					$obj = $obj->order_by_asc('track_number')->order_by_asc('name');

					// and get the list of objects
					$obj = $obj->find_many();
				}
				else
				{
					// no, it's an ID value

					// find the object with that ID
					$obj = $obj->find_one($id);

					// is it an Album object?
					if(get_class($obj)=='Album')
					{
						// Yes! Save the album stats
						$results['album_stats'] = $obj->getStats();
					}

					// update the page title with the name of this object
					$results['page_title'] = $obj->name;
				}
			}
			else
			{
				// there are no more arguments, so what we're left with is a
				// list (of songs, albums, artists, whatever)

				// are we looking at a list of songs?
				if($arg=='song')
				{
					// Yes. order it by track number then alphabetically by name
					$obj = $obj->order_by_asc('track_number')->order_by_asc('name');
				}

				// are we looking for a list of albums?
				if($arg=='album')
				{
					// Yes. Order the list by release year, then alphabetically
					// by name
					$obj = $obj->order_by_asc('year')->order_by_asc('name');
				}

				// and get the list of objects
				$obj = $obj->find_many();
			}

			// some final cleanup of the list of pages - does the current object
			// have a name?
			if(isset($obj->name))
			{
				// Yes! remove the last item from the list
				array_pop($pages);

				// and add the object name in its place
				$pages[] = $obj->name;
			}

		}

		// add the object to the results
		$results['items'] = $obj;

		// now figure out the previous page path & text

		// add the first argument back onto the list of pages
		array_unshift($pages, ucfirst(strtolower($original_args[0])).'s');

		// the base index to work from
		$i = count($original_args)-1;

		// the page
		$previous_page = $i/2-1;
		$previous_page = $previous_page>=0 ? $pages[$previous_page] : null;

		// the path
		$previous_path = $i-2;
		$previous_path = $previous_path>=0 ? '/'.implode('/',array_chunk($original_args,$previous_path+1)[0]) : null;

		// finally, put the previous info together
		$previous = null;
		if($previous_page && $previous_path)
			$previous = array(
					'text' => $previous_page,
					'path' => $previous_path,
				);
		
		// add the previous page info to the results
		$results['previous_page'] = $previous;

		// and return the results
		return $results;
	}

	/*
	 *		artist 					- list of artists
	 *				page head='Artists', list head=null
	 *
	 *		artist/1/album			- list of albums for artist=1
	 *				page head=artist.artist, list head=null
	 *
	 *		artist/1/album/2/song	- list of songs for artist=1, album=2
	 *				page head=artist.artist, list head=album+stats
	 *
	 *		artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
	 * 
	 * 		artist/1/song			- list of all songs for artist=1
	 *				page head=artist.artist, list head=null
	 */

	/**
	 * Build a list of entries for a given key value.
	 * 
	 * This is used to parse the output from MPD, which is an array of strings
	 * of the form "key: value\n". Given a list of strings and a key value, this
	 * method will extract the values corresponding to that key.
	 * 
	 * @param string $key 
	 * @param array $list 
	 * @return array
	 */
	private static function buildListOfKeys($key, $list)
	{
		$result = array();
		foreach($list as $l)
		{
			// a little kludge here to handle names, etc. with colons in them.
			// Explode the string with colons, shift off the first item (the
			// key), then implode the rest with colons to rebuild
			$a = explode(':', $l);
			$b = array_shift($a);
			$a = array( $b, implode(':', $a));
			if(count($a)>=2)
			{
				$k = strtolower(trim($a[0]));
				$v = trim($a[1]);

				if($k==strtolower($key))
				{
					if(!in_array($v,$result))
						$result[] = $v;
				}
			}
		}
		sort($result, SORT_NATURAL|SORT_FLAG_CASE);

		return $result;
	}
	/**
	 * Build a list of artists from a query
	 * 
	 * @param array $query 
	 * The URI, parsed into an array of elements
	 * 
	 * @return array
	 */
	public static function query($query=array())
	{
		//		artist					- list of artist
		//				list: artist
		//		artist/1/album			- list of albums for artist=1
		//				search: artist, ID - extract album names
		//		artist/1/album/2/song	- list of songs for artist=1, album=2
		//				search: artist, ID, album, ID - extract song names
		//		artist/1/album/2/song/3	- load all songs for artist=1, album=2, play song=3, go to nowplaying
		//				search: artist, ID, album, ID, song, ID - get song info & start playing
		// 		artist/1/song			- list of all songs for artist=1
		//				search: artist, ID - get song titles

		// chop the array up into pairs of entries
		$query = array_chunk($query,2);

		// start building the command
		$command = null;
		$args = null;

		// step through the chunks
		while(count($query)>0)
		{
			$a = array_shift($query);

			// is it a single element array with the value 'artist'?
			if(count($a)==1 && $a[0]=='artist')
			{
				// Yes! update the command and exit the loop
				$command = 'list';
				$args = array('artist');
				break;
			}

			// No. add to the command
			$command = 'search';
			$args[] = $a[0];
			if(isset($a[1]))
				$args[] = Music::decode($a[1]);
				// $args[] = $a[1];
		}


		// at this point, the last item in the $args array tells us what kind
		// of list we want
		$list = array_pop($args);
		// is the $list values actually "song"?
		if($list=='song')
		{
			// Yes! We actually want the "title" tag from the results
			$list = 'title';
		}

		// run the command
		$result = Music::send($command, $args);
Kint::dump($result['values']);

		// build the appropriate type of list
		return static::buildListOfKeys($list, $result['values']);
	}

}