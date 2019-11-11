<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/User/Login2.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Login2.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_User_Login2
 *
 *
 */
class Default_Form_User_Login2 extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_User_Login2 instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formUserLogin2');
		$this->setAttrib('class', 'login-form');

		/**
		 * formLogin2
		 */
		$formLogin = new L8M_Form_Element_Email('login2');
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
		$formPassword = new Zend_Form_Element_Password('password2');
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
		$formSubmitButton = new Zend_Form_Element_Submit('formUserLoginSubmit2');
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