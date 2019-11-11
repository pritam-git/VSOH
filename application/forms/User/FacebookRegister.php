<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/User/FacebookRegister.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FacebookRegister.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_User_FacebookRegister
 *
 *
 */

class Default_Form_User_FacebookRegister extends L8M_FormExpert
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
		$fbUser = PRJ_Facebook::getMe();

		/**
		 * email
		 */
		$formEmail = new L8M_Form_Element_Email('email');
		$formEmail
			->setLabel('Email')
			->setRequired(TRUE)
			->setValue($fbUser['email'])
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

		$formSalutation->setValue(PRJ_Facebook::getSalutation());
		$this->addElement($formSalutation);

		/**
		 * firstname
		 */
		$formFirstname = new Zend_Form_Element_Text('firstname');
		$formFirstname
			->setLabel('First Name')
			->setValue($fbUser['first_name'])
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
ä		 * lastname
		 */
		$formLastname = new Zend_Form_Element_Text('lastname');
		$formLastname
			->setLabel('Last Name')
			->setValue($fbUser['last_name'])
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
		 * country
		 */

		$countryName = NULL;

		$countryCode = explode('_', $fbUser['locale']);
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country m')
			->addWhere('m.iso_2 = ?', array(end($countryCode)))
			->limit(1)
			->execute()
			->getFirst()
		;

		if ($countryModel){
			$countryName = $countryModel->name_local;
		}

		$formCountry = new Zend_Form_Element_Text('country');
		$formCountry
			->setLabel('Country')
			->setValue($countryName)
			->setRequired(FALSE)
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
		$this->addElement($formCountry);

		/**
		 * www
		 */
		$formWww = new Zend_Form_Element_Text('www');
		$formWww
			->setLabel('WWW')
			->setValue($fbUser['link'])
			->setRequired(FALSE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
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
		 * password
		 */

		$passwordValue = L8M_Library::generatePassword(8);

		$formPassword = new Zend_Form_Element_Hidden('password');
		$formPassword
			->setValue($passwordValue)
			->setRequired(TRUE)
		;
		$this->addElement($formPassword);


		$formPasswordRepeated = new Zend_Form_Element_Hidden('password_repeated');
		$formPasswordRepeated
			->setValue($passwordValue)
			->setRequired(TRUE)
		;
		$this->addElement($formPasswordRepeated);

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