<?php

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Validate' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @see Zend_Locale_Format
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Locale' . DIRECTORY_SEPARATOR . 'Format.php';

/**
 * @category   L8M
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class L8M_Validate_Float extends Zend_Validate_Abstract
{
	const INVALID   = 'floatInvalid';
	const NOT_FLOAT = 'notFloat';

	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID   => "Invalid type given. String, integer or float expected",
		self::NOT_FLOAT => "'%value%' does not appear to be a float",
	);

	/**
	 * Constructor for the float validator
	 */
	public function __construct()
	{

	}

	/**
	 * Defined by Zend_Validate_Interface
	 *
	 * Returns true if and only if $value is a floating-point value
	 *
	 * @param  string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		if (!is_string($value) && !is_int($value) && !is_float($value)) {
			$this->_error(self::INVALID);
			return false;
		}

		if (is_float($value)) {
			return true;
		}

		$this->_setValue(L8M_Translate::numeric($value));
		try {
			if (!Zend_Locale_Format::isFloat($value, array('locale' => L8M_Locale::getDefaultSystem()))) {
				$this->_error(self::NOT_FLOAT);
				return false;
			}
		} catch (Zend_Locale_Exception $e) {
			$this->_error(self::NOT_FLOAT);
			return false;
		}

		return true;
	}
}
