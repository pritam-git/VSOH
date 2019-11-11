<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Image/Jpg.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Jpg.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Image_Jpg
 *
 *
 */
class L8M_Image_Jpg extends L8M_Image_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Attempts to load the image from a file with the specified filename.
	 *
	 * @param  string $fileName
	 * @return resource
	 */
	protected function _load($fileName = NULL)
	{
		return @imagecreatefromjpeg($fileName);
	}

	/**
	 * Attempts to save the image with the specified filename.
	 *
	 * @param  string $fileName
	 * @return bool
	 */
	protected function _save($fileName = NULL)
	{
		return @imagejpeg($this->_image, $fileName, 1000);
	}

}