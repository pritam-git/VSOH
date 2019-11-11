<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mime.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Mime.php 415 2015-09-17 08:57:39Z nm $
 */

/**
 *
 *
 * L8M_Mime
 *
 *
 */
class L8M_Mime extends Zend_Mime
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	const TYPE_APPLICATION_WORD				= 'application/msword';
	const TYPE_APPLICATION_WORD_2007		= 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	const TYPE_APPLICATION_EXCEL			= 'application/vnd.ms-excel';
	const TYPE_APPLICATION_EXCEL_2007		= 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	const TYPE_APPLICATION_POWERPOINT		= 'application/vnd.ms-powerpoint';
	const TYPE_APPLICATION_POWERPOINT_2007	= 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
	const TYPE_APPLICATION_SHOCKWAVE_FLASH	= 'application/x-shockwave-flash';
	const TYPE_APPLICATION_PDF				= 'application/pdf';
	const TYPE_APPLICATION_ZIP				= 'application/zip';
	const TYPE_APPLICATION_ZIP_X			= 'application/x-zip-compressed';
	const TYPE_APPLICATION_ZIP_XG			= 'application/x-gzip';
	const TYPE_APPLICATION_ZIP_XWIN			= 'application/x-gzip';
	const TYPE_IMAGE_BMP					= 'image/bmp';
	const TYPE_IMAGE_GIF					= 'image/gif';
	const TYPE_IMAGE_ICO					= 'image/x-icon';
	const TYPE_IMAGE_JPG					= 'image/jpeg';
	const TYPE_IMAGE_PNG					= 'image/png';
	const TYPE_IMAGE_TIF					= 'image/tiff';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of extensions and associated Mime types
	 *
	 * @var array
	 */
	protected static $_mimeTypes = array(
										 'bmp'=>self::TYPE_IMAGE_BMP,
										 'doc'=>self::TYPE_APPLICATION_WORD,
										 'docx'=>self::TYPE_APPLICATION_WORD_2007,
										 'gif'=>self::TYPE_IMAGE_GIF,
										 'ico'=>self::TYPE_IMAGE_ICO,
										 'jpg'=>self::TYPE_IMAGE_JPG,
										 'jpeg'=>self::TYPE_IMAGE_JPG,
										 'htm'=>self::TYPE_HTML,
										 'html'=>self::TYPE_HTML,
										 'pdf'=>self::TYPE_APPLICATION_PDF,
										 'png'=>self::TYPE_IMAGE_PNG,
										 'ppt'=>self::TYPE_APPLICATION_POWERPOINT,
										 'ppt'=>self::TYPE_APPLICATION_POWERPOINT,
										 'pptx'=>self::TYPE_APPLICATION_POWERPOINT_2007,
										 'swf'=>self::TYPE_APPLICATION_SHOCKWAVE_FLASH,
										 'txt'=>self::TYPE_TEXT,
										 'xls'=>self::TYPE_APPLICATION_EXCEL,
										 'xlsx'=>self::TYPE_APPLICATION_EXCEL_2007,
										 'zip'=>self::TYPE_APPLICATION_ZIP,
										);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns MIME type of the file at the specified location.
	 *
	 * @todo   actual file checking?
	 * @param  string $file
	 * @return string
	 */
	public static function getMimeType($file = NULL)
	{
		if ($file &&
			is_string($file)) {

			$extension = strtolower(substr($file, strrpos($file, '.')  + 1));
			if (array_key_exists($extension, self::$_mimeTypes)) {
				return self::$_mimeTypes[$extension];
			}
		}
		return self::TYPE_OCTETSTREAM;
	}

	/**
	 * Check mime type for zip.
	 *
	 * @param string $mimeType
	 * @return boolean
	 */
	public static function isZip($mimeType) {
		$returnValue = FALSE;
		if ($mimeType == self::TYPE_APPLICATION_ZIP ||
			$mimeType == self::TYPE_APPLICATION_ZIP_X ||
			$mimeType == self::TYPE_APPLICATION_ZIP_XG ||
			$mimeType == self::TYPE_APPLICATION_ZIP_XWIN) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}
}