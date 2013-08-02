<?php

/**
 * The view class for the "now playing" page
 */
class NowPlayingPage extends View {

	/**
	 * Render a list page
	 *
	 * @param Song $song
	 * The song that is currently playing
	 *
	 * @return string
	 * The HTML of the rendered page
	 */
	public static function render($song, $previous)
	{
		// instantiate the template engine
		$parser = new Rain\Tpl;

		// get the image data for the song
		$image_data = Song::getImageData($song['Artist'], $song['Album'], 320);

		// assign the values to the template parser
		$parser->assign(array(
			'image'		=> $image_data,
			'song'		=> $song,
			'volume'	=> Music::getVolume(),
			'previous'	=> $previous,
		));

		// return the HTML
		return $parser->draw( "now-playing-page", true );
	}
}