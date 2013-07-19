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

	public static function render($header_object, $list_items)
	{
		// build the page
		$html = '';

		// output the page header

		// output the header
		$html .= ListView::outputHeader($header_object);

		// output the body of the list

		// output the page footer

		// and return the result
		return $html;
	}

	private static function outputHeader($obj)
	{
		// if it's a scalar value, then simply output it
		if(is_scalar($obj))
			return $obj;

		// if it's a Voodoo ORM object, then render it appropriately
		if(get_class($obj)=='Voodoo\VoodOrm')
		{
			// render it based on the type of object
			switch($obj->getTableName())
			{
				case 'albums':
					// get the artist info
					$artist = Database::voodORM()->table('artists')->where('id',$obj->artist_id)->findOne();
					$count = Database::voodORM()->table('songs')->where('album_id',$obj->id)->count();
					$length = Database::voodORM()->table('songs')->where('album_id',$obj->id)->sum('playtime_seconds');

					// render an album description
					$html = "{$artist->artist}<br />{$obj->album}<br />";
					if($obj->year)
						$html .= "Released {$obj->year}<br />";
					$a = array();
					if($count)
						$a[] = "$count songs";
					if($length)
						$a[] = round($length/60)." mins.";
					if(count($a))
						$html .= implode(', ',$a)."<br />";

					return $html;

					break;
				case 'artists':
				case 'genres':
				case 'songs':
					throw new Exception("Table ".$obj->getTableName()." is not yet implemented");
					break;
				default:
					throw new Exception("Unrecognized table name: ".$obj->getTableName());
			}
		}
		else
			throw new Exception("Unrecognized header type: ".get_class($obj));
	}
}