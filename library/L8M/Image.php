<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Image.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Image.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Image
 *
 *
 */
class L8M_Image
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of mime types and associated image handlers.
	 *
	 * @todo add more mappings of mime types to handlers
	 *
	 * @var array
	 */
	protected static $_supportedMimeTypes = array(
		L8M_Mime::TYPE_IMAGE_GIF=>'Gif',
		L8M_Mime::TYPE_IMAGE_PNG=>'Png',
		L8M_Mime::TYPE_IMAGE_JPG=>'Jpg',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Image_Abstract instance from file name.
	 *
	 * @param  string $file
	 * @return L8M_Image_Abstract
	 */
	public static function fromFile($fileName = NULL, $mimeType = NULL)
	{
		if (!$fileName ||
			!is_string($fileName)) {
			throw new L8M_Image_Exception('Filename needs to be specified as a string.');
		}

		if (!$mimeType) {
			$imageInfo = @getimagesize($fileName);
			if ($imageInfo &&
				array_key_exists('mime', $imageInfo)) {
				$mimeType = $imageInfo['mime'];
			}
		}

		if (!$mimeType ||
			!array_key_exists($mimeType, self::$_supportedMimeTypes)) {
			return NULL;
		}

		$imageClass = 'L8M_Image_'
					  . str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower(self::$_supportedMimeTypes[$mimeType]))));

		if (!class_exists($imageClass)) {
			Zend_Loader::loadClass($imageClass);
		}

		if (!class_exists($imageClass)) {
			return NULL;
		}

		$image = new $imageClass();

		if (!($image instanceof L8M_Image_Abstract)) {
			throw new L8M_Image_Exception('Image class needs to extend L8M_Image_Abstract');
		}

		try {
			$image->load($fileName);
		} catch (L8M_Image_Exception $exception) {
			$width = 10;
			$height = 10;
			if (is_array($imageInfo) &&
				array_key_exists(0, $imageInfo) &&
				array_key_exists(1, $imageInfo)) {

				$width = $imageInfo[0];
				$height = $imageInfo[1];
			}
			$image->createNonExisting($fileName, $width, $height);

			if ($image->isErrorOccured()) {
				throw new L8M_Image_Exception('Image-File seems to be corrupt and can not be loaded.');
			}
		}

		return $image;

	}

}