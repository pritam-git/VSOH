<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/ProductOptionModel/Create/BeforeSave.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeSave.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_ProductOptionModel_Create_BeforeSave
 *
 *
 */
class PRJ_ProductOptionModel_Create_BeforeSave
{

	private $_goOn = FALSE;
	private $_formValues = array();
	private $_exception = NULL;

	/**
	 * BeforeSave
	 *
	 * @param String $modelName
	 * @param array $formValues
	 * @param L8M_ModelForm_Base $form
	 */
	public function beforeSave($modelName, $formValues, $form)
	{
		$this->_formValues = $formValues;

		/**
		 * check option
		 */
		$productOptionModel = Doctrine_Query::create()
			->from('Default_Model_ProductOptionModel m')
			->addWhere('m.model_name_id = ? ', array($formValues['model_name_id']))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * if option used
		 */
		if ($productOptionModel) {

			$exceptionMsg = vsprintf(L8M_Translate::string('An error has occurred.'),
				array(
					$productOptionModel->name,
				)
			);
			$this->_exception = new L8M_Exception($exceptionMsg);
			$this->_goOn = FALSE;

			/**
			 * add error to form element
			 *
			 * @var Zend_Form_Element
			 */
			$errorMsg = vsprintf(L8M_Translate::string('Model Name "%1s" already used on "%2s".'),
				array(
					$productOptionModel->ModelName->name,
					$productOptionModel->name,
				)
			);
			$nameElement = $form->getElement('product_option_model_model_name_id');
			$nameElement->addErrorMessage($errorMsg);
			$nameElement->markAsError();
		} else {
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