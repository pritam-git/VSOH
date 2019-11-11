<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Newsletter/Unsubscribe.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Unsubscribe.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_Newsletter_Unsubscribe
 *
 *
 */
class Default_Form_Newsletter_Unsubscribe extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Newsletter_Unsubscribe instance.
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
			->setAttrib('id', 'formNewsletterUnsubscribe')
		;

		/**
		 * email
		 */
		$formEmail = new L8M_Form_Element_Email('email');
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

		if (L8M_Config::getOption('l8m.newsletter.subscribe.captcha')) {
			$formElementCaptcha = new Zend_Form_Element_Captcha('captcha', array(
				'label'   => '',
				'captcha' => array('captcha' => 'Image',
				'name'    => 'newsletterUnsubscribeCaptcha',
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
			$this->addElement($formElementCaptcha);
		}

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formNewsletterUnsubscribeSubmit');
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