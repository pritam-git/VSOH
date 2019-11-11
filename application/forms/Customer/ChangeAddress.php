<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Customer/ChangeAddress.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ChangeAddress.php 28 2014-04-02 14:50:33Z nm $
 */

/**
 *
 *
 * Default_Form_Customer_ChangeAddress
 *
 *
 */
class Default_Form_Customer_ChangeAddress extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Customer_ChangeAddress instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		$view = Zend_Layout::getMvcInstance()->getView();

		/**
		 * form
		 */
		$this
			->setMethod(Zend_Form::METHOD_POST)
			->setAttrib('id', 'formChangeAddress')
		;

		/**
		 * firstname
		 */
		$formFirstName = new Zend_Form_Element_Text('firstname');
		$formFirstName
			->setLabel('Vorname *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formFirstName);

		/**
		 * lastname
		 */
		$formLastname = new Zend_Form_Element_Text('lastname');
		$formLastname
			->setLabel('Nachname *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formLastname);

		/**
		 * street
		 */
		$formStreet = new Zend_Form_Element_Text('street');
		$formStreet
			->setLabel('Straße *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength(array(
					'max'=>255,
				))
			))
		;
		$this->addElement($formStreet);

		/**
		 * street number
		 */
		$formStreetNumber = new Zend_Form_Element_Text('street_number');
		$formStreetNumber
			->setLabel('Hausnummer *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_Alnum(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alnum(TRUE),
				new Zend_Validate_StringLength(array(
					'min'=>1,
					'max'=>5,
				)),
			))
		;
		$this->addElement($formStreetNumber);

		/**
		 * address line 1
		 */
		$formAddressLine1 = new Zend_Form_Element_Text('address_line_1');
		$formAddressLine1
			->setLabel('Adresszusatz 1')
			->setRequired(FALSE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength(array(
					'max'=>255,
				))
			))
		;
		$this->addElement($formAddressLine1);

		/**
		 * address line 2
		 */
		$formAddressLine2 = new Zend_Form_Element_Text('address_line_2');
		$formAddressLine2
			->setLabel('Adresszusatz 2')
			->setRequired(FALSE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength(array(
					'max'=>255,
				))
			))
		;
		$this->addElement($formAddressLine2);

		/**
		 * zip
		 */
		$formZip = new Zend_Form_Element_Text('zip');
		$formZip
			->setLabel('PLZ *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_Alnum(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alnum(TRUE),
				new Zend_Validate_StringLength(array(
					'max'=>16,
				)),
			))
		;
		$this->addElement($formZip);

		/**
		 * city
		 */
		$formCity = new Zend_Form_Element_Text('city');
		$formCity
			->setLabel('Stadt *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_Alpha(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alpha(TRUE),
			))
		;
		$this->addElement($formCity);

		/**
		 * country
		 */
		$formCountry = new L8M_JQuery_Form_Element_Select('country_id');
		$formCountry
			->setLabel($view->translate('Land *', 'de'))
			->setRequired(TRUE)
			->setDisableTranslator(TRUE)
		;

		/**
		 * countryOptions
		 */
		$formCountryOptions = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->select('c.id, ct.name')
			->leftJoin('c.Translation ct')
			->addWhere('ct.lang = ?', L8M_Locale::getDefault())
			->orderBy('ct.name ASC')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
		;
		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT == L8M_Environment::getInstance()->getEnvironment()) {
			$formCountryOptions = $formCountryOptions
				->addWhere('c.id = ? ', array(54))
			;
		}
		$formCountryOptions = $formCountryOptions
			->execute(array())
		;

		if (is_array($formCountryOptions) &&
			count($formCountryOptions) > 0) {

			$defaultCountryID = $formCountryOptions[0]['c_id'];
			foreach($formCountryOptions as $formCountryOption) {
				if ($formCountryOption['ct_name'] == 'Deutschland') {
					$defaultCountryID = $formCountryOption['c_id'];
				}
				$formCountry->addMultiOption(
					$formCountryOption['c_id'],
					$formCountryOption['ct_name']
				);
			}
			$formCountry->setValue($defaultCountryID);
		}
		$this->addElement($formCountry);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerChangeAddress');
		$formSubmitButton
			->setLabel('Submit')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'class'=>'submit',
				)),
			))
		;
		$this->addElement($formSubmitButton);
	}
}