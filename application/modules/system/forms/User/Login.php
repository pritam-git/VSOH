<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/forms/User/Login.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Login.php 547 2017-08-24 22:11:03Z nm $
 */

/**
 *
 *
 * System_Form_User_Login
 *
 *
 */
class System_Form_User_Login extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_Form_User_Login instance.
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
		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != L8M_Environment::getInstance()->getEnvironment()) {
			$formLogin = new Zend_Form_Element_Text('login');
		} else {
			$formLogin = new Zend_Form_Element_Select('login');
			$formLogin->setDisableTranslator(TRUE);

			/**
			 * EntityAdmin
			 */
			$formEntityOptions = Doctrine_Query::create()
				->from('Default_Model_EntityAdmin m')
				->select('m.login')
				->addWhere('m.disabled = ? ', array(FALSE))
				->addWhere('m.activated_at IS NOT NULL', array())
				->orderBy('m.login ASC')
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->execute()
			;

			foreach($formEntityOptions as $formEntityOption) {
				$formLogin->addMultiOption(
					$formEntityOption['m_login'],
					$formEntityOption['m_login']
				);
			}
		}
		$formLogin
			->setLabel('Login')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formLogin);

		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != L8M_Environment::getInstance()->getEnvironment()) {

			/**
			 * formPassword
			 */
			$formPassword = new Zend_Form_Element_Password('password');
			$formPassword
				->setLabel('Password')
				->setRequired(TRUE)
				->setValidators(array(
					new Zend_Validate_NotEmpty(),
				))
			;
			$this->addElement($formPassword);
		}

		/**
		 * formCaptcha
		 */
		if (L8M_Config::getOption('authentication.backEnd.captcha.enabled')) {
			if (L8M_Config::getOption('authentication.backEnd.captcha.useGoogleReCaptcha')) {
				$formElementCaptcha = new L8M_Form_Element_GoogleReCaptcha('captcha');
			} else {
				$formElementCaptcha = new Zend_Form_Element_Captcha('captcha', array(
					'label'=>'',
					'captcha'=>array(
						'captcha'=>'Image',
						'name'=>'contactCaptcha',
						'width'=>200,
						'height'=>80,
						'wordLen'=>5,
						'timeout'=>300,
						'dotNoiseLevel'=>180,
						'lineNoiseLevel'=>18,
						'textColor'=>array(
							'red'=>94,
							'green'=>151,
							'blue'=>202,
						),
						'backgroundColor'=>array(
							'red'=>248,
							'green'=>248,
							'blue'=>248,
						),
						'font'=>BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'monofont.ttf',
						'fontSize'=>32,
						'imgDir'=>PUBLIC_PATH . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR,
						'imgUrl'=>DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR)
					)
				);
				$formElementCaptcha
					->setDecorators(array(
						new Zend_Form_Decorator_Errors(),
						new Zend_Form_Decorator_Description(),
						new Zend_Form_Decorator_HtmlTag(array(
							'tag'=>'dd',
							'id'=>'captcha-element',
						)),
					))
				;
			}
			$this->addElement($formElementCaptcha);
		}

		/**
		 * formLatitude
		 */
		$formElement = new Zend_Form_Element_Hidden('lat');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formLongitude
		 */
		$formElement = new Zend_Form_Element_Hidden('lon');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formAccuracy
		 */
		$formElement = new Zend_Form_Element_Hidden('acc');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formAltitude
		 */
		$formElement = new Zend_Form_Element_Hidden('alt');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formAltitudeAccuracy
		 */
		$formElement = new Zend_Form_Element_Hidden('altacc');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formHeading
		 */
		$formElement = new Zend_Form_Element_Hidden('hea');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formSpeed
		 */
		$formElement = new Zend_Form_Element_Hidden('spe');
		$formElement
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formSubmitButton
		 */
		if (!L8M_Config::getOption('authentication.backEnd.geoPositioning.enabled')) {
			$formSubmitButton = new Zend_Form_Element_Submit('formUserLoginSubmit');
			$formSubmitButton
				->setLabel('Login')
				->setDecorators(array(
					new Zend_Form_Decorator_ViewHelper(),
					new Zend_Form_Decorator_HtmlTag(array(
						'tag'=>'dd',
					)),
				))
			;
			$this->addElement($formSubmitButton);
		}
	}
}