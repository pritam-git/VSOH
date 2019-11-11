<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Image/Png.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Png.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Image_Png
 *
 *
 */
class L8M_Image_Png extends L8M_Image_Abstract
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
		return @imagecreatefrompng($fileName);
	}

	/**
	 * Attempts to save the image with the specified filename.
	 *
	 * @param  string $fileName
	 * @return bool
	 */
	protected function _save($fileName = NULL)
	{
		return @imagepng($this->_image, $fileName, 9);
	}

}