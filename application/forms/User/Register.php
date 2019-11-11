<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/User/Register.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Register.php 497 2016-04-15 13:39:38Z nm $
 */

/**
 *
 *
 * Default_Form_User_Register
 *
 *
 */
class Default_Form_User_Register extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_User_Register instance.
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
			->setAttrib('id', 'formUserRegister')
		;

		/**
		 *
		 *
		 * login
		 *
		 *
		 */

		/**
		 * email
		 */
		$formEmail = new L8M_Form_Element_Email('email');
		$formEmail
			->setLabel('Email')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_EmailAddress(),
				new Zend_Validate_Db_NoRecordExists(array(
					'table'=>'entity',
					'field'=>'login',
					'adapter'=>Zend_Registry::get('databaseDefault'),
				))
			))
		;
		$this->addElement($formEmail);

		/**
		 * password
		 */
		$formPassword = new Zend_Form_Element_Password('password');
		$formPassword
			->setLabel('Password')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_StringLength(array(
					'min'=>8,
					'max'=>34,
				)),
			))
		;
		$this->addElement($formPassword);

		/**
		 * passwordRepeated
		 */
		$formPasswordRepeated = new Zend_Form_Element_Password('password_repeated');
		$formPasswordRepeated
			->setLabel('Retype Password')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_StringLength(array(
					'min'=>8,
					'max'=>34,
				)),
			))
		;
		$this->addElement($formPasswordRepeated);

		/**
		 *
		 *
		 * details
		 *
		 *
		 */

		if (L8M_Config::getOption('shop.company.enabled')) {

			/**
			 * company
			 */
			$formCompany = new Zend_Form_Element_Text('company');
			$formCompany
				->setLabel('Company')
				->setFilters(array(
					new Zend_Filter_StripTags(),
					new Zend_Filter_StripNewlines(),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_StringLength(array(
						'min'=>3,
						'max'=>80,
					))
				))
			;
			$this->addElement($formCompany);

		}

		/**
		 * salutation
		 */
		$formSalutation = new L8M_JQuery_Form_Element_Select('salutation_id');
		$formSalutation
			->setLabel('Salutation')
			->setRequired(TRUE)
		;

		/**
		 * salutationOptions
		 */
		$salutationOptions = Doctrine_Query::create()
			->from('Default_Model_Salutation s')
			->select('s.id, st.name')
			->leftJoin('s.Translation st')
			->addWhere('s.disabled = ?', 0)
			->addWhere('st.lang = ?', L8M_Locale::getLang())
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute(array())
		;

		$formSalutation->addMultiOption('', '-');

		if (is_array($salutationOptions) &&
			count($salutationOptions)>0) {
			foreach($salutationOptions as $salutationOption) {
				$formSalutation->addMultiOption(
					$salutationOption['s_id'],
					$salutationOption['st_name']
				);
			}
		}
		$this->addElement($formSalutation);

		/**
		 * firstname
		 */
		$formFirstname = new Zend_Form_Element_Text('firstname');
		$formFirstname
			->setLabel('First Name')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_StringLength(array(
					'min'=>3,
					'max'=>80,
				))
			))
		;
		$this->addElement($formFirstname);

		/**
		 * lastname
		 */
		$formLastname = new Zend_Form_Element_Text('lastname');
		$formLastname
			->setLabel('Last Name')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_StringLength(array(
					'min'=>3,
					'max'=>80,
				))
			))
		;
		$this->addElement($formLastname);

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
		$this->addElement($formCity);

		/**
		 * country
		 */
		$formCountry = new L8M_JQuery_Form_Element_Select('country_id');
		$formCountry
			->setLabel('Country')
			->setRequired(TRUE)
			->setDisableTranslator(TRUE)
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
// 		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT == L8M_Environment::getInstance()->getEnvironment()) {
// 			$formCountryOptions = $formCountryOptions
// 				->addWhere('sc.country_id = ? ', array(54))
// 			;
// 		}
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
			$formCountry->setValue($defaultCountryID);
		}
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
		$this->addElement($formWww);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerRegisterSubmit');
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