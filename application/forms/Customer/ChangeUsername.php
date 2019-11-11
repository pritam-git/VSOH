<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Customer/ChangeUsername.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ChangeUsername.php 28 2014-04-02 14:50:33Z nm $
 */

/**
 *
 *
 * Default_Form_Customer_ChangeUsername
 *
 *
 */
class Default_Form_Customer_ChangeUsername extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Customer_ChangeUsername instance.
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
			->setAttrib('id', 'formChangeUsername')
		;

		/**
		 * firstname
		 */
		$formFirstName = new Zend_Form_Element_Text('firstname');
		$formFirstName
			->setLabel('Vorname *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formFirstName);

		/**
		 * lastname
		 */
		$formLastname = new Zend_Form_Element_Text('lastname');
		$formLastname
			->setLabel('Nachname *')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
				new Zend_Filter_StripNewlines(),
				new Zend_Filter_StringTrim(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formLastname);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerChangeUsername');
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