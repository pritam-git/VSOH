<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Validator/GoogleReCaptcha.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleReCaptcha.php 7 2014-03-11 16:18:40Z nm $
 */


/**
 *
 *
 * L8M_Form_Validator_GoogleReCaptcha
 *
 *
 */
class L8M_Form_Validator_GoogleReCaptcha extends Zend_Validate_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * Invalid ID
	 *
	 * @var string
	 */
	const INVALID = 'googleReCaptchaInvalid';
	const MISSING = 'googleReCaptchaMissing';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID => 'Robot verification failed, please try again.',
		self::MISSING => 'Please click on the reCAPTCHA box.'
	);


	/**
	 *
	 *
	 * GoogleReCaptcha Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE when the validation is ok.
	 *
	 * @return bool
	 */
	public function isValid($value)
	{
		$returnValue = FALSE;

		/**
		 * @var Zend_Controller_Request_Abstract
		 */
		$requestObject = Zend_Controller_Front::getInstance()->getRequest();

		if (!$requestObject->getParam('g-recaptcha-response')) {
			$this->_error(self::MISSING);
		} else {
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . L8M_Config::getOption('google.reCaptcha.secret') . '&response=' . $_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if ($responseData->success) {
				$returnValue = TRUE;
			} else {
				$this->_error(self::INVALID);
			}
		}

		return $returnValue;
	}
}