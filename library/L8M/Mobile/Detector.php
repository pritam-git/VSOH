<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mobile/Detector/Mobi.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Detector.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mobile_Detector
 *
 *
 */
class L8M_Mobile_Detector
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of registered mobile detectors.
	 *
	 * @var array
	 */
	protected static $_registeredDetectors = array(
		'easy'=>'L8M_Mobile_Detector_Easy',
		'mobi'=>'L8M_Mobile_Detector_Mobi',
		'wurfl'=>'L8M_Mobile_Detector_WURFL',
	);

	/**
	 * static options
	 *
	 * @var array
	 */
	protected static $_options = NULL;

	/**
	 * static type
	 *
	 * @var string
	 */
	protected static $_type = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Attempts to create L8M_Mobile_Detector_Abstract instance of the specified
	 * type and with the specified options.
	 *
	 * @param  string            $type
	 * @param  array|Zend_Config $options
	 * @return L8M_Mobile_Detector_Abstract
	 */
	public static function factory($type = NULL, $options = NULL)
	{

		/**
		 * type
		 */
		if (!$type ||
			!is_string($type)) {
			throw new L8M_Mobile_Detector_Exception('Type needs to be specified as a string.');
		}

		/**
		 * options
		 */
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Mobile_Detector_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}

		/**
		 * detectorClass
		 */
		$type = strtolower($type);
		if (!array_key_exists($type, self::$_registeredDetectors)) {
			throw new L8M_Mobile_Detector_Exception('Type needs to be one of "' . implode('", "', array_flip(self::$_registeredDetectors)) . '".');
		}
		$detectorClass = self::$_registeredDetectors[$type];

		/**
		 * load class
		 */
		if (!class_exists($detectorClass)) {
			try {
				@Zend_Loader::loadClass($detectorClass);
			} catch (Zend_Exception $exception) {

			}
		}

		/**
		 * check whether the import class actually extends L8M_Mobile_Detector_Abstract
		 */
		$reflectionClass = new ReflectionClass($detectorClass);
		if (!$reflectionClass->isSubclassOf('L8M_Mobile_Detector_Abstract')) {
			throw new L8M_Mobile_Detector_Exception($detectorClass . ' does not extend L8M_Mobile_Detector_Abstract.');
		}

		/**
		 * detector
		 */
		$detector = new $detectorClass($options);

		return $detector;

	}

	public static function getDeviceName()
	{
		$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Mobile_Detector');
		if (isset($session->deviceName)) {
			return $session->deviceName;
		}

		return NULL;
	}

	public static function getDeviceShort()
	{
		$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Mobile_Detector');
		if (isset($session->deviceShort)) {
			return $session->deviceShort;
		}

		return NULL;
	}

	public static function isMobileDevice()
	{
		$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Mobile_Detector');
		if (isset($session->isMobileDevice)) {
			return $session->isMobileDevice;
		}

		return NULL;
	}

	public static function isTabletDevice()
	{
		$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Mobile_Detector');
		if (isset($session->isTabletDevice)) {
			return $session->isTabletDevice;
		}

		return NULL;
	}

}
