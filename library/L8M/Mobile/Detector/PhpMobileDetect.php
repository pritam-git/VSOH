<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mobile/Detector/Phpmobiledetect.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PhpMobileDetect.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mobile_Detector_Phpmobiledetect
 *
 *
 */
class L8M_Mobile_Detector_Easy extends L8M_Mobile_Detector_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * stores whether mobile device is a tablet or not
	 *
	 * @var $_isTablet boolean
	 */
	private $_isTablet = FALSE;

	/**
	 * stores detector class
	 *
	 * @var L8M_Mobile_Detector_Phpmobiledetect_Detector
	 */
	private $_detector = NULL;

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_Mobile_Detector_Phpmobiledetect instance.
	 *
	 * @return void
	 */
	public function init()
	{
		if (!$this->_detector) {
			$this->_detector = new L8M_Mobile_Detector_Phpmobiledetect_Detector();
		}
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE if it is a mobile device.
	 * Code of: http://detectmobilebrowser.com/
	 *
	 * @return bool
	 */
	public function isMobileDevice()
	{
		$returnValue = FALSE;

		if ($this->_detector->isMobile() ||
			$this->_detector->isTablet()) {

			$returnValue = TRUE;

			/**
			 * do we have to ignore tablets?
			 */
			if (L8M_Config::getOption('mobile.detector.phpmobiledetect.ignoreTablet')) {
				if ($this->_detector->isTablet() !== FALSE) {
					$returnValue = FALSE;
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Returns TRUE if it is a tablet device.
	 * Code of: http://detectmobilebrowser.com/
	 *
	 * @return bool
	 */
	public function isTabletDevice()
	{
		$returnValue = $this->_detector->isTablet();

		return $returnValue;
	}

	/**
	 * Returns device name.
	 *
	 * @return string
	 */
	public function getDeviceName($realName = TRUE)
	{
		$deviceName = NULL;
		if ($this->isMobileDevice()) {
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'iPhone') !== FALSE) {
				$deviceName = 'iPhone';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'iPad') !== FALSE) {
				$deviceName = 'iPad';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'BlackBerry') !== FALSE) {
				$deviceName = 'BlackBerry';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'HTC') !== FALSE) {
				$deviceName = 'HTC';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'LG') !== FALSE) {
				$deviceName = 'LG';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'MOT') !== FALSE) {
				$deviceName = 'MOT';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'Nokia') !== FALSE) {
				$deviceName = 'Nokia';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'Palm') !== FALSE) {
				$deviceName = 'Palm';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'SAMSUNG') !== FALSE) {
				$deviceName = 'SAMSUNG';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'SonyEricson') !== FALSE) {
				$deviceName = 'SonyEricson';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'PDA') !== FALSE) {
				$deviceName = 'PDA';
			} else {
				$deviceName = 'unknown';
			}
		}

		if (!$realName) {
			$deviceName = strtolower($deviceName);
		}
		return $deviceName;
	}
}
