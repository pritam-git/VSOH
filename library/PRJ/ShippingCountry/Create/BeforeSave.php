<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/ShippingCountry/Create/BeforeSave.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeSave.php 54 2014-04-30 11:29:22Z nm $
 */

/**
 *
 *
 * PRJ_ShippingCountry_Create_BeforeSave
 *
 *
 */
class PRJ_ShippingCountry_Create_BeforeSave
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
		 * check for model with same countryID
		 */
		$shippingCountryModel = Doctrine_Query::create()
			->from('Default_Model_ShippingCountry sc')
			->addWhere('sc.country_id = ?', array($this->_formValues['country_id']))
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