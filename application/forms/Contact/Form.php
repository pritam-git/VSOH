<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Contact/Form.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Form.php 545 2017-08-24 20:24:35Z nm $
 */

/**
 *
 *
 * Default_Form_Contact_Form
 *
 *r
 */
class Default_Form_Contact_Form extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Contact_Form instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formContact');

		/**
		 * name
		 */
		$formName = new Zend_Form_Element_Text('name');
		$formName
			->setLabel('Name')
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
		$this->addElement($formName);


		/**
		 * formEmail
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
		 * formMessage
		 */
		$formMessage = new Zend_Form_Element_Textarea('message');
		$formMessage
			->setLabel('Your Message')
			->setRequired(TRUE)
			->setAttrib('class', 'form-control')
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formMessage);

		if (L8M_Config::getOption('l8m.contact.captcha')) {
			if (L8M_Config::getOption('l8m.contact.useGoogleReCaptcha')) {
				$formElementCaptcha = new L8M_Form_Element_GoogleReCaptcha('captcha');
			} else {
				$formElementCaptcha = new Zend_Form_Element_Captcha('captcha', array(
					'label'   => '',
					'captcha' => array('captcha' => 'Image',
					'name'    => 'contactCaptcha',
					'wordLen' => 5,
					'timeout' => 300,
					'font'    => BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'monofont.ttf',
					'imgDir'  => PUBLIC_PATH . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR,
					'imgUrl'  => DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR)
				));
				$formElementCaptcha
					->setAttrib('autocomplete', 'off')
					->setDecorators(array(
						new Zend_Form_Decorator_Errors(),
						new Zend_Form_Decorator_Description(),
						new Zend_Form_Decorator_HtmlTag(array(
							'tag'=>'div',
							'id'=>'captcha-element',
						)),
					))
				;
			}
			$this->addElement($formElementCaptcha);
		}

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formContactSubmit');
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