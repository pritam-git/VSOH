<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/forms/Newsletter/Tester.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Tester.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Admin_Form_Newsletter_Tester
 *
 *
 */
class Admin_Form_Newsletter_Tester extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes Admin_Form_Newsletter_Tester instance.
	 *
	 * @return void
	 */
	public function init()
	{

		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formAdminNewsletterTester');


		$viewFromMvc = Zend_Layout::getMvcInstance()->getView();

		/**
		 * language short
		 */
		$formLanguage = new L8M_JQuery_Form_Element_Select('language_short');
		$formLanguage
			->setDisableTranslator(TRUE)
			->setLabel($viewFromMvc->translate('Sprache', 'de'))
			->setRequired(TRUE)
		;

		$formLanguage->addMultiOption(
			'',
			'-'
		);

		foreach (L8M_Locale::getSupported() as $langShort) {
			$formLanguage->addMultiOption(
				$langShort,
				$langShort
			);
		}
		$this->addElement($formLanguage);

		/**
		 * salutation
		 */
		$formSalutation = new L8M_JQuery_Form_Element_Select('salutation_id');
		$formSalutation
			->setDisableTranslator(TRUE)
			->setLabel($viewFromMvc->translate('Anrede', 'de'))
			->setRequired(TRUE)
		;


		/**
		 * salutationOptions
		 */
		$formSalutation->addMultiOption(
			'',
			'-'
		);

		$salutationOptionsCollection = Doctrine_Query::create()
			->from('Default_Model_Salutation s')
			->execute()
		;

		foreach($salutationOptionsCollection as $salutationOptionModel) {
			$formSalutation->addMultiOption(
				$salutationOptionModel->id,
				$salutationOptionModel->name
			);
		}
		$this->addElement($formSalutation);

		/**
		 * firstname
		 */
		$formFirstname = new Zend_Form_Element_Text('firstname');
		$formFirstname
			->setLabel('Vorname')
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
			->setLabel('Nachname')
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
		 * email
		 */
		$formEmail = new Zend_Form_Element_Text('email');
		$formEmail
			->setLabel('E-Mail')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_EmailAddress(),
			))
		;
		$this->addElement($formEmail);

		/**
		 * formSubmit
		 */
		$formSubmit = new Zend_Form_Element_Submit('formAdminNewsletterTesterSubmit');
		$formSubmit
			->setLabel('Yes, this is my wish!')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
		;
		$this->addElement($formSubmit);

	}

}