<?php

require_once 'Zend' . DIRECTORY_SEPARATOR . 'Validate' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @category     L8M
 * @package      Zend_Validate
 * @copyright    Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license      http://framework.zend.com/license/new-bsd	 New BSD License
 */
class L8M_Validate_Date extends Zend_Validate_Abstract
{
	const INVALID	= 'dateInvalid';

	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID	=> "String is not a valid date format. Use something like YYYY-MM-DD",
	);


	/**
	 * Constructor
	 *
	 * @param string|array|Zend_Config $options OPTIONAL
	 */
	public function __construct($options = null)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		} else if (!is_array($options)) {
			$options = func_get_args();
			$temp	= array();
			if (!empty($options)) {
				$temp['type'] = array_shift($options);
			}

			$options = $temp;
		}

		if (is_array($options) && array_key_exists('type', $options)) {
			$this->setType($options['type']);
		}

		$view = Zend_Layout::getMvcInstance()->getView();

		$this->_messageTemplates[self::INVALID] = $this->_messageTemplates[self::INVALID];
	}

	/**
	 * Defined by Zend_Validate_Interface
	 *
	 * Returns true if and only if $value is not an empty value.
	 *
	 * @param	string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		$returnValue = FALSE;

		$this->_setValue($value);

		$arr = explode('-', $value);
		if (is_array($arr) &&
			count($arr) == 3 &&
			checkdate($arr[1], $arr[2], $arr[0])) {

			$returnValue = TRUE;
		} else {
			$this->_error(self::INVALID);
		}

		return $returnValue;
	}
}
