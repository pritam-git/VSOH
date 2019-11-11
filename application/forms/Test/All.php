<?php

/**
 * L8M
 *
 *
 * @filesource /application/form/Test/All.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: All.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Form_Test_All
 *
 *
 */
class Default_Form_Test_All extends L8M_Dojo_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Default_Form_Customer_Register instance.
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
			->setAttrib('id', 'formTestAll');

		/**
         * formHidden
         */
		$formHidden = new Zend_Form_Element_Hidden('id');
       	$formHidden->setDecorators(array('ViewHelper'));
       	$this->addElement($formHidden);

        /**
         * formValidationTextBox
         */
        $formValidationTextBox = new Zend_Dojo_Form_Element_ValidationTextBox('login');
        $formValidationTextBox
        	->setLabel('ValidationTextBox')
			->setPromptMessage($this->getTranslator()->translate('Please enter your desired login.'))
			->setInvalidMessage($this->getTranslator()->translate('The login you entered does not seem to be valid.'))
			->setRegExp(L8M_Validate_Regex_Login::REG_EX_JAVASCRIPT)
			->setRequired(TRUE)
			->setValidators(array(
				new Zend_Validate_Regex(L8M_Validate_Regex_Login::REG_EX),
			))
		;
        $this->addElement($formValidationTextBox);

		/**
		 * formPasswordTextBox
		 */
        $formPasswordTextBox = new Zend_Dojo_Form_Element_PasswordTextBox('password');
        $formPasswordTextBox
        	->setLabel('PasswordTextBox')
		    ->setPromptMessage($this->getTranslator()->translate('Please enter your password.'))
		    ->setInvalidMessage($this->getTranslator()->translate('The password you entered does not seem to be valid.'))
		    ->setRegExp(L8M_Validate_Regex_Password::REG_EX_JAVASCRIPT)
		    ->setRequired(FALSE)
		    ->setValidators(array(
		    	new Zend_Validate_Regex(L8M_Validate_Regex_Password::REG_EX),
		    ))
		;
        $this->addElement($formPasswordTextBox);

        /**
         * formTextBox
         */
        $formTextBox = new Zend_Dojo_Form_Element_TextBox('cityName');
		$formTextBox
			->setLabel('TextBox')
			->setRequired(FALSE);
		$this->addElement($formTextBox);

		/**
         * formCheckBox
         */
        $formCheckBox = new Zend_Dojo_Form_Element_CheckBox('agb_agree');
        $formCheckBox
        	->setLabel('I have read, understood and agree with L8M Hotels and Hostels terms and conditions.')
            ->addValidator('GreaterThan', FALSE, array('min'=>0))
            ->getValidator('GreaterThan')->setMessages(array(Zend_Validate_GreaterThan::NOT_GREATER=>$this->getTranslator()->translate('You have to agree with our terms and conditions, otherwise you cannot proceed.')));
        $this->addElement($formCheckBox);

		/**
         * formDateTextBox
         */
        $formDateTextBox = new Zend_Dojo_Form_Element_DateTextBox('arrival');
        $formDateTextBox
        	->setLabel('DateTextBox')
        	->setConstraint('min', date('Y-m-d'))
        	->setRequired(FALSE);
        $this->addElement($formDateTextBox);

        /**
         * formNumberSpinner
         */
        $formNumberSpinner = new Zend_Dojo_Form_Element_NumberSpinner('persons');
        $formNumberSpinner
        	->setLabel('NumberSpinner')
	        ->setPromptMessage('Please specifiy the number of persons traveling in total.')
	        ->setInvalidMessage('The number of persons you entered does not seem to be valid.')
	        ->setMin(1)
	        ->setMax(10)
	        ->setSmallDelta(1)
	        ->setLargeDelta(2)
	        ->setRangeMessage('You can make a booking request for ' . $formNumberSpinner->getMin() . ' up to ' . $formNumberSpinner->getMax() . ' persons.<br />If you want to make a booking request for a group, click <a href="#" title="Group Bookings">here</a>.')
	        ->setRequired(TRUE);
		$this->addElement($formNumberSpinner);

        /**
         * formFilteringSelect
         */
        $formFilteringSelect = new Zend_Dojo_Form_Element_FilteringSelect('country_id');
        $formFilteringSelect
        	->setLabel('FilteringSelect')
			->setAutocomplete(TRUE)
			->setStoreType('dojo.data.ItemFileReadStore')
			->setStoreId('countryAutoComplete')
			->setStoreParams(array(
				'url'=>'/default/auto-complete/country',
			))
			->setRequired(TRUE);
        $this->addElement($formFilteringSelect);

        /**
         * formCurrencyTextBox
         */
        $formCurrencyTextBox = new Zend_Dojo_Form_Element_CurrencyTextBox('currency');
        $formCurrencyTextBox->setLabel('CurrencyTextBox');
        $this->addElement($formCurrencyTextBox);

        /**
         * formEditor
         */
        $formEditor = new Zend_Dojo_Form_Element_Editor('editor');
        $formEditor->setLabel('Editor');
        $this->addElement($formEditor);

        /**
         * formNumberTextBox
         */
        $formNumberTextBox = new Zend_Dojo_Form_Element_NumberTextBox('number');
        $formNumberTextBox->setLabel('NumberTextBox');
        $this->addElement($formNumberTextBox);

        /**
         * formSimpleTextarea
         */
        $formSimpleTextarea = new Zend_Dojo_Form_Element_SimpleTextarea('simpletextarea');
        $formSimpleTextarea->setLabel('SimpleTextarea');
        $this->addElement($formSimpleTextarea);

        /**
         * formTextarea
         */
        $formTextarea = new Zend_Dojo_Form_Element_Textarea('textarea');
        $formTextarea->setLabel('Textarea');
        $this->addElement($formTextarea);

        /**
         * formFile
         */
        $formFile = new Zend_Form_Element_File('file');
        $formFile->setLabel('File');
        $this->addElement($formFile);

        /**
         * formCaptchaOptions
         *
         * @todo revise
         */
        $captchaConfig = Zend_Registry::get('Zend_Config')->form->captcha;
        $captchaOptions = array(
        	'font'=>$captchaConfig->font,
			'expiration'=>120,
			'gcFreq'=>0,
			'fontSize'=>32,
			'height'=>100,
			'width'=>374,
			'imgDir'=>$captchaConfig->imgDir,
        	'imgUrl'=>$captchaConfig->imgUrl,
			'suffix'=>'.png',
			'dotNoiseLevel'=>20,
			'lineNoiseLevel'=>20,
			'imgAlt'=>'Registration Captcha',
        );
        /**
         * formCaptcha
         */
		$formCaptcha = new Zend_Form_Element_Captcha('customerCaptcha', array('captcha'=>'Image', 'captchaOptions'=>$captchaOptions));
        $formCaptcha
        	->setLabel('Please enter the text shown in the Captcha image.')
            ->setRequired(TRUE)
            ->setValidators(array(
            	new Zend_Validate_Regex('/^.{2,32}$/'),
            ))
		;
        $this->addElement($formCaptcha);

        /**
         * formSubmitButton
         */
        $formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton('customerSubmit');
        $formSubmitButton
        	->setLabel('SubmitButton')
            ->setDecorators(array('DijitElement'))
		;
        $this->addElement($formSubmitButton);
	}
}