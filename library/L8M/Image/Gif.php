<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Image/Gif.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Gif.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Image_Gif
 *
 *
 */
class L8M_Image_Gif extends L8M_Image_Abstract
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
		return @imagecreatefromgif($fileName);
	}

	/**
	 * Attempts to save the image with the specified filename.
	 *
	 * @param  string $fileName
	 * @return bool
	 */
	protected function _save($fileName = NULL)
	{
		return @imagegif($this->_image, $fileName);
	}

}