<?php
/**
 * @category   L8M
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license	http://framework.zend.com/license/new-bsd	 New BSD License
 */
class L8M_Validate_IdenticalMd5 extends Zend_Validate_Abstract
{
	/**
	 * Error codes
	 * @const string
	 */
	const NOT_SAME	  = 'notSame';
	const MISSING_TOKEN = 'missingToken';

	/**
	 * Error messages
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_SAME	  => "The two given tokens do not match",
		self::MISSING_TOKEN => 'No token was provided to match against',
	);

	/**
	 * @var array
	 */
	protected $_messageVariables = array(
		'token' => '_tokenString'
	);

	/**
	 * Original token against which to validate
	 * @var string
	 */
	protected $_tokenString;
	protected $_token;
	protected $_strict = true;

	/**
	 * Sets validator options
	 *
	 * @param  mixed $token
	 * @return void
	 */
	public function __construct($token = null)
	{
		if ($token instanceof Zend_Config) {
			$token = $token->toArray();
		}

		if (is_array($token) && array_key_exists('token', $token)) {
			if (array_key_exists('strict', $token)) {
				$this->setStrict($token['strict']);
			}

			$this->setToken($token['token']);
		} else if (null !== $token) {
			$this->setToken($token);
		}
	}

	/**
	 * Retrieve token
	 *
	 * @return string
	 */
	public function getToken()
	{
		return $this->_token;
	}

	/**
	 * Set token against which to compare
	 *
	 * @param  mixed $token
	 * @return Zend_Validate_Identical
	 */
	public function setToken($token)
	{
		$this->_tokenString = (string) $token;
		$this->_token	   = $token;
		return $this;
	}

	/**
	 * Returns the strict parameter
	 *
	 * @return boolean
	 */
	public function getStrict()
	{
		return $this->_strict;
	}

	/**
	 * Sets the strict parameter
	 *
	 * @param Zend_Validate_Identical
	 */
	public function setStrict($strict)
	{
		$this->_strict = (boolean) $strict;
		return $this;
	}

	/**
	 * Defined by Zend_Validate_Interface
	 *
	 * Returns true if and only if a token has been set and the provided value
	 * matches that token.
	 *
	 * @param  mixed $value
	 * @param  array $context
	 * @return boolean
	 */
	public function isValid($value, $context = null)
	{
		$this->_setValue((string) $value);

		if (($context !== null) && isset($context) && array_key_exists($this->getToken(), $context)) {
			$token = $context[$this->getToken()];
		} else {
			$token = $this->getToken();
		}

		if ($token === null) {
			$this->_error(self::MISSING_TOKEN);
			return false;
		}

		$strict = $this->getStrict();
		if (($strict && (md5($value) !== $token)) || (!$strict && (md5($value) != $token))) {
			$this->_error(self::NOT_SAME);
			return false;
		}

		return true;
	}
}