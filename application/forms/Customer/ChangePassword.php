<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Customer/ChangePassword.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ChangePassword.php 28 2014-04-02 14:50:33Z nm $
 */

/**
 *
 *
 * Default_Form_Customer_ChangePassword
 *
 *
 */
class Default_Form_Customer_ChangePassword extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Customer_ChangePassword instance.
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
			->setAttrib('id', 'formChangePassword')
		;

		/**
		 * oldpassword for user account
		 */
		$formOldPassword = new Zend_Form_Element_Password('old_password');
		$formOldPassword
			->setLabel('Altes Passwort')
			->setAttrib('class', 'form-control')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_StringLength(array(
					'min'=>3,
					'max'=>34,
				)),
			))
		;
		$this->addElement($formOldPassword);

		/**
		 * password for user account
		 */
		$formPassword = new Zend_Form_Element_Password('password');
		$formPassword
			->setLabel('Passwort')
			->setAttrib('class', 'form-control')
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
		 * password repeat for user account
		 */
		$formPasswordRepeat = new Zend_Form_Element_Password('password_repeat');
		$formPasswordRepeat
			->setLabel('Passwort wiederholen')
			->setAttrib('class', 'form-control')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_StringLength(array(
					'min'=>6,
					'max'=>32,
				)),
			))
		;
		$this->addElement($formPasswordRepeat);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerChangePassword');
		$formSubmitButton
			->setLabel('Speichern')
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