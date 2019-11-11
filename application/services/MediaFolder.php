<?php

/**
 * L8M
 *
 *
 * @filesource /application/services/MediaFolder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaFolder.php 527 2017-03-26 09:28:36Z nm $
 */

/**
 *
 *
 * Default_Service_MediaFolder
 *
 *
 */
class Default_Service_MediaFolder extends Default_Service_Base_Abstract
{
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Return Default_Model_MediaFolder from given path.
	 *
	 * @param  string $path
	 * @return Default_Model_MediaFolder
	 */
	public static function getMediaFolderModelFromPath($path = NULL)
	{
		/**
		 * media directory
		 */
		$defaultMediaFolder = NULL;
		if ($path) {

			if (strlen(trim($path)) > 1 &&
				substr(trim($path), 0, 1) == '/' &&
				substr(trim($path), -1) != '/') {

				$directoryString = substr(trim($path), 1);
				$directoryPathArray = explode('/', $directoryString);

				$goOn = TRUE;
				$firstPath = TRUE;
				$parentMediaFolderModel = NULL;
				foreach ($directoryPathArray as $directoryPathItem) {
					if ($goOn) {
						$allowedChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'é', 'É', 'è', 'È', 'ê', 'Ê', 'í', 'Í', 'ì', 'Ì', 'î', 'Î', 'ñ', 'Ñ', 'ó', 'Ó', 'ò', 'Ò', 'ô', 'Ô', 'ß', 'ú', 'Ù', 'ù', 'Ù', 'û', 'Û', '-', '_', ' ');
						$directoryPathItem = trim(L8M_Library::getUsableUrlStringOnly($directoryPathItem, '', $allowedChars, array(), FALSE));
						if ($directoryPathItem) {
							$mediaFolderQuery = Doctrine_Query::create()
								->from('Default_Model_MediaFolder m')
							;

							if ($firstPath) {
								$mediaFolderQuery = $mediaFolderQuery
									->addWhere('m.media_folder_id IS NULL')
								;
							} else
							if (!$firstPath &&
								$parentMediaFolderModel) {

								$mediaFolderQuery = $mediaFolderQuery
									->addWhere('m.media_folder_id = ? ', array($parentMediaFolderModel->id))
								;
							} else {
								$goOn = FALSE;
							}
							$mediaFolderModel = $mediaFolderQuery
								->addWhere('m.name = ? ', array($directoryPathItem))
								->limit(1)
								->execute()
								->getFirst()
							;

							if (!$mediaFolderModel) {
								$mediaFolderModel = new Default_Model_MediaFolder();
								$mediaFolderModel->name = $directoryPathItem;
								if ($parentMediaFolderModel) {
									$mediaFolderModel->media_folder_id = $parentMediaFolderModel->id;
								}
								$mediaFolderModel->save();
							}
							$parentMediaFolderModel = $mediaFolderModel;
							$firstPath = FALSE;
						} else {
							$goOn = FALSE;
						}
					}
				}
				if (!$goOn) {
					throw new L8M_Exception('Failure auto creating media folder. You can only create folders same as that pattern: "/base/subdirectory" by using only characters and digits.');
				} else {
					$defaultMediaFolder = $parentMediaFolderModel;
				}
			} else {
				throw new L8M_Exception('Failure auto creating media folder. You can only create folders same as that pattern: "/base/subdirectory" by using only characters and digits.');
			}
		}

		return $defaultMediaFolder;
	}

	/**
	 * Return id of Default_Model_MediaFolder from given path.
	 *
	 * @param  string $path
	 * @return integer
	 */
	public static function getMediaFolderIDFromPath($path = NULL)
	{
		$defaultMediaFolderID = NULL;
		$defaultMediaFolder = self::getMediaFolderModelFromPath($path);

		if ($defaultMediaFolder) {
			$defaultMediaFolderID = $defaultMediaFolder->id;
		}

		return $defaultMediaFolderID;
	}
}