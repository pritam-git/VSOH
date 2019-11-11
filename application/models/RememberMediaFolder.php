<?php

/**
 * Default_Model_RememberMediaFolder
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 5 2014-02-10 10:17:08Z nm $
 */
class Default_Model_RememberMediaFolder extends Default_Model_Base_RememberMediaFolder
{

	/**
	 * Set column model name to media folder for each user
	 *
	 * @param Default_Model_ModelColumnName $modelColumnNameModel
	 * @param Default_Model_MediaFolder $mediaFolderModel
	 * @return void
	 */
	public static function setInfo($modelColumnNameModel = NULL, $mediaFolderModel = NULL) {
		if ($modelColumnNameModel instanceof Default_Model_ModelColumnName &&
			Zend_Auth::getInstance()->hasIdentity()) {

			if ($mediaFolderModel instanceof Default_Model_MediaFolder) {
				$rememberMediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_RememberMediaFolder m')
					->addWhere('m.model_column_name_id = ?', array($modelColumnNameModel->id))
					->addWhere('m.entity_id = ?', array(Zend_Auth::getInstance()->getIdentity()->id))
					->addWhere('m.media_folder_id = ?', array($mediaFolderModel->id))
					->limit(1)
					->execute()
					->getFirst()
				;
				if (!$rememberMediaFolderModel) {
					$rememberMediaFolderModel = Doctrine_Query::create()
						->from('Default_Model_RememberMediaFolder m')
						->addWhere('m.model_column_name_id = ?', array($modelColumnNameModel->id))
						->addWhere('m.entity_id = ?', array(Zend_Auth::getInstance()->getIdentity()->id))
						->limit(1)
						->execute()
						->getFirst()
					;
					if (!$rememberMediaFolderModel) {
						$rememberMediaFolderModel = new Default_Model_RememberMediaFolder();
					}
				}

				$rememberMediaFolderModel->merge(array(
					'model_column_name_id'=>$modelColumnNameModel->id,
					'entity_id'=>Zend_Auth::getInstance()->getIdentity()->id,
					'media_folder_id'=>$mediaFolderModel->id,
				));

				$rememberMediaFolderModel->save();
			} else {
				$rememberMediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_RememberMediaFolder m')
					->addWhere('m.model_column_name_id = ?', array($modelColumnNameModel->id))
					->addWhere('m.entity_id = ?', array(Zend_Auth::getInstance()->getIdentity()->id))
					->limit(1)
					->execute()
					->getFirst()
				;
				if ($rememberMediaFolderModel) {
					$rememberMediaFolderModel->hardDelete();
				}
			}
		}
	}
}