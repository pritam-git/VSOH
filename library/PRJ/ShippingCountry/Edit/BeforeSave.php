<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/ShippingCountry/Edit/BeforeSave.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeSave.php 309 2015-04-01 12:15:01Z nm $
 */

/**
 *
 *
 * PRJ_ShippingCountry_Edit_BeforeSave
 *
 *
 */
class PRJ_ShippingCountry_Edit_BeforeSave
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
		$this->_goOn = TRUE;
		$this->_formValues = $formValues;

		$modelFrom = $modelName . ' m';

		$model = Doctrine_Query::create()
			->from($modelFrom)
			->addWhere('m.id = ?', array($modelID))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * check for model with same countryID
		 */
		$shippingCountryModel = Doctrine_Query::create()
			->from('Default_Model_ShippingCountry sc')
			->addWhere('sc.country_id = ? AND sc.id != ?', array($formValues['country_id'], $model->id))
			->limit(1)
			->execute()
			->getFirst()
		;

		if ($shippingCountryModel) {

			/**
			 * add error to form element
			 *
			 * @var Zend_Form_Element
			 */
			$errorMsg = L8M_Translate::string('Choosen country already used for another model.');
			$countryElement = $form->getElement('shipping_country_country_id');
			$countryElement->addErrorMessage($errorMsg);
			$countryElement->markAsError();
			$this->_goOn = FALSE;
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