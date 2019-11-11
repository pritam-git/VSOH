<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/services/Media.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Media.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Service_Media
 *
 *
 */
class System_Service_Media extends System_Service_Base_Abstract
{
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Attempts to create a Default_Model_Media instance from the specified
	 * Zend_File_Transfer_Adapter_Abstract instance and the specified key.
	 *
	 * @param  Zend_Form_Element_File $element
	 * @param  System_Model_MediaFolder $mediaFolder
	 * @return Default_Model_Media
	 */
	public static function fromFormElementFile($element = NULL, $mediaFolder = NULL)
	{
		if (!($element instanceof Zend_Form_Element_File)) {
			throw new System_Service_Media_Exception('Element needs to be an instance of Zend_Form_Element_File.');
		}

		if ($mediaFolder &&
			!($mediaFolder instanceof System_Model_MediaFolder)) {
			throw new System_Service_Media_Exception('If specified, media folder needs to be a System_Model_MediaFolder instance.');
		}

		/**
		 * an error has occured
		 */
		if (!$element->isUploaded()) {
			return NULL;
		}

		/**
		 * perform upload to temporary directory
		 */
		$element->receive();

		return System_Service_Media::fromFileTransferAdapter($element->getTransferAdapter(), $element->getName(), $mediaFolder);

	}

	/**
	 * Attempts to create a System_Model_Media instance from the specified
	 * Zend_File_Transfer_Adapter_Abstract instance and the specified
	 * identifier. Optionally, a System_Model_MediaFolder instance can be
	 * specified.
	 *
	 * @param  Zend_File_Transfer_Adapter_Abstract $adapter
	 * @param  string $identifier
	 * @param  Default_Model_MediaFolder $mediaFolder
	 * @return Default_Model_Media
	 */
	public static function fromFileTransferAdapter($adapter = NULL, $identifier = NULL, $mediaFolder = NULL)
	{
		if (!$adapter ||
			!($adapter instanceof Zend_File_Transfer_Adapter_Abstract)) {
			throw new System_Service_Media_Exception('Adapter needs to be specified as a Zend_File_Transfer_Adapter_Abstract instance.');
		}
		if (!$identifier ||
			!is_string($identifier)) {
			throw new System_Service_Media_Exception('Identifier needs to be specified as a string.');
		}
		if ($mediaFolder &&
			(!($mediaFolder instanceof System_Model_MediaFolder))) {
			throw new System_Service_Media_Exception('Media folder needs to be specified as a Default_Model_MediaFolder instance.');
		}

		/**
		 * retrieve file info from transfer adapter
		 */
		$fileInfo = $adapter->getFileInfo($identifier);
		$fileInfo = $fileInfo[$identifier];

		/**
		 * prepare data
		 */
		$data = array(
			'file_name'=>$fileInfo['name'],
			'file_size'=>$fileInfo['size'],
			'mime_type'=>$fileInfo['type'],
		);

		if ($data['mime_type'] == L8M_Mime::TYPE_APPLICATION_SHOCKWAVE_FLASH) {
			$media = new System_Model_MediaShockwave();
		}

		/**
		 * image info
		 */
		$imageInfo = self::getImageInfo($fileInfo['tmp_name']);

		if (is_array($imageInfo)) {
			if (!isset($media)) {
				$media = new System_Model_MediaImage();
			}
			$data = array_merge($data, $imageInfo);
		} else {
			$media = new System_Model_MediaFile();
		}

		/**
		 * populate Default_Model_Media instance with data
		 */
		$media->merge($data);
		$media->name = $data['file_name'];

		/**
		 * save
		 */
		try {
			$media->save();
			if (!rename($fileInfo['tmp_name'], $media->getStoredFilePath())) {
				$media->delete();
				throw new System_Service_Media_Exception('Could not move uploaded file to the defined media path.');
			}
			return $media;
		} catch (Doctrine_Exception $exception) {
		}

		return NULL;
	}

	/**
	 * Returns Default_Model_Media instance from request.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return Default_Model_Media
	 */
	public static function fromRequest($request = NULL)
	{
		if ($request === NULL) {
			$request = new Zend_Controller_Request_Http();
		}
		if (!$request instanceof Zend_Controller_Request_Abstract) {
			throw new System_Service_Media_Exception('Request needs to be specified as a Zend_Controller_Request_Abstract instance.');
		}

		/**
		 * media
		 */
		$media = Doctrine_Query::create()
			->from('System_Model_Media m')
			->where('m.id = ?')
			->execute(array())
			->getFirst()
		;

		return $media;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns FALSE when the file at the specified location is not an image,
	 * returns an array with information about the image otherwise
	 *
	 * @param  string $path
	 * @return bool|array
	 */
	public static function getImageInfo($path = NULL)
	{
		if ($path &&
			is_string($path) &&
			file_exists($path) &&
			is_file($path) &&
			(FALSE != $imageSize = getimagesize($path))) {

			if (!isset($imageSize['channels'])) {
				$imageSize['channels'] = NULL;
			}

			return array(
				'width'=>$imageSize[0],
				'height'=>$imageSize[1],
				'mime_type'=>$imageSize['mime'],
				'channels'=>$imageSize['channels'],
			);

		}

		return FALSE;

	}

	/**
	 * Returns FALSE when the file at the specified location is not a file,
	 * returns an array with information about that file otherwise.
	 *
	 * @param  string $path
	 * @return bool|array
	 */
	public static function getFileInfo($path = NULL)
	{
		if ($path &&
			is_string($path) &&
			file_exists($path) &&
			is_file($path)) {

			return array(
				'file_name'=>basename($path),
				'file_size'=>filesize($path),
				'mime_type'=>NULL,
			);

		}

		return FALSE;

	}

}