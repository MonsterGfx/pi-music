<?php

class Song {

	public static function getList()
	{
		// get the list
		$result = Music::send('listallinfo');

		// return the values
		return Music::buildSongList($result['values']);
	}

	/**
	 * Get the image data as a data URL for an artist & album
	 *
	 * @param string $artist
	 * @param string $album
	 * @param int $size
	 * @return string
	 */
	public static function getImageData($artist, $album, $size)
	{
		// build the expected file name
		$image_file = Config::get('app.music-artwork-path').md5($artist.$album)."-{$size}.jpg";

		// does the file exist?
		if(file_exists($image_file))
		{
			// convert it to a string & return it
			return Image::toDataURL($image_file);
		}

		// couldn't find the artwork, return the default artwork
		switch($size)
		{
			case 180:
				return Image::toDataURL(Config::get('app.music-artwork-path').'default-album-180.jpg');
			case 320:
				return Image::toDataURL(Config::get('app.music-artwork-path').'default-album-320.jpg');
			default:
				return Image::toDataURL(Config::get('app.music-artwork-path').'default-album.jpg');
		}
	}
}