<?php

class Scan {

	private static function scanFolder($path)
	{
		// make sure there's a trailing '/' on the path
		$x = strrev($path);
		if($x[0]!='/')
			$path .= '/';

		// iterate through the folders
		$dirhandle = opendir($path);

		echo "starting ($path)\n";

		while(false!==($entry=readdir($dirhandle)))
		{
			// if we have '.' or '..' then skip
			if($entry=='.' || $entry=='..')
				continue;

			$filename = "{$path}{$entry}";

			// if it's a folder, then scan it recursively
			if(is_dir($filename))
			{
				Scan::scanFolder($filename);
				continue;
			}

			// otherwise, it's a file & we need to scan it

			// get the last update time
			$file_updated = filemtime($filename);

			// scan the file
			$getID3 = new getID3;

			// Analyze file and store returned data in $ThisFileInfo
			$file_info = $getID3->analyze($filename);

			// get the tags
			$tags = $file_info['tags'];
			if(isset($tags['id3v2']))
				$tags = $tags['id3v2'];
			else if(isset($tags['id3v1']))
				$tags = $tags['id3v1'];
			else if(isset($tags['quicktime']))
				$tags = $tags['quicktime'];
			else
				$tags = array();

			// create/update the artist record
			$artist = null;
			if(isset($tags['artist']))
			{
				$artist = Model::factory('Artist')->where('name',$tags['artist'][0])->find_one();
				if(!$artist)
				{
					$artist = Model::factory('Artist')->create();
					$artist->name = $tags['artist'][0];
					$artist->save();
				}
			}

			// create/update the album record
			$album = null;
			if(isset($tags['album']))
			{
				$album = Model::factory('Album')->where('name',$tags['album'][0])->where('artists_id',$artist->id)->find_one();
				if(!$album)
				{
					$album = Model::factory('Album')->create();
					$album->artists_id	= $artist->id;
					$album->name		= $tags['album'][0];
					$album->year		= isset($tags['year']) ? $tags['year'][0] : null;
					$album->save();
				}
			}

			// create/update the genre record
			$genre = null;
			if(isset($tags['genre']))
			{
				$genre = Model::factory('Genre')->where('name',$tags['genre'][0])->find_one();
				if(!$genre)
				{
					$genre = Model::factory('Genre')->create();
					$genre->name = $tags['genre'][0];
					$genre->save();
				}
			}


			// try to load the entry for this song
			$song = Model::factory('Song')->where('filenamepath', $file_info['filenamepath'])->find_one();

			// if the song exists and the last file modification time is BEFORE the
			// database "updated_at" value, then there's no reason to continue since
			// the file is still current
			if($song && $file_updated<$song->updated_at)
				continue;

			// if the song wasn't found, then create it
			if(!$song)
			{
				$song = Model::factory('Song')->create();
				$song->created_at = time();
			}

			$song->filenamepath	= $file_info['filenamepath'];
			$song->filesize		= $file_info['filesize'];
			$song->fileformat	= $file_info['fileformat'];


			$song->dataformat		= isset($file_info['audio']['dataformat']) ? $file_info['audio']['dataformat'] : null;
			$song->codec			= isset($file_info['audio']['codec']) ? $file_info['audio']['codec'] : null;
			$song->sample_rate		= isset($file_info['audio']['sample_rate']) ? $file_info['audio']['sample_rate'] : null;
			$song->channels			= isset($file_info['audio']['channels']) ? $file_info['audio']['channels'] : null;
			$song->bits_per_sample	= isset($file_info['audio']['bits_per_sample']) ? $file_info['audio']['bits_per_sample'] : null;
			$song->lossless			= isset($file_info['audio']['lossless']) ? $file_info['audio']['lossless'] : null;
			$song->channelmode		= isset($file_info['audio']['channelmode']) ? $file_info['audio']['channelmode'] : null;
			$song->bitrate			= isset($file_info['audio']['bitrate']) ? $file_info['audio']['bitrate'] : null;
			$song->playtime_seconds			= isset($file_info['playtime_seconds']) ? $file_info['playtime_seconds'] : null;

			$song->name			= isset($tags['title']) ? $tags['title'][0] : null;
			$song->artists_id	= $artist ? $artist->id : null;
			$song->album_artist	= isset($tags['album_artist']) ? $tags['album_artist'][0] : null;
			$song->albums_id		= $album ? $album->id : null;
			$song->genres_id		= $genre ? $genre->id : null;
			$song->track_number	= isset($tags['track_number']) ? $tags['track_number'][0] : null;
			$song->disc_number	= isset($tags['disc_number']) ? $tags['disc_number'][0] : null;
			$song->compilation	= isset($tags['compilation']) ? $tags['compilation'][0] : null;
			$song->bpm			= isset($tags['bpm']) ? $tags['bpm'][0] : null;
			$song->rating		= isset($tags['rating']) ? $tags['rating'][0] : null;

			$song->updated_at = time();

			// save
			$song->save();

			// now extract the album artwork (if any)

			// find the artwork in the $file_info structure
			$artwork = null;
			$artwork_type = null;
			if(isset($file_info['comments']['picture'][0]['data']))
			{
				$artwork = $file_info['comments']['picture'][0]['data'];
				$artwork_type = $file_info['comments']['picture'][0]['image_mime'];
			}
			else if(isset($file_info['id3v2']['APIC'][0]['data']))
			{
				$artwork = $file_info['id3v2']['APIC'][0]['data'];
				$artwork_type = $file_info['id3v2']['APIC'][0]['image_mime'];
			}

			// did we find some artwork?
			if($artwork)
			{
				// build the artwork path
				$image_path = Config::get('app.music-artwork-path').'album-'.$album->id;
				switch($artwork_type)
				{
					case 'image/jpeg':
						$image_path .= '.jpg';
						break;
					case 'image/png':
						$image_path .= '.png';
						break;
					case 'image/gif':
						$image_path .= '.gif';
						break;
					case 'image/tiff':
						$image_path .= '.tiff';
						break;
					default:
						$image_path .= '.dat';
						break;
				}

				// check to see if the file already exists
				if(!file_exists($image_path))
				{
					// save the artwork
					file_put_contents($image_path, $artwork);
				}
			}
		}
		echo "done ($path).\n\n\n";

	}

	public static function scanAll()
	{
		// get the file path
		$path = Config::get('app.music-path');

		if(!$path)
			throw new Exception("Invalid music path");

		// if it's not an array, then make it one
		if(!is_array($path))
			$path = array($path);

		// step through the paths in the array
		foreach($path as $p)
			Scan::scanFolder($p);

		// now scan ALL music and remove any that entries do not exist
		$songs = Model::factory('Song')->find_many();
		foreach($songs as $s)
		{
			if(!file_exists($s->filenamepath))
			{
				$s->delete();
			}
		}
	}
}