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

		// get the path to the image file
		$image_path = Config::get('app.music-artwork-path').$song->artwork.'-320.jpg';

		// assign the values to the template parser
		$parser->assign(array(
			'image_path'	=> null,
			'image'			=> null,
			// 'image_path'	=> $image_path,
			// 'image'			=> Image::toDataUrl($image_path),
			'song'			=> $song,
			'volume'		=> Music::getVolume(),
			'back'			=> $back_url,

			// 'debug'			=> print_r($request,true),
		));

		// return the HTML
		return $parser->draw( "now-playing-page", true );
	}
}