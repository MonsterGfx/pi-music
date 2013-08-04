<?php

class Image {

	/**
	 * Convert an image file to a data URL
	 * 
	 * @param string $path 
	 * The path to the file
	 * 
	 * @return string
	 * The formatted data URL string
	 */
	public static function toDataUrl($path, $mime='image/jpg')
	{
		$result = null;
		// load the image data
		if(file_exists($path))
		{
			$img = file_get_contents($path);

			if($img)
			{
				$result = base64_encode($img);
				$result = "data:{$mime};base64,{$result}";
			}
		}

		return $result;
	}

}