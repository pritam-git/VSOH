<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Image/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 17 2014-03-26 15:41:58Z nm $
 */

/**
 *
 *
 * L8M_Image_Abstract
 *
 *
 */
 abstract class L8M_Image_Abstract
 {

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the filename (absolute path) of an image.
	 *
	 * @var string
	 */
	protected $_fileName = NULL;

	/**
	 * A resource representing the content of the image.
	 *
	 * @var resource
	 */
	protected $_image = NULL;

	/**
	 * An array of information about the image (sort of caching).
	 *
	 * @var array
	 */
	protected $_imageInfo = NULL;

	/**
	 * Represents, whether an error occured during processing image-functions.
	 *
	 * @var array
	 */
	protected $_errorOccured = FALSE;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Image_Abstract instance
	 *
	 * @return void
	 *
	 */
	public function __construct()
	{

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Sets the image.
	 *
	 * @param  resource $image
	 * @return L8M_Image_Abstract
	 */
	public function setImage($image = NULL)
	{
		if (!is_resource($image)) {
			throw new L8M_Image_Exception('Image needs to be specified as a resource');
		}

		$this->_image = $image;
		$this->_imageInfo = array();

		return $this;
	}

	/**
	 * Sets the filename of the image.
	 *
	 * @param  string $fileName
	 * @return L8M_Image_Abstract
	 */
	public function setFileName($fileName = NULL)
	{
		return $this;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

 	/**
	 * Attempts to crop the image to the specified dimensions.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @param int $coordinateX
	 * @param int $coordinateY
	 * @param int $width
	 * @param int $height
	 * @return Default_Model_MediaImage
	 */
	public function crop($coordinateX = NULL, $coordinateY = NULL, $width = NULL, $height = NULL)
	{
		if (!is_resource($this->_image)) {
			throw new L8M_Image_Exception('Can not rotate an empty image.');
		}

		if ($coordinateX &&
			!is_int($coordinateX)) {
			throw new L8M_Image_Exception('Coordinate X needs to be an integer, if specified.');
		}

		if ($coordinateY &&
			!is_int($coordinateY)) {
			throw new L8M_Image_Exception('Coordinate Y needs to be an integer, if specified.');
		}

		if ($width &&
			!is_int($width)) {
			throw new L8M_Image_Exception('Width needs to be an integer, if specified.');
		}

		if ($height &&
			!is_int($height)) {
			throw new L8M_Image_Exception('Height needs to be an integer, if specified.');
		}

		/**
		 * create empty image
		 */
		$cropedImage = imagecreatetruecolor($width, $height);

		/**
		 * define a color as transparent
		 */
		imagecolortransparent($cropedImage, imagecolorallocatealpha($cropedImage, 0, 0, 0, 0));

		/**
		 * copy resized version of original image onto it
		 */
		if (!imagecopyresampled($cropedImage, $this->_image, 0, 0, $coordinateX, $coordinateY, $width, $height, $width, $height)) {
			$this->_errorOccured = TRUE;
		}

		$this->_image = $cropedImage;

		return $this;
	}

	/**
	 * returns an array with array and height as asoc keys
	 */
	public function getDimension()
	{
		$dimension = array(
			'width'=>$this->getWidth(),
			'height'=>$this->getHeight(),
		);
		return $dimension;
	}

	/**
	 * returns image filesize
	 */
	public function getFilesize()
	{
		return filesize($this->_fileName);
	}

	/**
	 * returns TRUE if errror is occured
	 */
	public function isErrorOccured() {
		return $this->_errorOccured;
	}

	/**
	 * returns image height
	 */
	public function getHeight()
	{
		return imagesy($this->_image);
	}

	/**
	 * Attempts to load an image from a file with the specified filename.
	 *
	 * @param  string $fileName
	 * @return L8M_Image_Abstract
	 */
	public function load($fileName = NULL)
	{
		if (!$fileName ||
			!is_string($fileName)) {

			throw new L8M_Image_Exception('Filename needs to be specified as a string.');
		}

		$fileName = realpath($fileName);

		if (!file_exists($fileName) ||
			!is_file($fileName) ||
			!is_readable($fileName)) {

			throw new L8M_Image_Exception('Filename does not specify a file that is readable.');
		}

		$image = $this->_load($fileName);

		if (is_resource($image)) {
			$this->_image = $image;
			$this->_fileName = $fileName;
		} else {
			throw new L8M_Image_Exception('Image-File seems to be corrupt and can not be loaded.');
		}

		return $this;
	}

	/**
	 * Attempts to save the image to a file with the specified filename.
	 *
	 * @param  string $fileName
	 * @param  bool   $overWrite
	 * @return L8M_Image_Abstract
	 *
	 */
	public function save($fileName = NULL, $overWrite = FALSE)
	{
		if (!$fileName) {
			$fileName = $this->_fileName;
		}

		if (!$fileName ||
			!is_string($fileName)) {

			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Filename needs to be specified as a string.');
		}

		/**
		 * @todo sanity check (filename)
		 */

		$overWrite = (bool) $overWrite;

		if (!$this->_errorOccured &&
			$this->_save($fileName)) {

			$this->_fileName = $fileName;
		}

		return $this;

	}

	/**
	 * Attempts to resize the image to the specified width and height.
	 *
	 * @param  int $width
	 * @param  int $height
	 * @return L8M_Image_Abstract
	 */
	public function resize($width = NULL, $height = NULL)
	{
		if (!is_resource($this->_image)) {

			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Can not resize an empty image.');
		}

		if ($width &&
			!is_int($width)) {

			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Width needs to be specified as an integer.');
		}

		if ($width == 0 ||
			$width === NULL) {

			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Width needs to be specified as an integer greater than 0.');
		}

		if ($height &&
			!is_int($height)) {

			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Height needs to be specified as an integer.');
		}

		if ($height == 0 ||
			$height === NULL) {

			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Height needs to be specified as an integer greater than 0.');
		}

		/**
		 * create empty image
		 */
		$resizedImage = imagecreatetruecolor($width, $height);

		/**
		 * define a color as transparent
		 */
		imagecolortransparent($resizedImage, imagecolorallocatealpha($resizedImage, 0, 0, 0, 0));

		/**
		 * copy resized version of original image onto it
		 */
		if (!imagecopyresampled($resizedImage, $this->_image, 0, 0, 0, 0, $width, $height, imagesx($this->_image), imagesy($this->_image))) {
			$this->_errorOccured = TRUE;
		}

		$this->_image = $resizedImage;

		return $this;

	}

	/**
	 * Attempts to rotate the image to the specified degrees.
	 *
	 * @param  int $degrees
	 * @param  int $backgroundColorRed
	 * @param  int $backgroundColorGreen
	 * @param  int $backgroundColorBlue
	 * @param  int $backgroundColorAlpha
	 * @return L8M_Image_Abstract
	 */
	public function rotate($degrees, $backgroundColorRed = 0, $backgroundColorGreen = 0, $backgroundColorBlue = 0, $backgroundColorAlpha = 0)
	{
		if (!is_resource($this->_image)) {
			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Can not rotate an empty image.');
		}

		if (!is_int($degrees)) {
			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Degrees needs to be specified as an integer.');
		}

		/**
		 * rotate image
		 */
		$rotatedImage = imagerotate($this->_image, $degrees, imagecolorallocatealpha($this->_image, $backgroundColorRed, $backgroundColorGreen, $backgroundColorBlue, $backgroundColorAlpha));

		$this->_image = $rotatedImage;

		return $this;
	}

	/**
	 * returns image width
	 */
	public function getWidth()
	{
		return imagesx($this->_image);
	}

	/**
	 * Creates image-resource for a not existing image.
	 *
	 * @param string $fileName
	 * @param integer $width
	 * @param integer $height
	 * @return integer
	 */
	public function createNonExisting($fileName = NULL, $width = 10, $height = 10)
	{
		$image = NULL;
		if ($width > 0 &&
			$height > 0) {

			/**
			 * Create the image
			 */
			$image = imagecreatetruecolor($width, $height);

			/**
			 * Create some colors
			 */
			$white = imagecolorallocate($image, 255, 255, 255);
			$red = imagecolorallocate($image, 204, 0, 0);

			/**
			 * create background
			 */
			imagefill($image, 0, 0, $white);

			/**
			 * create cross
			 */
			imagesetthickness($image, 2);
			imageline($image, 0, 0, $width, $height, $red);
			imageline($image, 0, $height, $width, 0, $red);
		} else {
			throw new L8M_Image_Exception('Image-File has no dimension and can not be loaded.');
		}

		if (is_resource($image)) {
			$this->_image = $image;
			$this->_fileName = $fileName;
		} else {
			$this->_errorOccured = TRUE;
			throw new L8M_Image_Exception('Image-File seems to be corrupt and can not be loaded.');
		}

		return $this;
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Attempts to load the image from a file with the specified filename.
	 * Returns an image resource identifier.
	 *
	 * @param  string $fileName
	 * @return resource
	 */
	abstract protected function _load($fileName = NULL);

	/**
	 * Attempts to save the image with the specified filename, returns TRUE on
	 * success and FALSE on failure.
	 *
	 * @param  string $fileName
	 * @return bool
	 */
	abstract protected function _save($fileName = NULL);

}