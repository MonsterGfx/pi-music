<?php

class Scan {

	/**
	 * Extract the image data (if any) from a file
	 *
	 * @param string $filename
	 *
	 * @return Image|null
	 */
	private static function getImage($filename)
	{
		// check the file
		if(!file_exists($filename))
			return null;

		// scan the file
		$getID3 = new getID3;

		// Analyze file and store returned data in $ThisFileInfo
		$file_info = $getID3->analyze($filename);




		// try to extract the album artwork (if any)
		// find the artwork in the $file_info structure
		$artwork = null;

		// try the different places in which I've found artwork
		if(isset($file_info['comments']['picture'][0]['data']))
		{
			$artwork = $file_info['comments']['picture'][0]['data'];
		}
		else if(isset($file_info['id3v2']['APIC'][0]['data']))
		{
			$artwork = $file_info['id3v2']['APIC'][0]['data'];
		}

		// did we find some artwork?
		if(!$artwork)
			return null;

		// create the image object and return it
		return imagecreatefromstring($artwork);
	}

	/**
	 * Resize an image
	 *
	 * @param Image $image
	 * @param int $resolution
	 * @return Image
	 */
	private static function resizeImage($image, $resolution)
	{
		// create a resized image
		$copy = imagecreatetruecolor($resolution, $resolution);

		// copy the image
		imagecopyresampled($copy, $image, 0, 0, 0, 0, $resolution, $resolution, imagesx($image), imagesy($image));

		// return the resulting copy
		return $copy;
	}
	/**
	 * Scan the albums & artists in the music database and create artwork for
	 * them.
	 */
	public static function scanAll()
	{
		// get some config items
		$artwork_folder = Config::get('app.music-artwork-path');
		$artwork_sizes = array(180,320);

		// get the music directory path
		$music_directory = Config::get('app.music-path');

		// get the list of artists
		$artists = Artist::getList();

		// step through the artists
		foreach($artists as $artist)
		{
			// get the list of albums for the current artist
			$albums = Artist::getAlbums($artist);

			// step through the albums
			foreach($albums as $album)
			{
				// create a file name for the  artist/album combination
				$image_file = md5($artist.$album['album']);

				// does the file already exist?
				if(!file_exists($artwork_folder.$image_file.'.jpg'))
				{
					// No! we need to extract the artwork from a song file for
					// this artist/album

					// get the list of songs
					$songs = Album::getSongs($artist, $album['album']);

					// step through the songs & attempt to extract the image
					//data
					foreach($songs as $song)
					{
						// get the music file name
						$music_file = $music_directory.$song['file'];

						// make sure we have a music file to check
						if(file_exists($music_file))
						{
							// get the image data (if we can)
							$image = static::getImage($music_file);

							// make sure we got an image
							if($image)
							{
								// save the "untouched" image file
								// save the original as a JPEG
								imagejpeg($image, Config::get('app.music-artwork-path').$image_file.".jpg", 100);

								// loop through the required sizes
								foreach($artwork_sizes as $s)
								{
									// resize to the appropriate size
									$i = static::resizeImage($image, $s);

									// and save
									if($i)
										imagejpeg($i, Config::get('app.music-artwork-path').$image_file."-{$s}.jpg", 100);

								} // END loop through the required sizes

								// all done with this one!
								// break out of the song loop (since we
								// don't need to scan any more songs from
								// this album)
								break;

							} // END make sure we got an image

						} // END make sure we have a music file to check

					} // END step through the songs

				} // END does the file already exist?

			} // END step through the albums

		} // END step through the artists

	}
}