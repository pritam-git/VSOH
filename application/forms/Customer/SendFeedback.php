<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Customer/SendFeedback.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SendFeedback.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_Customer_SendFeedback
 *
 *
 */
class Default_Form_Customer_SendFeedback extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Customer_SendFeedback instance.
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
			->setAttrib('id', 'formSendFeedback')
		;

		/**
		 * formName
		 */
		$formName = new Zend_Form_Element_Text('name');
		$formName
			->setLabel('Ihr Name')
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
		$this->addElement($formName);

		/**
		 * formEmail
		 */
		$formEmail = new L8M_Form_Element_Email('email');
		$formEmail
			->setLabel('Ihre Emailadresse')
			->setRequired(TRUE)
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
		 * formFeedback
		 */
		$formFeedback = new Zend_Form_Element_Textarea('feedback');
		$formFeedback
			->setLabel('Ihr Feedback')
			->setRequired(TRUE)
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formFeedback);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formCustomerSendFeedback');
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