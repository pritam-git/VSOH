<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mobile/Detector/WURFL.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: WURFL.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mobile_Detector_WURFL
 *
 *
 */
class L8M_Mobile_Detector_WURFL extends L8M_Mobile_Detector_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A WURFL_WURFLManager instance.
	 *
	 * @var WURFL_WURFLManager
	 */
	protected static $_wurflManagerInstance = NULL;

	/**
	 * A WURFL_Device instance.
	 *
	 * @var WURFL_Device
	 */
	protected $_device = NULL;

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_Mobile_Detector_Mobi instance.
	 *
	 * @return void
	 */
	public function init()
	{
		$this->_device = $this->_getWURFLManager()->getDeviceForHttpRequest($_SERVER);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Sets options.
     *
     * @param  array|Zend_Config $options
     * @return L8M_Mobile_Detector_WURFL
     */
    public function setOptions($options = NULL)
    {
    	/**
    	 * options
    	 */
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            throw new L8M_Mobile_Detector_WURFL_Exception('Options need to be specified as an array or a Zend_Config instance.');
        }

        /**
         * path to WURFL library
         */
        if (!isset($options['library']['path'])) {
        	throw new L8M_Mobile_Detector_WURFL_Exception('Path to WURFL library needs to be specified.');
        }

    	/**
         * path to WURFL resources
         */
        if (!isset($options['resource']['path'])) {
        	throw new L8M_Mobile_Detector_WURFL_Exception('Path to WURFL resources needs to be specified.');
        }

    	/**
         * path to WURFL config
         */
        if (!isset($options['config']['path'])) {
        	throw new L8M_Mobile_Detector_WURFL_Exception('Path to WURFL configuration needs to be specified.');
        }

        $this->_options = $options;

        return $this;

    }

	/**
	 * Returns device name.
	 *
	 * @return string
	 */
	public function getDeviceName()
	{
		$deviceName = $this->_device->getCapability('brand_name')
					. ' '
					. $this->_device->getCapability('model_name')
		;
		$deviceName = ucfirst($deviceName);

		return $deviceName;
	}

	/**
	 * Returns TRUE if it is a mobile device.
	 *
	 * @return bool
	 */
	public function isMobileDevice()
	{

		if ($this->_device->getCapability('is_wireless_device') === 'true') {
			return TRUE;
		}

		return FALSE;

	}

	/**
     *
     *
     * Helper Methods
     *
     *
     */

	/**
	 * Returns WURFL_WURFLManager instance.
	 *
	 * @return WURFL_WURFLManager
	 */
	protected function _getWURFLManager()
	{

		if (self::$_wurflManagerInstance === NULL) {

			/**
			 * WURFL_DIR
			 */
			if (!defined('WURFL_DIR')) {
				define('WURFL_DIR', $this->_options['library']['path']);
			}

			/**
			 * RESOURCES_DIR
			 */
			if (!defined('RESOURCES_DIR')) {
				define('RESOURCES_DIR', $this->_options['resource']['path']);
			}

			/**
			 * wurfl config
			 */
			$wurflConfig = new WURFL_Configuration_XmlConfig($this->_options['config']['path']);

			/**
			 * wurfl manager factory
			 */
			$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);

			/**
			 * wurfl manager
			 */
			self::$_wurflManagerInstance = $wurflManagerFactory->create();

		}

		return self::$_wurflManagerInstance;

	}

}