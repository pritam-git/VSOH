<?php

/**
 * L8M
 *
 *
 * @filesource /application/services/Media.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Media.php 534 2017-07-28 12:53:47Z nm $
 */

/**
 *
 *
 * Default_Service_Media
 *
 *
 */
class Default_Service_Media extends Default_Service_Base_Abstract
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
	 * @param  Default_Model_MediaFolder $mediaFolder
	 * @param  Default_Model_Role $roleModel
	 * @param  Default_Model_Entity $entityModel
	 * @return Default_Model_Media
	 */
	public static function fromFormElementFile($element = NULL, $mediaFolder = NULL, $roleModel = NULL, $entityModel = NULL)
	{
		if (!($element instanceof Zend_Form_Element_File)) {
			throw new Default_Service_Media_Exception('Element needs to be an instance of Zend_Form_Element_File.');
		}

		if ($mediaFolder &&
			!($mediaFolder instanceof Default_Model_MediaFolder)) {
			throw new Default_Service_Media_Exception('If specified, media folder needs to be a Default_Model_MediaFolder instance.');
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

		return Default_Service_Media::fromFileTransferAdapter(
			$element->getTransferAdapter(),
			$element->getName(),
			$mediaFolder,
			$roleModel,
			$entityModel
		);

	}

	/**
	 * Attempts to create a Default_Model_Media instance from the specified file and returns mediaFile ID
	 *
	 * @param String $file
	 * @param String|Default_Modul_Role $role
	 * @param String|Default_Model_MediaFolder $mediaFolder
	 * @param Default_Model_Entity $entityModel
	 * @return Integer
	 */
	public static function fromFileToMediaID($file = NULL, $roleShort = 'guest', $mediaFolder = NULL, $entityModel = NULL)
	{
		$mediaID = NULL;

		if (file_exists($file) &&
			is_file($file)) {

			if (!($roleShort instanceof Default_Model_Role)) {
				$defaultRole = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->select('r.id, r.short')
					->where('r.short = ? ', array($roleShort))
					->limit(1)
					->execute()
					->getFirst()
				;
				if (!$defaultRole) {
					throw new L8M_Exception('Can\'t figure out role.');
				}
			} else {
				$defaultRole = $roleShort;
			}

			if ($mediaFolder != NULL &&
				!($mediaFolder instanceof Default_Model_MediaFolder)) {

				if (is_string($mediaFolder)) {
					$mediaFolder = Default_Service_MediaFolder::getMediaFolderModelFromPath($mediaFolder);
				} else {
					$mediaFolder = NULL;
				}
			}

			$media = Default_Service_Media::fromFile($file, $mediaFolder, $defaultRole, $entityModel);
			if ($media) {
				$mediaID = $media->id;
				$media->free(TRUE);
			}
		}

		return $mediaID;
	}

	/**
	 * Attempts to create a Default_Model_Media instance from the specified url and returns mediaFile ID
	 *
	 * @param String $url
	 * @param String|Default_Modul_Role $role
	 * @param String|Default_Model_MediaFolder $mediaFolder
	 * @param Default_Model_Entity $entityModel
	 * @return Integer
	 */
	public static function fromUrlToMediaID($url = NULL, $roleShort = 'guest', $mediaFolder = NULL, $entityModel = NULL)
	{
		$mediaID = NULL;

		if (in_array('curl', get_loaded_extensions())) {

			if (!($roleShort instanceof Default_Model_Role)) {
				$defaultRole = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->select('r.id, r.short')
					->where('r.short = ? ', array($roleShort))
					->limit(1)
					->execute()
					->getFirst()
				;
				if (!$defaultRole) {
					throw new L8M_Exception('Can\'t figure out role.');
				}
			} else {
				$defaultRole = $roleShort;
			}

			if ($mediaFolder != NULL &&
				!($mediaFolder instanceof Default_Model_MediaFolder)) {

				if (is_string($mediaFolder)) {
					$mediaFolder = Default_Service_MediaFolder::getMediaFolderModelFromPath($mediaFolder);
				} else {
					$mediaFolder = NULL;
				}
			}

			$media = Default_Service_Media::fromUrl($url, $mediaFolder, $defaultRole, $entityModel);
			if ($media) {
				$mediaID = $media->id;
				$media->free(TRUE);
			}
		}

		return $mediaID;
	}

	/**
	 * Attempts to create a Default_Model_Media instance from the specified file
	 *
	 * @param  String $file
	 * @param  String|Default_Model_MediaFolder $mediaFolder
	 * @param  Default_Model_Role $roleModel
	 * @param  Default_Model_Entity $entityModel
	 * @return Default_Model_Media
	 */
	public static function fromFile($file = NULL, $mediaFolder = NULL, $roleModel = NULL, $entityModel = NULL)
	{
		if ($mediaFolder != NULL &&
			!($mediaFolder instanceof Default_Model_MediaFolder)) {

			if (is_string($mediaFolder)) {
				$mediaFolder = Default_Service_MediaFolder::getMediaFolderModelFromPath($mediaFolder);
			} else {
				$mediaFolder = NULL;
			}
		}

		if ($file &&
			!file_exists($file)) {

			throw new Default_Service_Media_Exception('File (' . $file . ') does not exist.');
		}

		if (class_exists('finfo')) {
			$fi = new finfo(FILEINFO_MIME);
			$mime_type = $fi->buffer(file_get_contents($file));
		} else {
			$mime_type = NULL;
		}

		$fileInfo = array(
			'name'=>basename($file),
			'size'=>filesize($file),
			'mime_type'=>$mime_type,
			'tmp_name'=>$file,
		);

		/**
		 * retrieve user infos and role
		 */
		if (!($entityModel instanceof Default_Model_Entity)) {
			$entityModel = Zend_Auth::getInstance()->getIdentity();
		}
		if (!($roleModel instanceof Default_Model_Role)) {
			if ($entityModel) {
				$roleID = $entityModel->Role->id;
			} else {

				/**
				 * retrieve the default admin role
				 */
				$defaultRole = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->select('r.id, r.short')
					->where('r.short = ? ', array('admin'))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($defaultRole) {
					$roleID = $defaultRole['id'];
				} else {
					throw new Default_Service_Media_Exception('Could not retrieve role.');
				}
			}
		} else {
			$roleID = $roleModel['id'];
		}

		$entityModelID = NULL;
		if ($entityModel) {
			$entityModelID = $entityModel->id;
		}

		if (!$entityModelID) {
			/**
			 * retrieve the default admin entity
			 */
			$defaultAdmin = Doctrine_Query::create()
				->from('Default_Model_EntityAdmin m')
				->limit(1)
				->execute()
				->getFirst()
			;

			if (!$defaultAdmin) {
				throw new Default_Service_Media_Exception('Could not retrieve an entity.');
			} else {
				$entityModelID = $defaultAdmin->id;
			}
		}

		/**
		 * prepare data
		 */
		$data = array(
			'file_name'=>$fileInfo['name'],
			'file_size'=>$fileInfo['size'],
			'mime_type'=>$fileInfo['mime_type'],
			'role_id'=>$roleID,
			'entity_id'=>$entityModelID,
			'short'=>self::getShort($fileInfo['name'], NULL, NULL, $fileInfo['size']),
		);

		if ($data['mime_type'] == L8M_Mime::TYPE_APPLICATION_SHOCKWAVE_FLASH) {
			$media = new Default_Model_MediaShockwave();
		}

		/**
		 * image info
		 */
		$imageInfo = self::getImageInfo($fileInfo['tmp_name']);

		if (is_array($imageInfo)) {
			if (!isset($media)) {
				$media = new Default_Model_MediaImage();
			}
			$data = array_merge($data, $imageInfo);
		} else {
			$media = new Default_Model_MediaFile();
		}

		/**
		 * populate Default_Model_Media instance with data
		 */
		$media->merge($data);

		/**
		 * create pseudo-name if name does not exists
		 */
		if (!$media->name) {
			$pseudoName = explode('.', $data['file_name']);
			if (count($pseudoName) > 1) {
				unset($pseudoName[count($pseudoName) - 1]);
			}
			$pseudoName = implode('.', $pseudoName);

			foreach (L8M_Locale::getSupported() as $lang) {
				$media['Translation'][$lang]['name'] = $pseudoName;
			}
		}

		/**
		 * media folder
		 */
		if ($mediaFolder) {
			$media->MediaFolder = $mediaFolder;
		}

		/**
		 * save
		 */
		try {
			$media->save();
			/**
			 * @todo consider that renaming fails if the specified file already
			 *	   exists
			 */
			if (!copy($fileInfo['tmp_name'], $media->getStoredFilePath())) {
				$media->delete();
				throw new Default_Service_Media_Exception('Could not move uploaded file to the defined media path.');
			}
			return $media;
		} catch (Doctrine_Exception $exception) {
		}

		return NULL;
	}

	/**
	 * Attempts to create a Default_Model_Media instance from the specified url
	 *
	 * @param  String $url
	 * @param  Default_Model_MediaFolder $mediaFolder
	 * @param  Default_Model_Role $roleModel
	 * @param  Default_Model_Entity $entityModel
	 * @return Default_Model_Media
	 */
	public static function fromUrl($url = NULL, $mediaFolder = NULL, $roleModel = NULL, $entityModel = NULL)
	{
		if ($mediaFolder &&
			(!($mediaFolder instanceof Default_Model_MediaFolder))) {

			throw new Default_Service_Media_Exception('Media folder needs to be specified as a Default_Model_MediaFolder instance.');
		}

		if (!in_array('curl', get_loaded_extensions())) {
			throw new Default_Service_Media_Exception('Extensoin cURl has to be installed.');
		}

		if (!$url) {
			throw new Default_Service_Media_Exception('Url (' . $url . ') does not exist.');
		}

		// create curl resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, $url);

		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		// $output contains the output string
		$response = curl_exec($ch);

		// get headers and content
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$tmpHeaders = explode("\r\n", substr($response, 0, $header_size));
		$header = array();
		foreach ($tmpHeaders as $tmpHeader) {
			if ($tmpHeader) {
				$tmpHeaderArray = explode(': ', $tmpHeader);
				if (count($tmpHeaderArray) == 2) {
					$tmpHeaderArray[0] = trim($tmpHeaderArray[0]);
					$tmpHeaderArray[1] = trim($tmpHeaderArray[1]);
					if ($tmpHeaderArray[0]) {
						$header[$tmpHeaderArray[0]] = $tmpHeaderArray[1];
					}
				}
			}
		}
		$content = substr($response, $header_size);

		// close curl resource to free up system resources
		curl_close($ch);

		if (array_key_exists('Content-Type', $header)) {
			$mime_type = $header['Content-Type'];
		} else {
			$mime_type = NULL;
		}

		if (array_key_exists('Content-Length', $header)) {
			$size = $header['Content-Length'];
		} else {
			$size = NULL;
		}

		if (!$content) {
			throw new Default_Service_Media_Exception('No content available from url (' . $url . ').');
		}

		$tmpFileName = basename($url);
		$i = 0;
		while (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'media' . strtolower($i) . '_' . $tmpFileName)) {
			$i++;
		}

		$fileInfo = array(
			'name'=>basename($url),
			'size'=>$size,
			'mime_type'=>$mime_type,
			'tmp_name'=>BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'media' . strtolower($i) . '_' . $tmpFileName,
		);

		if (!file_put_contents($fileInfo['tmp_name'], $content)) {
			throw new Default_Service_Media_Exception('Download file to local system.');
		}

		/**
		 * retrieve user infos and role
		 */
		if (!($entityModel instanceof Default_Model_Entity)) {
			$entityModel = Zend_Auth::getInstance()->getIdentity();
		}
		if (!($roleModel instanceof Default_Model_Role)) {
			if ($entityModel) {
				$roleID = $entityModel->Role->id;
			} else {

				/**
				 * retrieve the default admin role
				 */
				$defaultRole = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->select('r.id, r.short')
					->where('r.short = ? ', array('admin'))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($defaultRole) {
					$roleID = $defaultRole['id'];
				} else {
					throw new Default_Service_Media_Exception('Could not retrieve role.');
				}
			}
		} else {
			$roleID = $roleModel['id'];
		}

		$entityModelID = NULL;
		if ($entityModel) {
			$entityModelID = $entityModel->id;
		}

		if (!$entityModelID) {
			/**
			 * retrieve the default admin entity
			 */
			$defaultAdmin = Doctrine_Query::create()
				->from('Default_Model_EntityAdmin m')
				->limit(1)
				->execute()
				->getFirst()
			;

			if (!$defaultAdmin) {
				throw new Default_Service_Media_Exception('Could not retrieve an entity.');
			} else {
				$entityModelID = $defaultAdmin->id;
			}
		}

		/**
		 * prepare data
		 */
		$data = array(
			'file_name'=>$fileInfo['name'],
			'file_size'=>$fileInfo['size'],
			'mime_type'=>$fileInfo['mime_type'],
			'role_id'=>$roleID,
			'entity_id'=>$entityModelID,
			'short'=>self::getShort($fileInfo['name'], NULL, NULL, $fileInfo['size']),
		);

		if ($data['mime_type'] == L8M_Mime::TYPE_APPLICATION_SHOCKWAVE_FLASH) {
			$media = new Default_Model_MediaShockwave();
		}

		/**
		 * image info
		 */
		$imageInfo = self::getImageInfo($fileInfo['tmp_name']);

		if (is_array($imageInfo)) {
			if (!isset($media)) {
				$media = new Default_Model_MediaImage();
			}
			$data = array_merge($data, $imageInfo);
		} else {
			$media = new Default_Model_MediaFile();
		}

		/**
		 * populate Default_Model_Media instance with data
		 */
		$media->merge($data);

		/**
		 * create pseudo-name if name does not exists
		 */
		if (!$media->name) {
			$pseudoName = explode('.', $data['file_name']);
			if (count($pseudoName) > 1) {
				unset($pseudoName[count($pseudoName) - 1]);
			}
			$pseudoName = implode('.', $pseudoName);

			foreach (L8M_Locale::getSupported() as $lang) {
				$media['Translation'][$lang]['name'] = $pseudoName;
			}
		}

		/**
		 * media folder
		 */
		if ($mediaFolder) {
			$media->MediaFolder = $mediaFolder;
		}

		/**
		 * save
		 */
		try {
			$media->save();
			/**
			 * @todo consider that renaming fails if the specified file already
			 *	   exists
			 */
			if (!copy($fileInfo['tmp_name'], $media->getStoredFilePath())) {
				$media->delete();
				throw new Default_Service_Media_Exception('Could not write downloaded file to the defined media path.');
			}
			return $media;
		} catch (Doctrine_Exception $exception) {
		}

		return NULL;
	}

	/**
	 * Attempts to create a Default_Model_Media instance from the specified
	 * Zend_File_Transfer_Adapter_Abstract instance and the specified
	 * identifier. Optionally, a Default_Model_MediaFolder instance can be
	 * specified.
	 *
	 * @param  Zend_File_Transfer_Adapter_Abstract $adapter
	 * @param  string $identifier
	 * @param  Default_Model_MediaFolder $mediaFolder
	 * @param  Default_Model_Role $roleModel
	 * @param  Default_Model_Entity $entityModel
	 * @return Default_Model_Media
	 */
	public static function fromFileTransferAdapter($adapter = NULL, $identifier = NULL, $mediaFolder = NULL, $roleModel = NULL, $entityModel = NULL)
	{
		if (!$adapter ||
			!($adapter instanceof Zend_File_Transfer_Adapter_Abstract)) {
			throw new Default_Service_Media_Exception('Adapter needs to be specified as a Zend_File_Transfer_Adapter_Abstract instance.');
		}
		if (!$identifier ||
			!is_string($identifier)) {
			throw new Default_Service_Media_Exception('Identifier needs to be specified as a string.');
		}
		if ($mediaFolder &&
			(!($mediaFolder instanceof Default_Model_MediaFolder))) {
			throw new Default_Service_Media_Exception('Media folder needs to be specified as a Default_Model_MediaFolder instance.');
		}

		/**
		 * retrieve file info from transfer adapter
		 */
		$fileInfo = $adapter->getFileInfo($identifier);
		$fileInfo = $fileInfo[$identifier];

		/**
		 * retrieve user infos and role
		 */
		if (!($entityModel instanceof Default_Model_Entity)) {
			$entityModel = Zend_Auth::getInstance()->getIdentity();

			/**
			 * maybe null so check that again
			 */
			if (!($entityModel instanceof Default_Model_Entity)) {
				/**
				 * retrieve the default admin entity
				 */
				$entityModel = Doctrine_Query::create()
					->from('Default_Model_EntityAdmin m')
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$entityModel) {
					throw new Default_Service_Media_Exception('Could not retrieve an entity.');
				}
			}
		}
		if (!($roleModel instanceof Default_Model_Role)) {
			if ($entityModel) {
				$roleID = $entityModel->Role->id;
			} else {

				/**
				 * retrieve the default admin
				 */
				$defaultRole = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->select('r.id, r.short')
					->where('r.short = ? ', array('admin'))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($defaultRole) {
					$roleID = $defaultRole['id'];
				} else {
					throw new Default_Service_Media_Exception('Could not retrieve role.');
				}
			}
		} else {
			$roleID = $roleModel['id'];
		}

		/**
		 * prepare data
		 */
		$data = array(
			'file_name'=>$fileInfo['name'],
			'file_size'=>$fileInfo['size'],
			'mime_type'=>$fileInfo['type'],
			'role_id'=>$roleID,
			'entity_id'=>$entityModel->id,
			'short'=>self::getShort($fileInfo['name'], NULL, NULL, $fileInfo['size']),
		);

		if ($data['mime_type'] == L8M_Mime::TYPE_APPLICATION_SHOCKWAVE_FLASH) {
			$media = new Default_Model_MediaShockwave();
		}

		/**
		 * image info
		 */
		$imageInfo = self::getImageInfo($fileInfo['tmp_name']);

		if (is_array($imageInfo)) {
			if (!isset($media)) {
				$media = new Default_Model_MediaImage();
			}
			$data = array_merge($data, $imageInfo);
		} else {
			$media = new Default_Model_MediaFile();
		}

		/**
		 * populate Default_Model_Media instance with data
		 */
		$media->merge($data);
		$media->name = $data['file_name'];

		/**
		 * media folder
		 */
		if ($mediaFolder) {
			$media->MediaFolder = $mediaFolder;
		}

		/**
		 * save
		 */
		try {
			$media->save();
			/**
			 * @todo consider that renaming fails if the specified file already
			 *       exists
			 */
			if (!rename($fileInfo['tmp_name'], $media->getStoredFilePath())) {
				$media->delete();
				throw new Default_Service_Media_Exception('Could not move uploaded file to the defined media path.');
			}
			return $media;
		} catch (Doctrine_Exception $exception) {
		}

		return NULL;
	}

	/**
	 * Returns Default_Model_Media instance from request.
	 *
	 * @param  Zend_Controller_Request_Http $request
	 * @param  boolean $useShort
	 * @return Default_Model_Media
	 */
	public static function fromRequest($request = NULL, $useShort = FALSE)
	{
		if ($request === NULL) {
			$request = new Zend_Controller_Request_Http();
		}
		if (!$request instanceof Zend_Controller_Request_Abstract) {
			throw new Default_Service_Media_Exception('Request needs to be specified as a Zend_Controller_Request_Abstract instance.');
		}

		/**
		 * media folder
		 */
		if (!$useShort) {
			$expression = preg_replace('/\//', '\/', Default_Model_Media::PUBLIC_MEDIA_PATH . '/');
		} else {
			$expression = preg_replace('/\//', '\/', Default_Model_Media::PUBLIC_MEDIASHORT_PATH . '/');
		}

		/**
		 * strip folder from request uri
		 */
		$identifier = preg_replace('/^' . $expression . '/', '', $request->getRequestUri());

		/**
		 * start media query
		 */
		$mediaQuery = Doctrine_Query::create()
			->from('Default_Model_Media m')
		;

		/**
		 * retrieve via id or short
		 */
		if (!$useShort) {

			/**
			 * via id
			 */
			$mediaQuery = $mediaQuery
				->where('m.id = ? ')
			;
		} else {

			/**
			 * via short
			 */
			$mediaQuery = $mediaQuery
				->where('m.short = ? ')
			;
		}
		$media = $mediaQuery
			->execute(array($identifier))
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
	 * Returns Short or filename with extension if possible that is not used in db
	 *
	 * @param  string $filename
	 * @param  $width
	 * @param  $height
	 * @param  $filesize
	 * @return string
	 */
	public static function getShort($filename = NULL, $width = NULL, $height = NULL, $filesize = NULL)
	{
		$filename = L8M_Library::getUsableUrlStringOnly($filename, '-', array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '_', '.'));

		if ($filename != '') {

			/**
			 * retrieve possible mediashort
			 */
			$mediaShortExists = Doctrine_Query::create()
				->from('Default_Model_Media m')
				->where('m.short = ? ', array($filename))
				->limit(1)
				->execute()
				->getFirst()
			;

			if ($mediaShortExists) {
				$nameAddOn = '';
				if ($width != NULL) {
					$nameAddOn .= $width;
				}
				if ($height != NULL) {
					if ($nameAddOn == '') {
						$nameAddOn .= $height;
					} else {
						$nameAddOn .= 'x' . $height;
					}
				}
				if ($filesize != NULL) {
					if ($nameAddOn == '') {
						$nameAddOn .= $filesize;
					} else {
						$nameAddOn .= '-' . $filesize;
					}
				}
				if (!$nameAddOn) {
					$nameAddOn = md5(time() . microtime());
				}
				$filenameParts = explode('.', $filename);
				if (count($filenameParts) >= 2) {
					$filenameParts[count($filenameParts) - 2] = $filenameParts[count($filenameParts) - 2] . '_' . $nameAddOn;
					$filename = self::getShort(implode('.', $filenameParts), $width, $height, $filesize);
				} else {
					$filename .= $nameAddOn;
				}
			} else {
				$model = new Default_Model_Media();
				$columnDefinition = $model->getTable()->getColumnDefinition('short');
				if (strlen($filename) > $columnDefinition['length']) {
					$filenameParts = explode('.', $filename);
					if (count($filenameParts) >= 2) {
						$filename = self::getShort(substr(substr($filenameParts[0], 0, 10) . md5(time() . microtime()), 0, $columnDefinition['length'] - 1 - strlen($filenameParts[count($filenameParts) - 1])) . '.' . $filenameParts[count($filenameParts) - 1], $width, $height, $filesize);
					} else {
						$filename = self::getShort(substr(substr($filename, 0, 10) . md5(time() . microtime()), 0, $columnDefinition['length']), $width, $height, $filesize);
					}
				}
			}
		}
		return $filename;
	}

	/**
	 * Returns FALSE when the file at the specified location is not an image,
	 * returns an array with information about the image otherwise
	 *
	 * @param  string $path
	 * @return bool|array
	 */
	public static function getImageInfo($path = NULL)
	{
		$returnValue = FALSE;

		if ($path &&
			is_string($path) &&
			file_exists($path) &&
			is_file($path) &&
			(FALSE != $imageSize = getimagesize($path))) {

			/**
			 * image size info
			 */
			if (!isset($imageSize['channels'])) {
				$imageSize['channels'] = NULL;
			}

			$returnValue = array(
				'width'=>$imageSize[0],
				'height'=>$imageSize[1],
				'mime_type'=>$imageSize['mime'],
				'channels'=>$imageSize['channels'],
			);

			/**
			 * image meta info vars
			 */
			$infoAuthorArray = array();
			$infoTitleArray = array();
			$infoDescriptionArray = array();
			$infoKeywordsArray = array();

			$infoCopyright = NULL;
			$infoAuthor = NULL;
			$infoTitle = NULL;
			$infoDescription = NULL;
			$infoKeywords = NULL;
			$infoGPS = array(
				'latitude'=>NULL,
				'longitude'=>NULL,
			);

			/**
			 * from iptc info
			 */
			$iptcObj = L8M_ImageMeta_Iptc::factory($path);
			if ($iptcObj) {
				$tmpCopyright = $iptcObj->get(L8M_ImageMeta_Iptc::IPTC_COPYRIGHT_STRING);
				if ($tmpCopyright) {
					$infoCopyright = $tmpCopyright;
				}

				$tmpAuthor= $iptcObj->get(L8M_ImageMeta_Iptc::IPTC_BYLINE);
				if ($tmpAuthor) {
					$infoAuthorArray[] = $tmpAuthor;
				}

				$tmpTitle = $iptcObj->get(L8M_ImageMeta_Iptc::IPTC_OBJECT_NAME);
				if ($tmpTitle) {
					$infoTitleArray[] = $tmpTitle;
				}

				$tmpDescription = $iptcObj->get(L8M_ImageMeta_Iptc::IPTC_CAPTION);
				if ($tmpDescription) {
					$infoDescriptionArray[] = $tmpDescription;
				}

				$tmpKeywords = $iptcObj->get(L8M_ImageMeta_Iptc::IPTC_KEYWORDS);
				if (is_array($tmpKeywords) &&
					count($tmpKeywords) > 0) {

					$infoKeywordsArray[] = implode('; ', $tmpKeywords);
				}
			}

			/**
			 * from exif info
			 */
			$exifObj = L8M_ImageMeta_Exif::factory($path);
			if ($exifObj) {
				$tmpAuthor = $exifObj->get('IFD0.Author');
				if ($tmpAuthor) {
					$infoAuthorArray[] = $tmpAuthor;
				}
				$tmpAuthor = $exifObj->get('IFD0.Artist');
				if ($tmpAuthor) {
					$infoAuthorArray[] = $tmpAuthor;
				}
				$tmpAuthor = $exifObj->get('WINXP.Author');
				if ($tmpAuthor) {
					$infoAuthorArray[] = $tmpAuthor;
				}

				$tmpTitle = $exifObj->get('IFD0.Title');
				if ($tmpTitle) {
					$infoTitleArray[] = $exifObj->get('IFD0.Title');
				}
				$tmpTitle = $exifObj->get('WINXP.Title');
				if ($tmpTitle) {
					$infoTitleArray[] = $tmpTitle;
				}

				$tmpDescription = $exifObj->get('IFD0.Subject');
				if ($tmpDescription) {
					$infoDescriptionArray[] = $tmpDescription;
				}
				$tmpDescription = $exifObj->get('IFD0.ImageDescription');
				if ($tmpDescription) {
					$infoDescriptionArray[] = $tmpDescription;
				}
				$tmpDescription = $exifObj->get('WINXP.Subject');
				if ($tmpDescription) {
					$infoDescriptionArray[] = $tmpDescription;
				}

				$tmpKeywords = $exifObj->get('IFD0.Keywords');
				if ($tmpKeywords) {
					$infoKeywordsArray[] = $tmpKeywords;
				}
				$tmpKeywords = $exifObj->get('WINXP.Keywords');
				if ($tmpKeywords) {
					$infoKeywordsArray[] = $tmpKeywords;
				}

				$infoGPS = $exifObj->getGPS();
			}

			$infoAuthorArray = array_unique($infoAuthorArray);
			foreach ($infoAuthorArray as $tmpAuthor) {
				if (mb_strlen($tmpAuthor) > mb_strlen($infoAuthor)) {
					$infoAuthor = $tmpAuthor;
				}
			}

			$infoTitleArray = array_unique($infoTitleArray);
			foreach ($infoTitleArray as $tmpTitle) {
				if (mb_strlen($tmpTitle) > mb_strlen($infoTitle)) {
					$infoTitle = $tmpTitle;
				}
			}

			$infoDescriptionArray = array_unique($infoDescriptionArray);
			foreach ($infoDescriptionArray as $tmpDescription) {
				if (mb_strlen($tmpDescription) > mb_strlen($infoDescription)) {
					$infoDescription = $tmpDescription;
				}
			}

			$infoKeywordsArray = array_unique($infoKeywordsArray);
			foreach ($infoKeywordsArray as $tmpKeywords) {
				if (mb_strlen($tmpKeywords) > mb_strlen($infoKeywords)) {
					$infoKeywords = $tmpKeywords;
				}
			}

			/**
			 * publish image meta info
			 */
			$returnValue['author'] = $infoAuthor;
			$returnValue['copyright'] = $infoCopyright;
			$returnValue = array_merge($returnValue, $infoGPS);

			foreach (L8M_Locale::getSupported() as $lang) {
				$returnValue['Translation'][$lang] = array(
					'name'=>$infoTitle,
					'description'=>$infoDescription,
					'keywords'=>$infoKeywords,
				);
			}
		}

		return $returnValue;
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