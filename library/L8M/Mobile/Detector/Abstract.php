<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mobile/Detector/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mobile_Detector_Abstract
 *
 *
 */
abstract class L8M_Mobile_Detector_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array with options.
	 *
	 * @var array
	 */
	protected $_options = NULL;

	/**
	 * An L8M_Mobile_Detector_Abstract instance
	 *
	 * @var L8M_Mobile_Detector_Abstract
	 */
	protected static $_instance = NULL;

	/**
	 * An array with device properties.
	 *
	 * @var array
	 */
	protected $_properties = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Mobile_Detector_Abstract instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = NULL)
	{
		if ($options) {
			$this->setOptions($options);
		}
		$this->init();

		return $this;
	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets options.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Mobile_Detector_Abstract
	 */
	public function setOptions($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Mobile_Detector_Abstract_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}
		$this->_options = $options;
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Mobile_Detector_Abstract instance.
	 *
	 * @return void
	 */
	abstract public function init();

	/**
	 * Returns TRUE if it is a mobile device.
	 *
	 * @return bool
	 */
	abstract public function isMobileDevice();

	/**
	 * Returns device name.
	 *
	 * @return string
	 */
	abstract public function getDeviceName();

}
