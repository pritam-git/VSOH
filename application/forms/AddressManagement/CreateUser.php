<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/AddressManagement/CreateUser.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: CreateUser.php 497 2018-12-06 12:45:40Z nm $
 */

/**
 *
 *
 * Default_Form_AddressManagement_CreateUser
 *
 *
 */
class Default_Form_AddressManagement_CreateUser extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_AddressManagement_CreateUser instance.
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
			->setAttrib('id', 'formUserCreate')
		;
	}

	/**
	 * create form
	 * @param array $values
	 * @param null $do
	 */
	public function buildUp($values = array(),$do = NULL)
	{
		/**
		 *
		 *
		 * login
		 *
		 *
		 */
		if ($do == 'request') {
			$submitLabel = 'Anfrage auf Änderung';
		} else
		if ($do == 'send') {
			$do = NULL;
			$submitLabel = 'Zugang senden';
		} else {
			$submitLabel = 'Speichern';
		}

		$validatorArray = array(
			'table'=>'entity',
			'field'=>'login',
			'adapter'=>Zend_Registry::get('databaseDefault')
		);

		if(isset($values['id'])){
			$validatorArray['exclude'] = array('field'=>'id','value'=>$values['id']);
		}

		/**
		 * email
		 */
		$formEmail = new L8M_Form_Element_Email('email');
		$formEmail
			->setLabel('Email')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_EmailAddress(),
				new Zend_Validate_Db_NoRecordExists($validatorArray)
			))
		;
		if (isset($values['email'])) {
			$formEmail->setValue($values['email']);
		}
		$this->addElement($formEmail);

//		/**
//		 * password
//		 */
//		$formPassword = new Zend_Form_Element_Password('password');
//		$formPassword
//			->setLabel('Password')
//			->setRequired(TRUE)
//			->setAttrib('class', 'form-control')
//			->setFilters(array(
//				new Zend_Filter_StringTrim(),
//			))
//			->setValidators(array(
//				new Zend_Validate_StringLength(array(
//					'min'=>8,
//					'max'=>34,
//				)),
//			))
//		;
//		$this->addElement($formPassword);

		/**
		 *
		 *
		 * details
		 *
		 *
		 */

		/**
		 * salutation
		 */
		$formSalutation = new L8M_JQuery_Form_Element_Select('salutation_id');
		$formSalutation
			->setLabel('Salutation')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
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
		if (isset($values['salutation_id'])) {
			$formSalutation->setValue($values['salutation_id']);
		}
		$this->addElement($formSalutation);

		/**
		 * firstname
		 */
		$formFirstname = new Zend_Form_Element_Text('firstname');
		$formFirstname
			->setLabel('Firstname')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
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
		if (isset($values['firstname'])) {
			$formFirstname->setValue($values['firstname']);
		}
		$this->addElement($formFirstname);

		/**
		 * lastname
		 */
		$formLastname = new Zend_Form_Element_Text('lastname');
		$formLastname
			->setLabel('Lastname')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
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
		if (isset($values['lastname'])) {
			$formLastname->setValue($values['lastname']);
		}
		$this->addElement($formLastname);

		/**
		 * department
		 */
		if($do != 'request') {
			$formDepartment = new L8M_JQuery_Form_Element_Select('department_id');
			$formDepartment
				->setLabel('Department')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control');

			/**
			 * departmentOptions
			 */
			$departmentOptions = Doctrine_Query::create()
				->from('Default_Model_Department d')
				->select('d.id, dt.title')
				->leftJoin('d.Translation dt')
				->addWhere('dt.lang = ?', L8M_Locale::getLang())
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->execute(array());
			$formDepartment->addMultiOption('', '-');
			if (is_array($departmentOptions) &&
				count($departmentOptions) > 0
			) {
				foreach ($departmentOptions as $departmentOption) {
					$formDepartment->addMultiOption(
						$departmentOption['d_id'],
						$departmentOption['dt_title']
					);
				}
			}
			if (isset($values['department_id'])) {
				$formDepartment->setValue($values['department_id']);
			}
			$this->addElement($formDepartment);
		}

		/**
		 * Address1
		 */
		if($do == 'request') {
			$formStreet = new Zend_Form_Element_Text('street');
			$formStreet
				->setLabel('Street')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_StripTags(),
					new Zend_Filter_StripNewlines(),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_NotEmpty(),
					new Zend_Validate_StringLength(array(
						'max' => 255,
					))
				));
			if (isset($values['street'])) {
				$formStreet->setValue($values['street']);
			}
			$this->addElement($formStreet);
		}

//		/**
//		 * street number
//		 */
//		$formStreetNumber = new Zend_Form_Element_Text('street_number');
//		$formStreetNumber
//			->setLabel('House Number')
//			->setRequired(TRUE)
//			->setAttrib('class', 'form-control')
//			->setFilters(array(
//				new Zend_Filter_Alnum(TRUE),
//				new Zend_Filter_StringTrim(),
//			))
//			->setValidators(array(
//				new Zend_Validate_Alnum(TRUE),
//				new Zend_Validate_StringLength(array(
//					'min'=>1,
//					'max'=>5,
//				)),
//			))
//		;
//		if (isset($values['street_number'])) {
//			$formStreetNumber->setValue($values['street_number']);
//		}
//		$this->addElement($formStreetNumber);

		/**
		 * Plz (zip)
		 */
		if($do == 'request') {
			$formZip = new Zend_Form_Element_Text('zip');
			$formZip
				->setLabel('Zip')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_Alnum(TRUE),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_Alnum(TRUE),
					new Zend_Validate_StringLength(array(
						'max' => 16,
					)),
				));
			if (isset($values['zip'])) {
				$formZip->setValue($values['zip']);
			}
			$this->addElement($formZip);
		}

		/**
		 * Ort (city)
		 */
		if($do == 'request') {
			$formCity = new Zend_Form_Element_Text('city');
			$formCity
				->setLabel('City')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_Alpha(TRUE),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_Alpha(TRUE),
				));
			if (isset($values['city'])) {
				$formCity->setValue($values['city']);
			}
			$this->addElement($formCity);
		}

		/**
		 * address2 Postfach
		 */
		if($do == 'request') {
			$formAddressLine1 = new Zend_Form_Element_Text('address_line_1');
			$formAddressLine1
				->setLabel('Address line 1')
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_StripTags(),
					new Zend_Filter_StripNewlines(),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_NotEmpty(),
					new Zend_Validate_StringLength(array(
						'max' => 255,
					))
				));
			if (isset($values['address_line_1'])) {
				$formAddressLine1->setValue($values['address_line_1']);
			}
			$this->addElement($formAddressLine1);
		}

//		/**
//		 * country
//		 */
//		$formCountry = new L8M_JQuery_Form_Element_Select('country_id');
//		$formCountry
//			->setLabel('Country')
//			->setRequired(TRUE)
//			->setAttrib('class', 'form-control')
//			->setDisableTranslator(TRUE)
//		;
//
//		/**
//		 * countryOptions
//		 */
//		$formCountryOptions = Doctrine_Query::create()
//			->from('Default_Model_Country c')
//			->leftJoin('c.Translation ct')
//			->select('c.id, ct.name')
//			->addWhere('ct.lang = ?', L8M_Locale::getLang())
//			->orderBy('ct.name ASC')
//			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
//		;
//		$formCountryOptions = $formCountryOptions
//			->execute(array())
//		;
//		if (is_array($formCountryOptions) &&
//			count($formCountryOptions) > 0) {
//			$defaultCountryID = $formCountryOptions[0]['c_id'];
//			foreach($formCountryOptions as $formCountryOption) {
//				if ($formCountryOption['ct_name'] == 'Deutschland') {
//					$defaultCountryID = $formCountryOption['c_id'];
//				}
//				$formCountry->addMultiOption(
//					$formCountryOption['c_id'],
//					$formCountryOption['ct_name']
//				);
//			}
////			$formCountry->setValue($defaultCountryID);
//		}
//		if (isset($values['country_id'])) {
//			$formCountry->setValue($values['country_id']);
//		}
//		$this->addElement($formCountry);

		/**
		 * phone
		 */
		$formPhone = new L8M_Form_Element_Phone('phone');
		$formPhone
			->setLabel('Phone')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
//				new Zend_Filter_Digits(),
			))
			->setValidators(array(
//				new Zend_Validate_Digits(),
				new Zend_Validate_Regex('/^[0-9\-\(\)\/\+\s]*$/'),
				new Zend_Validate_StringLength(array(
					'max'=>80,
				))
			))
		;
		if (isset($values['phone'])) {
			$formPhone->setValue($values['phone']);
		}
		$this->addElement($formPhone);

		/**
		 * fax
		 */
		if($do == 'request') {
			$formFax = new L8M_Form_Element_Phone('fax');
			$formFax
				->setLabel('Fax')
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_Digits(),
				))
				->setValidators(array(
					new Zend_Validate_Digits(),
					new Zend_Validate_StringLength(array(
						'max' => 80,
					))
				));
			if (isset($values['fax'])) {
				$formFax->setValue($values['fax']);
			}
			$this->addElement($formFax);
		}

		/**
		 * www
		 */
		if($do == 'request') {
			$formWww = new Zend_Form_Element_Text('www');
			$formWww
				->setLabel('Www')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control')
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
						'max' => 255,
					)),
//					new Zend_Validate_Callback(function($value){
//						$uri = Zend_Uri::factory($value);
//						return $uri->valid();
//					})
				));
			if (isset($values['www'])) {
				$formWww->setValue($values['www']);
			}
			$this->addElement($formWww);
		}

		/**
		 * company
		 */
		if($do == 'request') {
			$formCompany = new Zend_Form_Element_Text('company');
			$formCompany
				->setLabel('Company')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_StripTags(),
					new Zend_Filter_StripNewlines(),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_StringLength(array(
						'min' => 3,
						'max' => 80,
					))
				));
			if (isset($values['company'])) {
				$formCompany->setValue($values['company']);
			}
			$this->addElement($formCompany);
		}

		/**
		 * gl
		 */
		if($do == 'request') {
			$formGl = new Zend_Form_Element_Text('gl');
			$formGl
				->setLabel('Gl')
				->setRequired(TRUE)
				->setAttrib('class', 'form-control')
				->setFilters(array(
					new Zend_Filter_StripTags(),
					new Zend_Filter_StripNewlines(),
					new Zend_Filter_StringTrim(),
				))
				->setValidators(array(
					new Zend_Validate_StringLength(array(
						'max' => 45,
					)),
				));
			if (isset($values['gl'])) {
				$formGl->setValue($values['gl']);
			}
			$this->addElement($formGl);
		}

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCreateUserSubmit');
		$formSubmitButton
			->setLabel($submitLabel)
			->setAttrib('class', 'btn btn-warning')
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