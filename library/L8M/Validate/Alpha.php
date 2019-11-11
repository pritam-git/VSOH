<?php

require_once 'Zend' . DIRECTORY_SEPARATOR . 'Validate' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @category     L8M
 * @package      Zend_Validate
 * @copyright    Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license      http://framework.zend.com/license/new-bsd	 New BSD License
 */
class L8M_Validate_Alpha extends Zend_Validate_Abstract
{
	const INVALID	  	= 'alphaInvalid';
	const NOT_ALPHA		= 'notAlpha';
	const STRING_EMPTY 	= 'alphaStringEmpty';

	/**
	 * Whether to allow white space characters; off by default
	 *
	 * @var boolean
	 * @deprecated
	 */
	public $allowWhiteSpace;

	/**
	 * Alphabetic filter used for validation
	 *
	 * @var Zend_Filter_Alpha
	 */
	protected static $_filter = null;

	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID	  	=> "Invalid type given. String expected",
		self::NOT_ALPHA		=> "'%value%' contains non alphabetic characters",
		self::STRING_EMPTY 	=> "'%value%' is an empty string"
	);

	/**
	 * Sets default option values for this instance
	 *
	 * @param  boolean|Zend_Config $allowWhiteSpace
	 * @return void
	 */
	public function __construct($allowWhiteSpace = false)
	{
		if ($allowWhiteSpace instanceof Zend_Config) {
			$allowWhiteSpace = $allowWhiteSpace->toArray();
		}

		if (is_array($allowWhiteSpace)) {
			if (array_key_exists('allowWhiteSpace', $allowWhiteSpace)) {
				$allowWhiteSpace = $allowWhiteSpace['allowWhiteSpace'];
			} else {
				$allowWhiteSpace = false;
			}
		}

		$this->allowWhiteSpace = (boolean) $allowWhiteSpace;

		$view = Zend_Layout::getMvcInstance()->getView();

		$this->_messageTemplates[self::INVALID] = $view->translate("Invalid type given. String expected");
		$this->_messageTemplates[self::NOT_ALPHA] = $view->translate("'%value%' contains non alphabetic characters");
		$this->_messageTemplates[self::STRING_EMPTY] = $view->translate("'%value%' is an empty string");
	}

	/**
	 * Returns the allowWhiteSpace option
	 *
	 * @return boolean
	 */
	public function getAllowWhiteSpace()
	{
		return $this->allowWhiteSpace;
	}

	/**
	 * Sets the allowWhiteSpace option
	 *
	 * @param boolean $allowWhiteSpace
	 * @return Zend_Filter_Alpha Provides a fluent interface
	 */
	public function setAllowWhiteSpace($allowWhiteSpace)
	{
		$this->allowWhiteSpace = (boolean) $allowWhiteSpace;
		return $this;
	}

	/**
	 * Defined by Zend_Validate_Interface
	 *
	 * Returns true if and only if $value contains only alphabetic characters
	 *
	 * @param  string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		if (!is_string($value)) {
			$this->_error(self::INVALID);
			return false;
		}

		$this->_setValue($value);

		if ('' === $value) {
			$this->_error(self::STRING_EMPTY);
			return false;
		}

		if (null === self::$_filter) {
			/**
			 * @see Zend_Filter_Alpha
			 */
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'Filter' . DIRECTORY_SEPARATOR . 'Alpha.php';
			self::$_filter = new Zend_Filter_Alpha();
		}

		self::$_filter->allowWhiteSpace = $this->allowWhiteSpace;

		if ($value !== self::$_filter->filter($value)) {
			$this->_error(self::NOT_ALPHA);
			return false;
		}

		return true;
	}

}
