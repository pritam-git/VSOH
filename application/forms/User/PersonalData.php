<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/User/PersonalData.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PersonalData.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_User_PersonalData
 *
 *
 */
class Default_Form_User_PersonalData extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_User_PersonalData instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this
			->setMethod(Zend_Form::METHOD_POST)
			->setAttrib('id', 'formUserPersonalData')
		;

		$entity = Zend_Auth::getInstance()->getIdentity();

		/**
		 * street
		 */
		$formStreet = new Zend_Form_Element_Text('street');
		$formStreet
			->setLabel('Street')
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

		$formStreet->setValue($entity->street);
		$this->addElement($formStreet);

		/**
		 * street number
		 */
		$formStreetNumber = new Zend_Form_Element_Text('street_number');
		$formStreetNumber
			->setLabel('House Number')
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

		$formStreetNumber->setValue($entity->street_number);
		$this->addElement($formStreetNumber);

		/**
		 * zip
		 */
		$formZip = new Zend_Form_Element_Text('zip');
		$formZip
			->setLabel('Zip')
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

		$formZip->setValue($entity->zip);
		$this->addElement($formZip);

		/**
		 * city
		 */
		$formCity = new Zend_Form_Element_Text('city');
		$formCity
			->setLabel('City')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_Alpha(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alpha(TRUE),
			))
		;
		$formCity->setValue($entity->city);
		$this->addElement($formCity);

		/**
		 * country
		 */
		$formCountry = new L8M_JQuery_Form_Element_Select('country_id');
		$formCountry
			->setLabel('Country')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_Alpha(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alpha(TRUE),
				new Zend_Validate_StringLength(array(
					'max'=>80,
				))
			))
		;

		/**
		 * countryOptions
		 */
		$formCountryOptions = Doctrine_Query::create()
			->from('Default_Model_ShippingCountry sc')
			->leftJoin('sc.Country c')
			->leftJoin('c.Translation ct')
			->select('sc.country_id, ct.name')
			->addWhere('ct.lang = ?', L8M_Locale::getLang())
			->orderBy('ct.name ASC')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
		;
		$formCountryOptions = $formCountryOptions
			->execute(array())
		;

		if (is_array($formCountryOptions) &&
			count($formCountryOptions) > 0) {

			$defaultCountryID = $formCountryOptions[0]['sc_country_id'];
			foreach($formCountryOptions as $formCountryOption) {
				if ($formCountryOption['ct_name'] == 'Deutschland') {
					$defaultCountryID = $formCountryOption['sc_country_id'];
				}
				$formCountry->addMultiOption(
					$formCountryOption['sc_country_id'],
					$formCountryOption['ct_name']
				);
			}
		}
		$formCountry->setValue($entity->country_id);
		$this->addElement($formCountry);

		/**
		 * phone
		 */
		$formPhone = new L8M_Form_Element_Phone('phone');
		$formPhone
			->setLabel('Phone')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_Digits(),
			))
			->setValidators(array(
				new Zend_Validate_Digits(),
				new Zend_Validate_StringLength(array(
					'max'=>80,
				))
			))
		;
		$formPhone->setValue($entity->phone);
		$this->addElement($formPhone);


		/**
		 * fax
		 */
		$formFax = new L8M_Form_Element_Phone('fax');
		$formFax
			->setLabel('Fax')
			->setRequired(FALSE)
			->setFilters(array(
				new Zend_Filter_Digits(),
			))
			->setValidators(array(
				new Zend_Validate_Digits(),
				new Zend_Validate_StringLength(array(
					'max'=>80,
				))
			))
		;
		$formFax->setValue($entity->fax);
		$this->addElement($formFax);

		/**
		 * www
		 */
		$formWww = new Zend_Form_Element_Text('www');
		$formWww
			->setLabel('WWW')
			->setRequired(FALSE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_Callback(function($value){
					if (!preg_match("~^(?:f|ht)tps?://~i", $value)) {
						$value = "https://" . $value;
					}
					return $value;
				})
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength(array(
					'max'=>255,
				))
			))
		;
		$formWww->setValue($entity->www);
		$this->addElement($formWww);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerPersonalDataSubmit');
		$formSubmitButton
			->setLabel('Change')
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