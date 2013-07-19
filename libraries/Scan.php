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
				$artist = Database::voodORM()->table('artists')->where('artist',$tags['artist'][0])->findOne();
				if(!$artist)
				{
					$artist = Database::voodORM()->table('artists');
					$artist->artist = $tags['artist'][0];
					$artist = $artist->save();
				}
			}

			// create/update the album record
			$album = null;
			if(isset($tags['album']))
			{
				$album = Database::voodORM()->table('albums')->where('album',$tags['album'][0])->_and()->where('artist_id',$artist->id)->findOne();
				if(!$album)
				{
					$album = Database::voodORM()->table('albums');
					$album->artist_id = $artist->id;
					$album->album = $tags['album'][0];
					$album = $album->save();
				}
			}

			// create/update the genre record
			$genre = null;
			if(isset($tags['genre']))
			{
				$genre = Database::voodORM()->table('genres')->where('genre',$tags['genre'][0])->findOne();
				if(!$genre)
				{
					$genre = Database::voodORM()->table('genres');
					$genre->genre = $tags['genre'][0];
					$genre = $genre->save();
				}
			}


			// try to load the entry for this song
			$song = Database::voodORM()->table('songs')->where('filenamepath', $file_info['filenamepath'])->findOne();

			// if the song exists and the last file modification time is BEFORE the
			// database "updated_at" value, then there's no reason to continue since
			// the file is still current
			if($song && $file_updated<$song->updated_at)
				continue;

			// if the song wasn't found, then create it
			if(!$song)
			{
				$song = Database::voodORM()->table('songs');
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

			$song->title		= isset($tags['title']) ? $tags['title'][0] : null;
			$song->artist_id	= $artist ? $artist->id : null;
			$song->album_artist	= isset($tags['album_artist']) ? $tags['album_artist'][0] : null;
			$song->album_id		= $album ? $album->id : null;
			$song->genre_id		= $genre ? $genre->id : null;
			$song->track_number	= isset($tags['track_number']) ? $tags['track_number'][0] : null;
			$song->disc_number	= isset($tags['disc_number']) ? $tags['disc_number'][0] : null;
			$song->compilation	= isset($tags['compilation']) ? $tags['compilation'][0] : null;
			$song->bpm			= isset($tags['bpm']) ? $tags['bpm'][0] : null;
			$song->rating		= isset($tags['rating']) ? $tags['rating'][0] : null;

			$song->updated_at = time();

			// save
			$song->save();

		}
		echo "done ($path).\n\n\n";

	}

	public static function scanAll()
	{
		// get the file path
		$path = Config::get('app.music-path');

		if(!$path)
			throw new Exception("Invalid music path");

		Scan::scanFolder($path);
	}
}