<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/User/Login.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Login.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_User_Login
 *
 *
 */
class Default_Form_User_Login extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_User_Login instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formUserLogin');

		/**
		 * formLogin
		 */
		$formLogin = new L8M_Form_Element_Email('login');
		$formLogin
			->setLabel('Login')
			->setAttrib('class', 'form-control')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formLogin);

		/**
		 * formPassword
		 */
		$formPassword = new Zend_Form_Element_Password('password');
		$formPassword
			->setLabel('Password')
			->setAttrib('class', 'form-control')
			->setRequired(TRUE)
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formPassword);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formUserLoginSubmit');
		$formSubmitButton
			->setLabel('Login')
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