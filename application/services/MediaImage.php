<?php

/**
 * L8M
 *
 *
 * @filesource /application/services/MediaImage.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaImage.php 533 2017-07-21 15:37:49Z nm $
 */

/**
 *
 *
 * Default_Service_MediaImage
 *
 *
 */
class Default_Service_MediaImage extends Default_Service_Base_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of HTML attributes that can be set for the tag returned by
	 * getTag().
	 *
	 * @var array
	 */
	protected $_settableHtmlAttributes = array(
		'id',
		'class',
		'style',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a Default_Model_MediaImage from the specified
	 * Default_Model_MediaImage, croped so it is specified by the coordinates and dimensions.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @param int $coordinateX
	 * @param int $coordinateY
	 * @param int $width
	 * @param int $height
	 * @return Default_Model_MediaImage
	 */
	public static function crop($image = NULL, $coordinateX = NULL, $coordinateY = NULL, $width = NULL, $height = NULL)
	{

		if (!($image instanceof Default_Model_MediaImage)) {
			throw new Default_Model_MediaImage_Exception('Image needs to be an instance of Default_Model_MediaImage.');
		}

		if ($coordinateX &&
			!is_int($coordinateX)) {

			throw new Default_Model_MediaImage_Exception('Coordinate X needs to be an integer, if specified.');
		}

		if ($coordinateY &&
			!is_int($coordinateY)) {

			throw new Default_Model_MediaImage_Exception('Coordinate Y needs to be an integer, if specified.');
		}

		if ($width &&
			!is_int($width)) {

			throw new Default_Model_MediaImage_Exception('Width needs to be an integer, if specified.');
		}

		if ($height &&
			!is_int($height)) {

			throw new Default_Model_MediaImage_Exception('Height needs to be an integer, if specified.');
		}

		$cropedImage = Default_Service_MediaImage::fromMediaImage($image);
		$cropedImage->width = 0;
		$cropedImage->height = 0;
		$cropedImage->file_size = 0;
		$cropedImage->role_id = $image->role_id;
		if (isset($image['entity_id'])) {
			$cropedImage->entity_id = $image->entity_id;
		} else
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$cropedImage->entity_id = Zend_Auth::getInstance()->getIdentity()->id;
		}
		$cropedImage->short = Default_Service_Media::getShort($image->short, $cropedImage->width, $cropedImage->height, $cropedImage->file_size);
		$cropedImage->save();


		/**
		 * imageinstance file does not exist yet and needs to be created
		 */
		if (!file_exists($cropedImage->getStoredFilePath())) {

			/**
			 * use Imagick for croping the image ?
			 */
			if (Zend_Registry::get('Imagick')) {

				/**
				 * Imagick
				 */
				$imagickError = NULL;
				try {
					$imagickObj = new Imagick($image->getStoredFilePath());
					$imagickCropResult = $imagickObj->cropImage($width, $height, $coordinateX, $coordinateY);
					if ($imagickCropResult) {
						$imagickObj->writeimage($cropedImage->getStoredFilePath());

						/**
						 * retrieve image infos
						 */
						$cropedImage->file_size = $imagickObj->getImageLength();
						$imagickDimensions = $imagickObj->getImageGeometry();
						$cropedImage->width = $imagickDimensions['width'];
						$cropedImage->height = $imagickDimensions['height'];
						$cropedImage->short = Default_Service_Media::getShort($image->short, $cropedImage->width, $cropedImage->height, $cropedImage->file_size);
						$cropedImage->save();
					}
				} catch (ImagickException $imagickError) {

				}

				if ($imagickError ||
					!$imagickCropResult) {

					$cropedImage->hardDelete();
				}

			} else {
				$imageFile = L8M_Image::fromFile($image->getStoredFilePath());

				if ($imageFile instanceof L8M_Image_Abstract) {

					$imageFile
						->crop($coordinateX, $coordinateY, $width, $height)
						->save($cropedImage->getStoredFilePath(), TRUE)
					;

					/**
					 * retrieve image infos
					 */
					$cropedImage->file_size = $imageFile->getFilesize();
					$cropedImage->width = $imageFile->getWidth();
					$cropedImage->height =  $imageFile->getHeight();
					$cropedImage->short = Default_Service_Media::getShort($image->short, $cropedImage->width, $cropedImage->height, $cropedImage->file_size);

					/**
					 * save
					 */
					if (!$imageFile->isErrorOccured()) {
						$cropedImage->save();
					} else {
						$cropedImage->hardDelete();
					}

				}
			}

		}

		return $cropedImage;
	}

	/**
	 * Attempts to create and returns a Default_Model_MediaImage
	 * instance from the specified Default_Model_MediaImage instance.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @return Default_Model_MediaImage
	 */
	public static function fromMediaImage($image = NULL)
	{
		if (!$image) {
			return NULL;
		}

		if (!($image instanceof Default_Model_MediaImage)) {
			throw new Default_Service_MediaImage_Exception('Image needs to be specified as a Default_Model_MediaImage instance.');
		}

		/**
		 * start image copy
		 */
		$imageCopy = new Default_Model_MediaImage();

		/**
		 * merge data
		 */
		$imageCopy->merge(array(
			'file_name'=>$image->file_name,
			'file_size'=>$image->file_size,
			'media_folder_id'=>$image->media_folder_id,
			'mime_type'=>$image->mime_type,
			'width'=>$image->width,
			'height'=>$image->height,
			'channels'=>$image->channels,
		));

		return $imageCopy;

	}

	/**
	 * Returns a Default_Model_MediaImageInstance from the specified
	 * Default_Model_MediaImage instance, resized so it would fit in a box with
	 * the specified maximum width and height, while maintaining aspect ratio.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @param  int                      $width
	 * @param  int                      $height
	 * @param  bool                     $enlarge
	 * @return Default_Model_MediaImageInstance
	 */
	public static function maxBox($image = NULL, $width = NULL, $height = NULL, $enlarge = FALSE)
	{

		if (!($image instanceof Default_Model_MediaImage)) {
			throw new Default_Model_MediaImage_Exception('Image needs to be an instance of Default_Model_MediaImage.');
		}

		if ($width &&
			!is_int($width)) {

			throw new Default_Model_MediaImage_Exception('Width needs to be an integer, if specified.');
		}

		if ($height &&
			!is_int($height)) {

			throw new Default_Model_MediaImage_Exception('Height needs to be an integer, if specified.');
		}

		$enlarge = (bool) $enlarge;

		/**
		 * retrieve old width and height of image
		 */
		$oldWidth = $image['width'];
		$oldHeight = $image['height'];

		/**
		 * check for null division
		 */
		if ($oldWidth == NULL ||
			$oldHeight == NULL) {

			throw new Default_Model_MediaImage_Exception('There is no Default_Model_MediaImage specified.');
		}

		/**
		 * calculate the new width and height maintaining aspect rotatio
		 */
		/**
		 * calculate with missing height or width
		 */
		if ($width == 0 ||
			$height == 0) {

			if ($width == 0) {

				/**
				 * calculate on missing width
				 */
				/**
				 * is image height < new height
				 */
				if ($oldHeight < $height) {

					/**
					 * should we enlarge the image?
					 */
					if ($enlarge) {
						$newWidth = intval($oldWidth * ($height / $oldHeight));
						$newHeight = intval($height);
					} else {
						$newWidth = intval($oldWidth);
						$newHeight = intval($oldHeight);
					}
				} else {
					$newWidth = intval($oldWidth * ($height / $oldHeight));
					$newHeight = intval($height);
				}
			} else
			if ($height == 0) {

				/**
				 * calculate on missing height
				 */
				/**
				 * is image width < new width
				 */
				if ($oldWidth < $width) {

					/**
					 * should we enlarge the image?
					 */
					if ($enlarge) {
						$newWidth = intval($width);
						$newHeight = intval($oldHeight * ($width / $oldWidth));
					} else {
						$newWidth = intval($oldWidth);
						$newHeight = intval($oldHeight);
					}
				} else {
					$newWidth = intval($width);
					$newHeight = intval($oldHeight * ($width / $oldWidth));
				}
			}
		} else

		/**
		 * quotient calculation result: height > width
		 */
		if ($oldWidth / $oldHeight < $width / $height) {

			/**
			 * is image height < new height
			 */
			if ($oldHeight < $height) {

				/**
				 * should we enlarge the image?
				 */
				if ($enlarge) {

					/**
					 * enlarge
					 */
					$newWidth = intval($oldWidth * ($height / $oldHeight));
					$newHeight = intval($height);
				} else {

					/**
					 * keep it small as it is
					 */
					$newWidth = intval($oldWidth);
					$newHeight = intval($oldHeight);
				}
			} else {

				/**
				 * resize image with aspect rotatio
				 */
				$newWidth = intval($oldWidth * ($height / $oldHeight));
				$newHeight = intval($height);
			}
		} else

		/**
		 * quotient calculation result: width > height
		 */
		if ($oldWidth / $oldHeight > $width / $height) {

			/**
			 * is image width < new width
			 */
			if ($oldWidth < $width) {

				/**
				 * should we enlarge the image?
				 */
				if ($enlarge) {

					/**
					 * enlarge
					 */
					$newWidth = intval($width);
					$newHeight = intval($oldHeight * ($width / $oldWidth));
				} else {

					/**
					 * keep it as small as it is
					 */
					$newWidth = intval($oldWidth);
					$newHeight = intval($oldHeight);
				}
			} else {

				/**
				 * resize image with aspect rotatio
				 */
				$newWidth = intval($width);
				$newHeight = intval($oldHeight * ($width / $oldWidth));
			}
		} else

		/**
		 * quotient calculation result: width and height in aspect rotatio
		 */
		if ($oldWidth / $oldHeight == $width / $height) {

			/**
			 * is image smaller then the new one?
			 */
			if ($oldWidth < $width ||
				$oldHeight < $height) {

				/**
				 * should we enlarge the image
				 */
				if ($enlarge) {

					/**
					 * enlarge
					 */
					$newWidth = intval($width);
					$newHeight = intval($height);
				} else {

					/**
					 * keep it as small as it is
					 */
					$newWidth = intval($oldWidth);
					$newHeight = intval($oldHeight);
				}
			} else {

				/**
				 * resize image
				 */
				$newWidth = intval($width);
				$newHeight = intval($height);
			}
		} else {
			throw new Default_Model_MediaImage_Exception('Unexpected failure. Something went wrong in calculation.');
		}

		/**
		 * make sure, there is at least 1 pixel
		 */
		if ($newWidth == 0) {
			$newWidth = 1;
		}
		if ($newHeight == 0) {
			$newHeight = 1;
		}

		return self::_resize($image, $newWidth, $newHeight);
	}

	/**
	 * Returns a Default_Model_MediaImageInstance from the specified
	 * Default_Model_MediaImage instance, resized so it would fit in a box with
	 * the specified maximum width and height.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @param  int                      $width
	 * @param  int                      $height
	 * @return Default_Model_MediaImageInstance
	 */
	public static function resize($image = NULL, $width = NULL, $height = NULL)
	{

		if (!($image instanceof Default_Model_MediaImage)) {
			throw new Default_Model_MediaImage_Exception('Image needs to be an instance of Default_Model_MediaImage.');
		}

		if ($width &&
			!is_int($width)) {

			throw new Default_Model_MediaImage_Exception('Width needs to be an integer, if specified.');
		}

		if ($height &&
			!is_int($height)) {

			throw new Default_Model_MediaImage_Exception('Height needs to be an integer, if specified.');
		}

		return self::_resize($image, $width, $height);
	}

	/**
	 * Returns a Default_Model_MediaImageInstance from the specified
	 * Default_Model_MediaImage instance, rotated so it is specified by the degrees.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @param  int                      $degrees
	 * @param  int                      $backgroundColorRed
	 * @param  int                      $backgroundColorGreen
	 * @param  int                      $backgroundColorBlue
	 * @param  int                      $backgroundColorAlpha
	 * @return Default_Model_MediaImage
	 */
	public static function rotate($image = NULL, $degrees = NULL, $backgroundColorRed = 0, $backgroundColorGreen = 0, $backgroundColorBlue = 0, $backgroundColorAlpha = 0)
	{

		if (!($image instanceof Default_Model_MediaImage)) {
			throw new Default_Model_MediaImage_Exception('Image needs to be an instance of Default_Model_MediaImage.');
		}

		if ($degrees &&
			!is_int($degrees)) {

			throw new Default_Model_MediaImage_Exception('Degrees needs to be an integer, if specified.');
		}

		if (!is_int($backgroundColorRed) &&
			($backgroundColorRed < 0 || $backgroundColorRed > 255)) {

			throw new Default_Model_MediaImage_Exception('BackgroundColor Red needs to be specified between 0 and 255.');
		}

		if (!is_int($backgroundColorGreen) &&
			($backgroundColorGreen < 0 || $backgroundColorGreen > 255)) {

			throw new Default_Model_MediaImage_Exception('BackgroundColor Green needs to be specified between 0 and 255.');
		}

		if (!is_int($backgroundColorBlue) &&
			($backgroundColorBlue < 0 || $backgroundColorBlue > 255)) {

			throw new Default_Model_MediaImage_Exception('BackgroundColor Blue needs to be specified between 0 and 255.');
		}

		if (!is_int($backgroundColorAlpha) &&
			($backgroundColorAlpha < 0 || $backgroundColorAlpha > 255)) {

			throw new Default_Model_MediaImage_Exception('BackgroundColor Alpha needs to be specified between 0 and 255.');
		}

		$rotatedImage = Default_Service_MediaImage::fromMediaImage($image);
		$rotatedImage->width = 0;
		$rotatedImage->height = 0;
		$rotatedImage->file_size = 0;
		$rotatedImage->role_id = $image->role_id;
		if (isset($image['entity_id'])) {
			$rotatedImage->entity_id = $image->entity_id;
		} else
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$rotatedImage->entity_id = Zend_Auth::getInstance()->getIdentity()->id;
		}
		$rotatedImage->short = Default_Service_Media::getShort($image->short, $rotatedImage->width, $rotatedImage->height, $rotatedImage->file_size);
		$rotatedImage->save();


		/**
		 * imageinstance file does not exist yet and needs to be created
		 */
		if (!file_exists($rotatedImage->getStoredFilePath())) {

			/**
			 * use Imagick for rotating the image ?
			 */
			if (Zend_Registry::get('Imagick')) {

				/**
				 * Imagick
				 */
				$imagickError = NULL;
				try {
					$colorStringFormat = '%1$02s%2$02s%3$02s%4$02s';
					$colorString = sprintf($colorStringFormat, dechex($backgroundColorRed), dechex($backgroundColorGreen), dechex($backgroundColorBlue), dechex($backgroundColorAlpha));
					$imagickBackgoundColor = new ImagickPixel('#' . $colorString);

					$imagickObj = new Imagick($image->getStoredFilePath());
					$imagickRotateResult = $imagickObj->rotateImage($imagickBackgoundColor, $degrees);
					if ($imagickRotateResult) {
						$imagickObj->writeimage($rotatedImage->getStoredFilePath());

						/**
						 * retrieve image infos
						 */
						$rotatedImage->file_size = $imagickObj->getImageLength();
						$imagickDimensions = $imagickObj->getImageGeometry();
						$rotatedImage->width = $imagickDimensions['width'];
						$rotatedImage->height = $imagickDimensions['height'];
						$rotatedImage->short = Default_Service_Media::getShort($image->short, $rotatedImage->width, $rotatedImage->height, $rotatedImage->file_size);
						$rotatedImage->save();
					}
				} catch (ImagickException $imagickError) {

				}

				if ($imagickError ||
					!$imagickRotateResult) {

					$rotatedImage->hardDelete();
				}

			} else {
				$imageFile = L8M_Image::fromFile($image->getStoredFilePath());

				if ($imageFile instanceof L8M_Image_Abstract) {

					$imageFile
						->rotate($degrees, $backgroundColorRed, $backgroundColorGreen, $backgroundColorBlue, $backgroundColorAlpha)
						->save($rotatedImage->getStoredFilePath(), TRUE)
					;

					/**
					 * retrieve image infos
					 */
					$rotatedImage->file_size = $imageFile->getFilesize();
					$rotatedImage->width = $imageFile->getWidth();
					$rotatedImage->height =  $imageFile->getHeight();
					$rotatedImage->short = Default_Service_Media::getShort($image->short, $rotatedImage->width, $rotatedImage->height, $rotatedImage->file_size);

					/**
					 * save
					 */
					if (!$imageFile->isErrorOccured()) {
						$rotatedImage->save();
					} else {
						$rotatedImage->hardDelete();
					}

				}
			}

		}

		return $rotatedImage;
	}

	/**
	 * Create an error MediaImageInstance.
	 *
	 * @param Default_Model_MediaImage $mediaImageModel
	 * @param integer $newWidth
	 * @param integer $newHeight
	 * @return Default_Model_MediaImageInstance
	 */
	protected static function _createFailureMediaImageInstance($mediaImageModel, $newWidth, $newHeight)
	{

		$imageInstance = Default_Service_MediaImageInstance::fromMediaImage($mediaImageModel);
		$imageInstance->width = $newWidth;
		$imageInstance->height = $newHeight;
		$imageInstance->file_size = 0;
		$imageInstance->role_id = $mediaImageModel->role_id;
		$imageInstance->mime_type = 'image/png';
		if (isset($mediaImageModel['entity_id'])) {
			$imageInstance->entity_id = $mediaImageModel->entity_id;
		} else
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$imageInstance->entity_id = Zend_Auth::getInstance()->getIdentity()->id;
		}
		$imageInstance->short = Default_Service_Media::getShort($mediaImageModel->short, $imageInstance->width, $imageInstance->height, $imageInstance->file_size);
		$imageInstance->save();

		$fileName = $imageInstance->getStoredFilePath();

		/**
		 * media could not be retrieved
		 */
		if (!file_exists($fileName)) {

			if (!is_writable(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' )) {
				throw new L8M_Exception('Not writeable: ' . $fileName);
			}

			/**
			 * Create the image
			 */
			$im = imagecreatetruecolor(130, 30);

			/**
			 * Create some colors
			*/
			$white = imagecolorallocate($im, 255, 255, 255);
			$black = imagecolorallocate($im, 0, 0, 0);

			/**
			 * create background
			*/
			imagefilledrectangle($im, 0, 0, 139, 29, $white);

			/**
			 * The text to draw
			*/
			$text = 'Image-Failure';

			/**
			 * Add the text
			 */
			imagestring($im, 1, 10, 10, $text, $black);

			/**
			 * save image using imagepng()
			*/
			imagepng($im, $fileName);

			imagedestroy($im);

			$imageInstance->file_size = filesize($fileName);
			$imageInstance->save();
		}

		return $imageInstance;
	}

	protected static function _getImageInstance($imageID = NULL, $width = NULL, $height = NULL)
	{
		$imageInstance = self::_getImageInstanceFromCache($imageID, $width, $height);

		if (!$imageInstance) {
			$imageInstance = Doctrine_Query::create()
				->from('Default_Model_MediaImageInstance mii')
				->addWhere('mii.media_image_id = ? ', $imageID)
				->addWhere('mii.width = ? ', $width)
				->addWhere('mii.height = ? ', $height)
				->execute(array())
				->getFirst()
			;

			self::_setImageInstanceToCache($imageInstance, $imageID, $width, $height);
		}

		return $imageInstance;
	}

	/**
	 * Creates an cach short
	 *
	 * @param string $short
	 * @return string
	 */
	public static function getCacheName($imageID = NULL)
	{
		$returnValue = NULL;

		if ($imageID) {
			$returnValue = strlen($imageID) . '_' . md5($imageID);
		}

		return $returnValue;
	}

	protected static function _getImageInstanceFromCache($imageID = NULL, $width = NULL, $height = NULL)
	{
		$returnValue = FALSE;

		if ($imageID &&
			$width &&
			$height &&
			$cache = L8M_Cache::getCache('Default_Model_MediaImageInstance')) {

			$cacheValue = $cache->load(self::getCacheName($imageID));
			if (is_array($cacheValue) &&
				isset($cacheValue[$width]) &&
				is_array($cacheValue[$width]) &&
				isset($cacheValue[$width][$height])) {

				$returnValue = $cacheValue[$width][$height];
			}
		}
		return $returnValue;
	}

	protected static function _setImageInstanceToCache($imageInstance = NULL, $imageID = NULL, $width = NULL, $height = NULL)
	{
		$returnValue = FALSE;

		if ($imageInstance &&
			$imageID &&
			$width &&
			$height &&
			$imageInstance instanceof Default_Model_MediaImageInstance &&
			$cache = L8M_Cache::getCache('Default_Model_MediaImageInstance')) {

			$cacheValue = $cache->load(self::getCacheName($imageID));
			if (is_array($cacheValue) &&
				isset($cacheValue[$width]) &&
				is_array($cacheValue[$width])) {

				$cacheValue[$width][$height] = $imageInstance;
			} else {
				$cacheValue = array();
				$cacheValue[$width] = array($height=> $imageInstance);
			}

			$cache->save($cacheValue, self::getCacheName($imageID));
		}
	}

	/**
	 * Returns a Default_Model_MediaImageInstance from the specified
	 * Default_Model_MediaImage instance, resized so it would fit in a box with
	 * the specified maximum width and height.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @param  int                      $newWidth
	 * @param  int                      $newHeight
	 * @return Default_Model_MediaImageInstance
	 */
	protected static function _resize($image, $newWidth, $newHeight)
	{

		/**
		 * check if image is in the right size
		 */
		if ($image->width == $newWidth &&
			$image->height == $newHeight) {

			$imageInstance = $image;
		} else {

			/**
			 * memory check
			 */
			$memoryLimit = L8M_Library::getPhpMemoryLimit();
			if (file_exists($image->getStoredFilePath())) {
				$imageInfo = @getimagesize($image->getStoredFilePath());
			} else {
				$imageInfo = array();
			}

			$imageBits = 1;
			if (isset($imageInfo['bits'])) {
				$imageBits = $imageInfo['bits'];
			}

			$imageChannels = 1;
			if ($image->channels) {
				$imageChannels = $image->channels;
			}

			$tweakFactor = 10.06;
			$memoryImageOld = round((($imageChannels + 1) * $image->width * $image->height * $tweakFactor) / $imageBits);
			$memoryImageNew = round((($imageChannels + 1) * $newWidth * $newHeight * $tweakFactor) / $imageBits);

			$memoryUsed = memory_get_usage();

			$memoryPossibleAfter = $memoryLimit - $memoryUsed - $memoryImageOld - $memoryImageNew;

			$doResizeInReal = TRUE;
			if ($memoryPossibleAfter < 0) {
				$doResizeInReal = FALSE;
			}

			/**
			 * check if we already have an image instance
			 */
			$imageInstance = self::_getImageInstance($image->id, $newWidth, $newHeight);

			/**
			 * an image instance could not be retrieved, let's create a new one and
			 * save it
			 */
			if (!($imageInstance instanceof Default_Model_MediaImageInstance)) {

				$imageInstance = Default_Service_MediaImageInstance::fromMediaImage($image);
				$imageInstance->width = $newWidth;
				$imageInstance->height = $newHeight;
				$imageInstance->file_size = 0;
				$imageInstance->role_id = $image->role_id;
				if (isset($image['entity_id'])) {
					$imageInstance->entity_id = $image->entity_id;
				} else
				if (Zend_Auth::getInstance()->hasIdentity()) {
					$imageInstance->entity_id = Zend_Auth::getInstance()->getIdentity()->id;
				}
				$imageInstance->short = Default_Service_Media::getShort($image->short, $imageInstance->width, $imageInstance->height, $imageInstance->file_size);
				$imageInstance->save();
			}

			/**
			 * imageinstance file does not exist yet and needs to be created
			 */
			if (!file_exists($imageInstance->getStoredFilePath())) {
				if ($doResizeInReal ||
					Zend_Registry::get('Imagick')) {

					/**
					 * use Imagick for resizeing the image ?
					 */
					if (Zend_Registry::get('Imagick')) {

						/**
						 * Imagick
						 */
						$imagickError = NULL;
						try {
							$imagickObj = new Imagick($image->getStoredFilePath());
							$imagickObj->setimagecolorspace(Imagick::COLORSPACE_TRANSPARENT);
							$imagickObj->scaleImage($newWidth, $newHeight);
							$imagickObj->writeimage($imageInstance->getStoredFilePath());

							/**
							 * retrieve image infos
							 */
							$imageInstance->file_size = $imagickObj->getImageLength();
							$imagickDimensions = $imagickObj->getImageGeometry();
							$imageInstance->width = $imagickDimensions['width'];
							$imageInstance->height = $imagickDimensions['height'];
							$imageInstance->short = Default_Service_Media::getShort($image->short, $imageInstance->width, $imageInstance->height, $imageInstance->file_size);
							$imageInstance->save();
						} catch (ImagickException $imagickError) {
							$imageInstance->hardDelete();
						}
					} else {

						/**
						 * php intern-functions
						 */
						$imageFile = L8M_Image::fromFile($image->getStoredFilePath());

						if ($imageFile instanceof L8M_Image_Abstract) {

							$imageFile
								->resize($newWidth, $newHeight)
								->save($imageInstance->getStoredFilePath(), TRUE)
							;

							/**
							 * retrieve image infos
							 */
							$imageInstance->file_size = $imageFile->getFilesize();
							$imageInstance->width = $imageFile->getWidth();
							$imageInstance->height = $imageFile->getHeight();
							$imageInstance->short = Default_Service_Media::getShort($image->short, $imageInstance->width, $imageInstance->height, $imageInstance->file_size);

							/**
							 * save
							 */
							if (!$imageFile->isErrorOccured()) {
								$imageInstance->save();
							} else {
								$imageInstance->hardDelete();
							}

						}
					}

				} else
				if (!$doResizeInReal) {

					$imageInstance->file_size = $image->file_size;
					$imageInstance->width = $newWidth;
					$imageInstance->height = $newHeight;
					$imageInstance->short = Default_Service_Media::getShort($image->short, $newWidth, $newHeight, $image->file_size);

					if (file_exists($image->getStoredFilePath()) &&
						@copy($image->getStoredFilePath(), $imageInstance->getStoredFilePath())) {

						$imageInstance->save();
					} else {
						$imageInstance->hardDelete();
					}
				}
			}
		}

		/**
		 * fallback last chance to prevent failure
		 */
		if (!$imageInstance ||
			$imageInstance->deleted_at) {

			$imageInstance = self::_createFailureMediaImageInstance($image, $newWidth, $newHeight);
		}

		return $imageInstance;
	}
}