<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/MediaFolder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaFolder.php 319 2015-04-07 12:03:28Z nm $
 */

/**
 *
 *
 * Default_Model_MediaFolder
 *
 *
 */
class Default_Model_MediaFolder extends Default_Model_Base_MediaFolder
{

	/**
	 * return (and create, if it not exists,) media folder model
	 *
	 * @param string $folderName
	 * @param string $parentFolder
	 * @return Default_Model_MediaFolder
	 */
	public static function getFolder ($folderName, $parentFolder = NULL) {

		$parentFolderID = NULL;

		$mediaFolderModel = Default_Model_MediaFolder::getModelByColumn('name', $folderName, 'Default_Model_MediaFolder');

		if ($mediaFolderModel instanceof Default_Model_MediaFolder) {
			return $mediaFolderModel;
		}

		if ($parentFolder != NULL) {
			$parentFolderModel = Default_Model_MediaFolder::getModelByColumn('name', $parentFolder, 'Default_Model_MediaFolder');

			if ($parentFolderModel instanceof Default_Model_MediaFolder) {
				$parentFolderID = $parentFolderModel->id;
			}
		}

		$mediaFolderModel = new Default_Model_MediaFolder();

		$mediaFolderModel->merge(array(
			'name'=>$folderName,
			'media_folder_id'=>$parentFolderID
		));

		$mediaFolderModel->save();

		return $mediaFolderModel;
	}

	/**
	 * deletes this data access object and all the related composites
	 * this operation is isolated by a transaction
	 *
	 * this event can be listened by the onPreDelete and onDelete listeners
	 *
	 * @return boolean	  true if successful
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		if ($this->id &&
			class_exists('Default_Model_ModelListImageFolder', TRUE)) {

			$modelListImageFolderCollection = Doctrine_Query::create()
				->from('Default_Model_ModelListImageFolder m')
				->addWhere('m.media_folder_id = ?', array($this->id))
				->execute()
			;
			foreach ($modelListImageFolderCollection as $modelListImageFolderModel) {
				$modelListImageFolderModel->hardDelete();
			}
		}

		return parent::delete($conn);
	}
}