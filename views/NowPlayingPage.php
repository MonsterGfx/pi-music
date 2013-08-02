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
	public static function render($song, $request)
	{
		// instantiate the template engine
		$parser = new Rain\Tpl;

		// get the previous page address from the $request
		$back_url = $request->server()['HTTP_REFERER'];

		// get the image data for the song
		$image_data = Song::getImageData($song['Artist'], $song['Album'], 320);

		// assign the values to the template parser
		$parser->assign(array(
			'image_path'	=> null,
			'image'			=> null,
			// 'image_path'	=> $image_path,
			'image'			=> $image_data,
			'song'			=> $song,
			'volume'		=> Music::getVolume(),
			'back'			=> $back_url,

			// 'debug'			=> print_r($request,true),
		));

		// return the HTML
		return $parser->draw( "now-playing-page", true );
	}
}