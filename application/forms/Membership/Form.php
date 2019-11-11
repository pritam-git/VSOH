<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Membership/Form.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Form.php 545 2017-08-24 20:24:35Z nm $
 */

/**
 *
 *
 * Default_Form_Membership_Form
 *
 *r
 */
class Default_Form_Membership_Form extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Membership_Form instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formMembership');

		/**
		 * company
		 */
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
					'min'=>3,
					'max'=>80,
				))
			))
		;
		$this->addElement($formCompany);

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
			->leftJoin('s.Translation st')
			->select('s.id, st.name')
			->addWhere('s.disabled = ?', 0)
			->addWhere('st.lang = ?', L8M_Locale::getLang())
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
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
		 * name
		 */
		$formFirstName = new Zend_Form_Element_Text('firstname');
		$formFirstName
			->setLabel('First Name')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty()
			))
		;
		$this->addElement($formFirstName);
		$formLastName = new Zend_Form_Element_Text('lastname');
		$formLastName
			->setLabel('Last Name')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength(array(
					'min'=>3,
					'max'=>80,
				))
			))
		;
		$this->addElement($formLastName);

		/**
		 * title
		 */
		$formTitle = new Zend_Form_Element_Text('title');
		$formTitle
			->setLabel('Title')
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_StringLength(array(
					'min'=>2,
					'max'=>80,
				))
			))
		;
		$this->addElement($formTitle);

		/**
		 * address
		 */
		$formAddressLine1 = new Zend_Form_Element_Text('address_line_1');
		$formAddressLine1
			->setLabel('Addresse 1')
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
					'max'=>255,
				))
			))
		;
		$this->addElement($formAddressLine1);
		$formAddressLine2 = new Zend_Form_Element_Text('address_line_2');
		$formAddressLine2
			->setLabel('Adresse 2 Postfach')
			->setAttrib('class', 'form-control')
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
			->setLabel('Zip')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_Alnum(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alnum(TRUE),
//				new Zend_Validate_StringLength(array(
//					'max'=>16,
//				)),
			))
		;
		$this->addElement($formZip);

		/**
		 * city
		 */
		$formZip = new Zend_Form_Element_Text('city');
		$formZip
			->setLabel('City')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_Alnum(TRUE),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_Alnum(TRUE),
				new Zend_Validate_StringLength(array(
					'min'=>2,
					'max'=>80,
				)),
			))
		;
		$this->addElement($formZip);

		/**
		 * country
		 */
		$formCountry = new L8M_JQuery_Form_Element_Select('country_id');
		$formCountry
			->setLabel($this->getView()->translate('Country', 'en'))
			->setRequired(TRUE)
			->setDisableTranslator(TRUE)
			->setAttrib('class', 'form-control')
		;

		/**
		 * countryOptions
		 */
		$formCountryOptions = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->leftJoin('c.Translation ct')
			->select('c.id, ct.name')
			->orderBy('ct.name ASC')
			->addWhere('ct.lang = ?', L8M_Locale::getLang())
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		$formCountry->addMultiOption('', '-');

		if (is_array($formCountryOptions) &&
			count($formCountryOptions) > 0) {

			foreach($formCountryOptions as $formCountryOption) {
				$formCountry->addMultiOption(
					$formCountryOption['c_id'],
					$formCountryOption['ct_name']
				);
			}
		}
		$this->addElement($formCountry);

		/**
		 * kanton
		 */
		/* $formKanton = new L8M_JQuery_Form_Element_Select('kanton_id');
		$formKanton
			->setLabel($this->getView()->translate('Kanton', L8M_Locale::getLang()))
			->setDisableTranslator(TRUE)
			->setAttrib('class', 'form-control')
		; */

		/**
		 * kantonOptions
		 */
		/* $formKantonOptions = Doctrine_Query::create()
			->from('Default_Model_Kanton k')
			->select('k.id, k.name')
			->orderBy('k.name ASC')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		$formKanton->addMultiOption('', '-');

		if (is_array($formKantonOptions) &&
			count($formKantonOptions) > 0) {

			foreach($formKantonOptions as $formKantonOption) {
				$formKanton->addMultiOption(
					$formKantonOption['k_id'],
					$formKantonOption['k_name']
				);
			}
		}
		$this->addElement($formKanton); */

		/**
		 * contract type
		 */
		/* $formContractType = new L8M_JQuery_Form_Element_Select('contract_type_id');
		$formContractType
			->setLabel($this->getView()->translate('Contract Type', 'en'))
			->setDisableTranslator(TRUE)
			->setAttrib('class', 'form-control')
		; */

		/**
		 * contractTypeOptions
		 */
		/* $formContractTypeOptions = Doctrine_Query::create()
			->from('Default_Model_ContractType ct')
			->select('ct.id, ct.name')
			->orderBy('ct.name ASC')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		$formContractType->addMultiOption('', '-');

		if (is_array($formContractTypeOptions) &&
			count($formContractTypeOptions) > 0) {

			foreach($formContractTypeOptions as $formContractTypeOption) {
				$formContractType->addMultiOption(
					$formContractTypeOption['ct_id'],
					$formContractTypeOption['ct_name']
				);
			}
		}
		$this->addElement($formContractType); */

		/**
		 * email
		 */
		$formEmail = new L8M_Form_Element_Email('email');
		$formEmail
			->setLabel('Emailaddress')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_EmailAddress(),
			))
		;
		$this->addElement($formEmail);

		/**
		 * www
		 */
		$formWww = new Zend_Form_Element_Text('www');
		$formWww
			->setLabel('WWW')
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
					'max'=>255,
				))
			))
		;
		$this->addElement($formWww);

		/**
		 * manager-phone
		 */
		$formManagerPhone = new L8M_Form_Element_Phone('manager_phone');
		$formManagerPhone
			->setLabel('Manager Phone')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
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
		$this->addElement($formManagerPhone);

		/**
		 * mobile
		 */
		$formPhone = new L8M_Form_Element_Phone('mobile');
		$formPhone
			->setLabel('Mobile')
			->setAttrib('class', 'form-control')
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
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
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
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formMembershipSubmit');
		$formSubmitButton
			->setLabel('Submit')
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