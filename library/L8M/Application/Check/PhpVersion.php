<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Check/PhpVersion.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PhpVersion.php 414 2015-09-15 16:45:16Z nm $
 */

/**
 *
 *
 * L8M_Application_Check_PhpVersion
 *
 *
 */
class L8M_Application_Check_PhpVersion
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of errors.
	 *
	 * @var array
	 */
	protected $_errors = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Constructs L8M_Application_Check_PhpVersion instance.
	 *
	 * @return L8M_Application_Check_PhpVersion
	 */
	protected function __construct()
	{

		/**
		 * php - standards
		 */
		if (version_compare(PHP_VERSION, '5.2.0', '<' )) {
			$this->_errors[] = 'Version 5.2 or higher needed';
		}
		if (version_compare(PHP_VERSION, '5.7.0', '>=' )) {
			$this->_errors[] = 'That system can only be used with Version: 5.2, 5.3, 5.4, 5.5 and 5.6';
		}

		if (version_compare(PHP_VERSION, '5.2.0') >= 0) {
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {

				if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
// 					if (extension_loaded('IonCube Loader')) {
// 						if (ioncube_loader_version() < '5.0') {
// 							$this->_errors[] = 'IonCube Loader: Version 5.0 or higher needed';
// 						}
// 					} else {
// 						$this->_errors[] = 'IonCube Loader needs to be installed';
// 					}
				} else {
					/**
					 * check for ZendLoader
					 */
					if (extension_loaded('Zend Guard Loader')) {
						if (!zend_loader_enabled()) {
							$this->_errors[] = 'Zend Guard Loader need to be enabled';
						}

						if (zend_loader_version() < '3.3') {
							$this->_errors[] = 'Zend Guard Loader: Version 3.3 or higher needed';
						}
					} else {
						if (extension_loaded('IonCube Loader')) {
							if (ioncube_loader_version() < '5.0') {
								$this->_errors[] = 'IonCube Loader: Version 5.0 or higher needed';
							}
						} else {
							$this->_errors[] = 'Zend Guard Loader or IonCube Loader needs to be installed';
						}
					}
				}
			} else {

				/**
				 * check for optimzier
				 */
				if (extension_loaded('Zend Optimizer')) {
					if (function_exists('zend_optimizer_version')) {
						if (zend_optimizer_version() < '3.3.3') {
							$this->_errors[] = 'Zend Optimizer: Version 3.3.3 or higher needed';
						}
					} else {
						$this->_errors[] = 'Zend Optimizer Version can not be determined';
					}
				} else {
					if (extension_loaded('IonCube Loader')) {
						if (ioncube_loader_version() < '5.0') {
							$this->_errors[] = 'IonCube Loader: Version 5.0 or higher needed';
						}
					} else {
						$this->_errors[] = 'Zend Optimizer or IonCube Loader needs to be installed';
					}
				}
			}
		}

		if (!ini_get('allow_url_fopen') &&
			!is_readable(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'Authentication.l8m')) {

			$this->_errors[] = 'PHP.ini needs "allow_url_fopen = On" or L8M-Authentication installed.';
		}
	}

	/**
	 * Returns a L8M_Application_Check_PhpVersion Instance.
	 *
	 * @return L8M_Application_Check_PhpVersion
	 */
	public static function factory()
	{
		return new L8M_Application_Check_PhpVersion();
	}

	/**
	 * Returns an array of all errors.
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * Returns TRUE on error or FALSE if there are no problems
	 *
	 * @return boolean
	 */
	public function hasErrors()
	{
		$returnValue = FALSE;
		if (count($this->_errors) > 0) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Returns an string of all errors as html list.
	 *
	 * @return String
	 */
	public function getErrorsHtml()
	{
		$returnValue = NULL;

		foreach ($this->_errors as $error) {
			$returnValue .= '<li class="exclamation">' . $error . '</li>';
		}

		if ($returnValue) {
			$returnValue = '<ul class="iconized">' . $returnValue . '</ul>';
		}
		return $returnValue;
	}
}