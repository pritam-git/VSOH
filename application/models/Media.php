<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Media.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Media.php 511 2016-08-29 15:54:24Z nm $
 */

/**
 *
 *
 * Default_Model_Media
 *
 *
 */
class Default_Model_Media extends Default_Model_Base_Media
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * The path which files will be uploaded to, relative to BASE_PATH.
	 */
	const UPLOAD_PATH = 'data/temp/upload';

	/**
	 * The path in which media files will be stored after upload, relative to
	 * BASE_PATH.
	 */
	const MEDIA_PATH = 'data/media';

	/**
	 * The path in which it seems(!) media files are stored.
	 */
	const PUBLIC_MEDIA_PATH = '/media';

	/**
	 * The path in which it seems(!) media short files are stored.
	 */
	const PUBLIC_MEDIASHORT_PATH = '/mediashort';

	/**
	 * The path in which public media files are stored.
	 */
	const PUBLIC_MEDIASHORT_IMG_PATH = '/mediafile';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of field names that are internationalized.
	 *
	 * @var array
	 */
	protected $_i18nFields = array(
		'name',
		'keywords',
		'description',
	);

	/**
	 * An array of models and corresponding file prefixes. The prefix will be
	 * prepended to the id of the instance in the database.
	 *
	 * @var array
	 */
	protected $_filePrefixes = array(
		'Default_Model_MediaFile'=>'f',
		'Default_Model_MediaImage'=>'i',
		'Default_Model_MediaImageInstance'=>'ii',
		'Default_Model_Media'=>'m',
		'Default_Model_Shockwave'=>'s',
	);

	/**
	 * An array of HTML attributes that can be set for the tag returned by
	 * getTag().
	 *
	 * @var array
	 */
	private $_settableHtmlAttributes = array(
		'class',
		'id',
		'style',
		'property',
	);

	/**
	 * An array of HTML attributes that have been set for this instance.
	 *
	 * @var array
	 */
	protected $_htmlAttributes = array(
	);

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Further constructs Default_Model_Media instance.
	 *
	 * @return void
	 */
	public function construct()
	{
		parent::construct();
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns the absolute path in which uploaded files will be stored.
	 *
	 * @return string
	 */
	public static function getUploadPath()
	{
		$uploadPath = BASE_PATH
					. DIRECTORY_SEPARATOR
					. str_replace('/', DIRECTORY_SEPARATOR, self::UPLOAD_PATH)
		;
		return $uploadPath;
	}

	/**
	 * Returns the absolute path in which media files are stored.
	 *
	 * @return string
	 */
	public static function getMediaPath()
	{
		$mediaPath = BASE_PATH
				   . DIRECTORY_SEPARATOR
				   . str_replace('/', DIRECTORY_SEPARATOR, self::MEDIA_PATH)
		;
		return $mediaPath;

	}

	/**
	 * Returns the file name that is used to store this Default_Model_Media
	 * instance in the media folder, which is located at /data/media. If the
	 * Default_Model_File instance has not been saved yet, it will return NULL.
	 *
	 * @return string
	 */
	public function getStoredFileName()
	{
		$class = get_class($this);
		$id = $this->_get('id');
		if ($id &&
			array_key_exists($class, $this->_filePrefixes)) {

			$storedFileName = $this->_filePrefixes[$class]
							. $id
			;
			return $storedFileName;
		}
		return NULL;
	}

	/**
	 * Returns the file path at which the content of this instance is stored. If
	 * the instance has not been saved yet, NULL is returned.
	 *
	 * @return string
	 */
	public function getStoredFilePath()
	{
		$storedFileName = $this->getStoredFileName();
		if (is_string($storedFileName)) {

			$storedFilePath = self::getMediaPath()
							. DIRECTORY_SEPARATOR
							. $storedFileName
			;
			return $storedFilePath;
		}
		return NULL;
	}

	/**
	 * Returns link to the file.
	 *
	 * @todo   models need to implement sluggable behaviour
	 * @param  bool $withBaseUrl
	 * @return string
	 */
	public function getLink($withBaseUrl = FALSE)
	{
		if (isset($this->id)) {

			/**
			 * prepend base url
			 */
			if ($withBaseUrl) {
//				$link = L8M_Library::getSchemeAndHttpHost() . Zend_Controller_Front::getInstance()->getBaseUrl();
				$link = L8M_Library::getSchemeAndHttpHost();
				if (substr($link, -1) == '/') {
					$link = substr($link, 0, strlen($link) - 1);
				}
			} else {
				$link = '';
			}

			if ($this->contains('short') &&
				$this->short !== NULL) {

				/**
				 * maybe short link
				 */
				if ($this->role_id &&
					$this->Role->short == 'guest' &&
					file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile') &&
					is_writable(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile')) {

					$newPublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $this->short;
					$file = $this->getStoredFilePath();
					if (file_exists($newPublicFile)) {
						$link .= self::PUBLIC_MEDIASHORT_IMG_PATH
							  . '/'
							  . $this->short
						;
					} else {
						if (@copy($file, $newPublicFile)) {
							$link .= self::PUBLIC_MEDIASHORT_IMG_PATH
								  . '/'
								  . $this->short
							;
						} else {
							$link .= self::PUBLIC_MEDIASHORT_PATH
								  . '/'
								  . $this->short
							;
						}
					}
				} else {
					$link .= self::PUBLIC_MEDIASHORT_PATH
						  . '/'
						  . $this->short
					;
				}
			} else {

				/**
				 * standard link
				 */
				$link .= self::PUBLIC_MEDIA_PATH
					  . '/'
					  . $this->id
				;
			}
			return $link;
		}
		return NULL;
	}

	/**
	 * Returns an XHTML tag.
	 *
	 * @return string
	 */
	public function getTag()
	{
		$tag = '<a '
			 . 'href="'
			 . $this->getLink()
			 . '"'
			 . $this->_htmlAttributesToString()
			 . '>'
			 . $this->file_name
			 . '('
			 . L8M_Library::getSizeString($this->file_size)
			 . ')'
			 . '</a>'
		;
		return $tag;
	}

	/**
	 * Returns content of a Default_Model_Media instance.
	 *
	 * @return string
	 */
	public function getContent()
	{
		if (isset($this->id)) {
			$fileName = $this->getStoredFilePath();
			/**
			 * @todo throw exceptions if file could not be found?
			 */
			if (file_exists($fileName) &&
				is_readable($fileName)) {
				$content = file_get_contents($fileName);
				if ($content) {
					return $content;
				}
			}
		}
		return NULL;
	}

	/**
	 * Attempts to set an HTML attribute for rendering the associated tag.
	 *
	 * @param  string $attribute
	 * @param  string $value
	 * @return Default_Model_Media
	 */
	public function setHtmlAttribute($attribute = NULL, $value = NULL)
	{
		if (!is_string($attribute)) {
			throw new Default_Model_Media_Exception('Attribute needs to be specified as string.');
		}

		if (!is_string($value)) {
			throw new Default_Model_Media_Exception('Specified attribute is not settable.');
		}

		/**
		 * @todo filter attribute and value (strip out invalid characters)
		 */

		/**
		 * check whether the specified attribute can be set
		 */
		if (!in_array($attribute, $this->_settableHtmlAttributes)) {
			throw new Default_Model_Media_Exception('Specified attribute is not settable.');
		}

		$this->_htmlAttributes[$attribute] = $value;

		return $this;
	}

	/**
	 * Attempts to set the specified attributes.
	 *
	 * @param  array $attributes
	 * @return Default_Model_Media
	 */
	public function setHtmlAttributes($attributes = NULL)
	{
		if (!is_array($attributes)) {
			throw new Default_Model_Media_Exception('Attributes needs to be specified as an array.');
		}

		$this->_htmlAttributes = array();

		/**
		 * iterate over attributes if the attribute array is not empty
		 */
		if (count($attributes)>0) {
			foreach($attributes as $attribute=>$value) {
				$this->setHtmlAttribute($attribute, $value);
			}
		}

		return $this;
	}

	/**
	 * Returns a string with the HTML attributes that have been set.
	 *
	 * @return string
	 */
	protected function _htmlAttributesToString($withoutKeys = array())
	{

		if (count($this->_htmlAttributes) == 0) {
			return '';
		}

		$htmlAttributes = array();

		foreach($this->_htmlAttributes as $attribute=>$value) {
			if (!in_array($attribute, $withoutKeys)) {
				$htmlAttributes[] = $attribute
								  . '="'
								  . $value
								  . '"'
				;
			}
		}

		$htmlAttributes = ' '
						. implode(' ', $htmlAttributes)
		;

		return $htmlAttributes;
	}

	/**
	 * Deletes the Default_Model_Media instance.
	 *
	 * @return boolean
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		/**
		 * save some temporary datas
		 */
		$storedFilePath = $this->getStoredFilePath();
		$lastShort = $this->short;

		/**
		 * delete associated Default_Model_MediaImageInstance instances
		 */
		if ($this instanceof Default_Model_MediaImage) {

			$imageInstances = Doctrine_Query::create()
				->from('Default_Model_MediaImageInstance mii')
				->where('mii.media_image_id = ?', $this->id)
				->execute(array())
			;

			if ($imageInstances->count() > 0) {
				foreach($imageInstances as $imageInstance) {
					/* @var $imageInstance Default_Model_MediaImageInstance */
					$imageInstance->delete($conn);
					if ($imageInstance->getTable()->hasTemplate('SoftDelete')) {
						$imageInstance->hardDelete();
					}
				}
			}
		}

		/**
		 * delete media calling parent function
		 */
		$returnValue = parent::delete($conn);

		if ($returnValue) {

			/**
			 * remove cache
			 */
			if ($this instanceof Default_Model_MediaImage) {
				$this->_removeFromCache();
			}
			if ($this->id &&
				$this->hasRelation('Translation')) {

				$this->_removeTranslationFromCache();
			}
			$this->_removeFieldNameFromCache();

			/**
			 * delete file
			 */
			if ($storedFilePath &&
				file_exists($storedFilePath) &&
				is_file($storedFilePath) &&
				is_writable($storedFilePath)) {

				@unlink($storedFilePath);

				$newPublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $lastShort;
				if (file_exists($newPublicFile) &&
					is_writable($newPublicFile)) {

					@unlink($newPublicFile);
				}
			}
		}

		return $returnValue;
	}

	/**
	 * remove from cache
	 */
	protected function _removeFromCache()
	{
		if ($this->id) {
			if (Zend_Registry::isRegistered('Zend_Cache_Manager') &&
				(NULL != $cacheManager = Zend_Registry::get('Zend_Cache_Manager')) &&
				($cacheManager instanceof Zend_Cache_Manager)) {

				if ($cacheManager->hasCacheTemplate('Default_Model_MediaImageInstance')) {
					$cache = $cacheManager->getCache('Default_Model_MediaImageInstance');
					$cacheValue = $cache->remove(Default_Service_MediaImage::getCacheName($this->id));
				}
			}
		}
	}

	/**
	 *
	 *
	 * Magic Methods
	 *
	 *
	 */

	/**
	 * Returns an XHTML tag.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getTag();
	}

	public function createThumbnailFromFirstPageOfPdf()
	{
		try {
			$filePath = $this->getStoredFilePath();
			if (!file_exists($filePath)) {
				throw new Exception('file is not exists.');
			}

			if ($this->mime_type != 'application/pdf' &&
				strpos($this->mime_type, 'application/pdf') === FALSE
			) {

				throw new Exception('file type is not pdf.');
			}

			$fileName = $this->getStoredFileName();
			$tempFilePath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $fileName . '.png';

			$image = new Imagick();

			//set image resolution (ppi)
			$image->setResolution(300, 300);

			//read the pdf
			$image->readImage("{$filePath}[0]");
			//set format for new image
			$image->setImageFormat('png');

			//set size of image
			$image->scaleImage(300, 300);

			//set white background
			$image->setImageBackgroundColor('white');
			$image->setImageAlphaChannel(11); // Imagick::ALPHACHANNEL_REMOVE
			$image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

			//set image unit for apply the resolution.
			//if image unite is missing the default resolution is applied (72 X 72)
			imagick::RESOLUTION_PIXELSPERINCH;
			$image->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

			//save image file
			$image->writeImage($tempFilePath);

			return array('isError'=>FALSE,'data'=>$tempFilePath);
		} catch (Exception $e){
			return array('isError'=>TRUE,'message'=>$e->getMessage());
		}
	}
}