<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/InfoPage/Edit/BeforeSave.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeSave.php 211 2014-10-28 08:48:33Z nm $
 */

/**
 *
 *
 * PRJ_InfoPage_Edit_BeforeSave
 *
 *
 */
class PRJ_InfoPage_Edit_BeforeSave
{

	private $_goOn = FALSE;
	private $_formValues = array();
	private $_exception = NULL;

	/**
	 * BeforeSave
	 *
	 * @param integer $modelID
	 * @param String $modelName
	 * @param array $formValues
	 * @param L8M_ModelForm_Base $form
	 */
	public function beforeSave($modelID, $modelName, $formValues, $form)
	{
		$this->_formValues = $formValues;

		/**
		 * name for future controller
		 */
		$controllerName = L8M_Library::getUsableUrlStringOnly($this->_formValues['name']);

		/**
		 * check for existent ControllerModel
		 */
		$controllerModel = Doctrine_Query::create()
			->from('Default_Model_Controller c')
			->leftJoin('c.Module m')
			->addWhere('c.name = ?', array($controllerName))
			->addWhere('m.name = ?', array('default'))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * check for existent InfoPageModel
		 */
		$infoPageModel = Default_Model_InfoPage::getModelByID($modelID, 'Default_Model_InfoPage');

		/**
		 * if controller or resource already exists, do not go on
		 */
		if ($infoPageModel->short != $controllerName &&
			($controllerModel != FALSE || L8M_Acl_Resource::existsInDatabase('default', $controllerName, 'index'))) {

			$exceptionMsg = vsprintf(L8M_Translate::string('Resource "%1s" already exist. You can not name the Controller "%2s".'),
				array(
					'default.' . $controllerName . '.index',
					$controllerName,
				)
			);
			$this->_exception = new L8M_Exception($exceptionMsg);
			$this->_goOn = FALSE;

			/**
			 * add error to form element
			 *
			 * @var Zend_Form_Element
			 */
			$errorMsg = vsprintf(L8M_Translate::string('"%1s" is a value, that is not allowed.'),
				array(
					$controllerName,
				)
			);
			$nameElement = $form->getElement('info_page_name');
			$nameElement->addErrorMessage($errorMsg);
			$nameElement->markAsError();
		} else {

			$modelFrom = $modelName . ' m';

			$model = Doctrine_Query::create()
				->from($modelFrom)
				->addWhere('m.id = ?', array($modelID))
				->limit(1)
				->execute()
				->getFirst()
			;

			/**
			 * name of old controller
			 */
			$controllerNameBefore = $model->short;

			/**
			 * check for existent ControllerModel
			 */
			$controllerModelBefore = Doctrine_Query::create()
				->from('Default_Model_Controller c')
				->addWhere('c.name = ?', array($controllerNameBefore))
				->limit(1)
				->execute()
				->getFirst()
			;

			/**
			 * if controller or resource already exists, do not go on
			 */
			if ($controllerModelBefore != FALSE) {
				Zend_Registry::set('PRJ_InfoPage_Edit_OldControllerName', $controllerNameBefore);
			}

			$this->_goOn = TRUE;
		}
	}

	/**
	 * Returns new from values.
	 *
	 * @return array
	 */
	public function replaceFormValues()
	{
		return $this->_formValues;
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