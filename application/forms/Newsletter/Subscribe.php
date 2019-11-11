<?php

/**
 * L8M
 *
 *
 * @filesource /application/forms/Newsletter/Subscribe.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Subscribe.php 75 2014-05-15 09:33:44Z nm $
 */

/**
 *
 *
 * Default_Form_Newsletter_Subscribe
 *
 *
 */
class Default_Form_Newsletter_Subscribe extends L8M_FormExpert
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Newsletter_Subscribe instance.
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
			->setAttrib('id', 'formNewsletterSubscribe')
		;

		$viewFromMvc = Zend_Layout::getMvcInstance()->getView();

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
				'name'    => 'newsletterSubscribeCaptcha',
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
		$formSubmitButton = new Zend_Form_Element_Submit('formNewsletterSubscribeSubmit');
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