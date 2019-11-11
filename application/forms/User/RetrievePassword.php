<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/User/RetrievePassword.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: RetrievePassword.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_User_RetrievePassword
 *
 *
 */
class Default_Form_User_RetrievePassword extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_User_RetrievePassword instance.
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
			->setAttrib('id', 'formUserRetrievePassword')
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
			->setDescription('Enter your email if you forgot your password and want to retrieve a new one.')
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
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerRetrievePassword');
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