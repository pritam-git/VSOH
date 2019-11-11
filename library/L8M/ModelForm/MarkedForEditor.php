<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ModelForm/MarkedForEditor.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MarkedForEditor.php 215 2014-11-05 11:37:46Z nm $
 */

/**
 *
 *
 * L8M_ModelForm_MarkedForEditor
 *
 *
 */
class L8M_ModelForm_MarkedForEditor
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Getters
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 *
	 *
	 *
	 *
	 */
	protected static function _deactivateOld()
	{
		$markedForEditorCollection = Doctrine_Query::create()
			->from('Default_Model_ModelMarkedForEditor m')
			->addWhere('m.active = ? ', array(TRUE))
			->addWhere('(NOW() - m.updated_at) > ? ', array(L8M_Config::getOption('l8m.modelFormBase.editorBlock.time')))
			->limit(10)
			->execute()
		;

		foreach ($markedForEditorCollection as $markedForEditorModel) {
			$markedForEditorModel->active = FALSE;
			$markedForEditorModel->save();
			$markedForEditorModel->free(TRUE);
		}
	}

	/**
	 *
	 *
	 *
	 *
	 */
	public static function generateIdentifier($model, $ID)
	{
		if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$returnValue = 'development';
		} else
		if (!L8M_Config::getOption('l8m.modelFormBase.editorBlock.enabled')) {
			$returnValue = 'editorBlock-disabled';
		} else {
			list($microtime, $time) = explode(' ', microtime());
			$totalTime =  $time . substr($microtime, 1);

			$returnValue = $model . ' - ' . $ID . ' - ' . $totalTime;
		}

		return $returnValue;
	}

	/**
	 *
	 *
	 *
	 *
	 */
	public static function getIdentifier($model, $ID, $saveNewOne = FALSE)
	{

		$returnValue = NULL;

		if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$returnValue = 'development';
		} else
		if (!L8M_Config::getOption('l8m.modelFormBase.editorBlock.enabled')) {
			$returnValue = 'editorBlock-disabled';
		} else {
			if (Zend_Auth::getInstance()->hasIdentity()) {

				$identityID = Zend_Auth::getInstance()->getIdentity()->id;

				$modelNameModel = Doctrine_Query::create()
					->from('Default_Model_ModelName m')
					->addWhere('m.name = ? ', array($model))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($modelNameModel) {

					self::_deactivateOld();

					$markedForEditorModel = Doctrine_Query::create()
						->from('Default_Model_ModelMarkedForEditor m')
						->addWhere('m.model_name_id = ? ', array($modelNameModel->id))
						->addWhere('m.referenced_id = ? ', array($ID))
						->addWhere('m.active = ? ', array(TRUE))
						->addWhere('m.entity_id = ? ', array($identityID))
						->addWhere('(NOW() - m.updated_at) <= ? ', array(L8M_Config::getOption('l8m.modelFormBase.editorBlock.time')))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($markedForEditorModel) {
						$returnValue = $markedForEditorModel['identifier'];
					} else {
						$returnValue = self::generateIdentifier($model, $ID);

						if ($saveNewOne &&
							self::isEditable($model, $ID)) {

							$markedForEditorModel = Doctrine_Query::create()
								->from('Default_Model_ModelMarkedForEditor m')
								->addWhere('m.model_name_id = ? ', array($modelNameModel->id))
								->addWhere('m.referenced_id = ? ', array($ID))
								->addWhere('m.active = ? ', array(TRUE))
								->addWhere('m.entity_id = ? ', array($identityID))
								->addWhere('(NOW() - m.updated_at) <= ? ', array(L8M_Config::getOption('l8m.modelFormBase.editorBlock.time')))
								->limit(1)
								->execute()
								->getFirst()
							;

							if ($markedForEditorModel) {
								$returnValue = $markedForEditorModel->identifier;
							}
						}
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 *
	 *
	 *
	 *
	 */
	public static function deactivate($model, $ID, $identifier)
	{
		$returnValue = FALSE;

		if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$returnValue = TRUE;
		} else
		if (!L8M_Config::getOption('l8m.modelFormBase.editorBlock.enabled')) {
			$returnValue = TRUE;
		} else {
			if (Zend_Auth::getInstance()->hasIdentity()) {

				$identityID = Zend_Auth::getInstance()->getIdentity()->id;

				self::_deactivateOld();

				$markedForEditorModel = Doctrine_Query::create()
					->from('Default_Model_ModelMarkedForEditor m')
					->leftJoin('m.ModelName t')
					->addWhere('t.name = ? ', array($model))
					->addWhere('m.referenced_id = ? ', array($ID))
					->addWhere('m.active = ? ', array(TRUE))
					->addWhere('m.entity_id = ? ', array($identityID))
					->addWhere('m.identifier = ? ', array($identifier))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($markedForEditorModel) {
					$markedForEditorModel->active = FALSE;
					$markedForEditorModel->save();
					$returnValue = TRUE;
				}
			}
		}

		return $returnValue;
	}

	/**
	 *
	 *
	 *
	 *
	 */
	public static function isValid($model, $ID, $identifier)
	{
		$returnValue = FALSE;

		if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$returnValue = TRUE;
		} else
		if (!L8M_Config::getOption('l8m.modelFormBase.editorBlock.enabled')) {
			$returnValue = TRUE;
		} else {
			if (Zend_Auth::getInstance()->hasIdentity()) {

				$identityID = Zend_Auth::getInstance()->getIdentity()->id;

				$markedForEditorModel = Doctrine_Query::create()
					->from('Default_Model_ModelMarkedForEditor m')
					->leftJoin('m.ModelName t')
					->addWhere('t.name = ? ', array($model))
					->addWhere('m.referenced_id = ? ', array($ID))
					->addWhere('m.active = ? ', array(TRUE))
					->addWhere('m.entity_id = ? ', array($identityID))
					->addWhere('m.identifier = ? ', array($identifier))
					->addWhere('(NOW() - m.updated_at) <= ? ', array(L8M_Config::getOption('l8m.modelFormBase.editorBlock.time')))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($markedForEditorModel) {
					$returnValue = TRUE;
				}
			}
		}

		return $returnValue;
	}

	/**
	 *
	 *
	 *
	 *
	 */
	public static function isEditable($model, $ID, $autoRenew = TRUE)
	{
		$returnValue = FALSE;

		if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$returnValue = TRUE;
		} else
		if (!L8M_Config::getOption('l8m.modelFormBase.editorBlock.enabled')) {
			$returnValue = TRUE;
		} else {
			if (Zend_Auth::getInstance()->hasIdentity()) {

				$identityID = Zend_Auth::getInstance()->getIdentity()->id;

				$modelNameModel = Doctrine_Query::create()
					->from('Default_Model_ModelName m')
					->addWhere('m.name = ? ', array($model))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($modelNameModel) {

					self::_deactivateOld();

					$markedForEditorModel = Doctrine_Query::create()
						->from('Default_Model_ModelMarkedForEditor m')
						->addWhere('m.model_name_id = ? ', array($modelNameModel->id))
						->addWhere('m.referenced_id = ? ', array($ID))
						->addWhere('m.active = ? ', array(TRUE))
						->addWhere('(NOW() - m.updated_at) <= ? ', array(L8M_Config::getOption('l8m.modelFormBase.editorBlock.time')))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($markedForEditorModel) {
						if ($markedForEditorModel->entity_id == $identityID) {
							$returnValue = TRUE;
						}
					} else {
						$returnValue = TRUE;
					}

					if ($returnValue) {
						if (!$markedForEditorModel) {
							$markedForEditorModel = new Default_Model_ModelMarkedForEditor();
							$markedForEditorModel->merge(array(
								'model_name_id'=>$modelNameModel->id,
								'referenced_id'=>$ID,
								'active'=>TRUE,
								'entity_id'=>$identityID,
								'identifier'=>self::generateIdentifier($model, $ID),
							));
						} else {
							if ($autoRenew) {
								$markedForEditorModel->identifier = self::generateIdentifier($model, $ID);
							} else {
								$markedForEditorModel->updated_at = date('Y-m-d H:i:s');
							}
						}
						$markedForEditorModel->save();
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 *
	 *
	 *
	 *
	 */
	public static function renewIdentifier($model, $ID, $identifier)
	{
		$returnValue = NULL;

		if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
			$returnValue = 'development';
		} else
		if (!L8M_Config::getOption('l8m.modelFormBase.editorBlock.enabled')) {
			$returnValue = 'editorBlock-disabled';
		} else {
			if (Zend_Auth::getInstance()->hasIdentity()) {

				$identityID = Zend_Auth::getInstance()->getIdentity()->id;

				$modelNameModel = Doctrine_Query::create()
					->from('Default_Model_ModelName m')
					->addWhere('m.name = ? ', array($model))
					->limit(1)
					->execute()
					->getFirst()
				;

				if ($modelNameModel) {

					self::_deactivateOld();

					$markedForEditorModel = Doctrine_Query::create()
						->from('Default_Model_ModelMarkedForEditor m')
						->addWhere('m.model_name_id = ? ', array($modelNameModel->id))
						->addWhere('m.referenced_id = ? ', array($ID))
						->addWhere('m.active = ? ', array(TRUE))
						->addWhere('m.entity_id = ? ', array($identityID))
						->addWhere('m.identifier = ? ', array($identifier))
						->addWhere('(NOW() - m.updated_at) <= ? ', array(L8M_Config::getOption('l8m.modelFormBase.editorBlock.time')))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($markedForEditorModel) {
						$returnValue = self::generateIdentifier($model, $ID);
						$markedForEditorModel->identifier = $returnValue;
						$markedForEditorModel->save();
					}
				}
			}
		}

		return $returnValue;
	}
}