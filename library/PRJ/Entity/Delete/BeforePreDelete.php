<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/Entity/Delete/BeforePreDelete.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforePreDelete.php 523 2016-11-22 08:31:14Z nm $
 */

/**
 *
 *
 * PRJ_Entity_Delete_BeforePreDelete
 *
 *
 */
class PRJ_Entity_Delete_BeforePreDelete
{

	private $_goOn = FALSE;
	private $_exception = NULL;

	/**
	 * BeforePreDelete
	 *
	 * @param Default_Model_Entity $entityModel
	 */
	public function beforePreDelete($entityModel)
	{

		/**
		 * check for entity modelList Config
		 */
		$entityModelListConfigCollection = Doctrine_Query::create()
			->from('Default_Model_EntityModelListConfig m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($entityModelListConfigCollection as $entityModelListConfigModel) {
			$entityModelListConfigModel->hardDelete();
		}

		/**
		 * check for media
		 */
		$mediaCollection = Doctrine_Query::create()
			->from('Default_Model_Media m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($mediaCollection as $mediaModel) {
			try {
				$mediaModel->hardDelete();
			} catch (Exception $exception) {
				$mediaModel->entity_id = Zend_Auth::getInstance()->getIdentity()->id;
				$mediaModel->save();
			}
		}

		/**
		 * check for log
		 */
		$logCollection = Doctrine_Query::create()
			->from('Default_Model_EntityLog m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($logCollection as $logModel) {
			$logModel->hardDelete();
		}

		/**
		 * check for ListConfig
		 */
		$modelListConfigCollection = Doctrine_Query::create()
			->from('Default_Model_EntityModelListConfig m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($modelListConfigCollection as $modelListConfigModel) {
			$modelListConfigModel->hardDelete();
		}

		/**
		 * check for remember media folder
		 */
		$rememberMediaFolderCollection = Doctrine_Query::create()
			->from('Default_Model_RememberMediaFolder m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($rememberMediaFolderCollection as $rememberMediaFolderModel) {
			$rememberMediaFolderModel->hardDelete();
		}

		/**
		 * check for model list export
		 */
		$modelListExportCollection = Doctrine_Query::create()
			->from('Default_Model_ModelListExport m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($modelListExportCollection as $modelListExportModel) {
			$modelListExportModel->hardDelete();
		}

		/**
		 * check for model marked for editor
		 */
		$modelMarkedForEditorCollection = Doctrine_Query::create()
			->from('Default_Model_ModelMarkedForEditor m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($modelMarkedForEditorCollection as $modelMarkedForEditorModel) {
			$modelMarkedForEditorModel->hardDelete();
		}

		/**
		 * check for dates participants
		 */
		$datesParticipantsCollection = Doctrine_Query::create()
			->from('Default_Model_DatesParticipants m')
			->addWhere('m.user_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($datesParticipantsCollection as $datesParticipantsModel) {
			$datesParticipantsModel->hardDelete();
		}

		/**
		 * check for model entity brands
		 */
		$entityM2nBrandCollection = Doctrine_Query::create()
			->from('Default_Model_EntityM2nBrand m')
			->addWhere('m.entity_id = ?', array($entityModel->id))
			->execute()
		;
		foreach ($entityM2nBrandCollection as $entityM2nBrandModel) {
			$entityM2nBrandModel->hardDelete();
		}

		$this->_goOn = TRUE;
	}

	/**
	 * Flags whether to go on or not.
	 *
	 * @return boolean
	 */
	public function goOn()
	{
		return $this->_goOn;
	}

	/**
	 * Returns internal error.
	 *
	 * @return Exception
	 */
	public function getException()
	{
		return $this->_exception;
	}
}