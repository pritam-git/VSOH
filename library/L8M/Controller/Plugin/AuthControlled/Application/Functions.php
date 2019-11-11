<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/AuthControlled/Application/Functions.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Functions.php 503 2016-07-21 10:17:42Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_AuthControlled_Application_Functions
 *
 *
 */
class L8M_Controller_Plugin_AuthControlled_Application_Functions
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Called after an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * We need to make sure that this plugin is registered last before the
	 * Zend_Controller_Plugin_Layout, so that this method gets called before
	 * Zend_Controller_Plugin_Layout::postDispatch().
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @param  Zend_Controller_Response_Abstract $response
	 * @param  String $content
	 * @return void
	 */
	public  static function postDispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, $content)
	{
		$phpVersion = NULL;
		$phpLoader = NULL;

		if (version_compare(PHP_VERSION, '5.2.0') >= 0 &&
			version_compare(PHP_VERSION, '7.1.0') < 0) {
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
				if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
					if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
						if (version_compare(PHP_VERSION, '5.6.0') >= 0) {
							if (version_compare(PHP_VERSION, '5.7.0') >= 0) {
								$phpVersion = '57';
							} else {
								$phpVersion = '56';
							}
						} else {
							$phpVersion = '55';
						}
						if (extension_loaded('Zend Guard Loader')) {
							$phpLoader = 'Zg';
						}
					} else {
						$phpVersion = '54';
					}
				} else {
					$phpVersion = '53';
				}
				if (!$phpLoader) {
					if (extension_loaded('Zend Guard Loader')) {
						$phpLoader = '';
					} else
					if (extension_loaded('IonCube Loader')) {
						$phpLoader = 'Ic';
					}
				}
			} else {
				$phpVersion = '52';
				if (extension_loaded('Zend Optimizer')) {
					$phpLoader = '';
				} else
				if (extension_loaded('IonCube Loader')) {
					$phpLoader = 'Ic';
				}
			}
		}

		/**
		 * fall back for a short time
		 */
		if (($phpVersion == '55' || $phpVersion == '56') &&
			$phpLoader == 'Zg' &&
			extension_loaded('IonCube Loader')) {

			$phpLoader = 'Ic';
		}

		/**
		 * go on
		 */
		$loadClass = 'L8M_Controller_Plugin_AuthControlled_Application_Functions_Php' . $phpLoader . $phpVersion;
		if (class_exists($loadClass)) {
			$loadClass::postDispatch($request, $response, $content);
		} else {
// 			throw new L8M_Exception('Could not find right PHP version and / or loader.');
		}
	}
}