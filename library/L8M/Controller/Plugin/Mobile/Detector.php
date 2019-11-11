<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Mobile/Detector.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Detector.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Mobile_Detector
 *
 *
 */
class L8M_Controller_Plugin_Mobile_Detector extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the detector type.
	 *
	 * @var string
	 */
	protected $_type = NULL;

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected $_options = NULL;

	/**
	 * An L8M_Mobile_Detector_Abstract instance.
	 *
	 * @var L8M_Mobile_Detector_Abstract
	 */
	protected static $_detectorInstance = NULL;

	/**
	 * A string representing the session namespace.
	 *
	 * @var string
	 */
	protected static $_sessionNamespace = 'L8M_Controller_Plugin_Mobile_Detector';

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Controller_Plugin_Mobile_Detector instance.
	 *
	 * @param  string			$type
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($type = NULL, $options = NULL)
	{

		/**
		 * type
		 */
		if (!$type ||
			!is_string($type)) {

			throw new L8M_Controller_Plugin_Mobile_Detector_Exception('Type needs to be specified as string.');
		}

		/**
		 * options
		 */
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Controller_Plugin_Mobile_Detector_Exception('Options need to be specified as array or Zend_Config instance.');
		}

		$this->_type = $type;
		$this->_options = $options;

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Called on route startup
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on route shutdown
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on dispatch loop startup
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on pre dispatch
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{

		/**
		 * session
		 */
		if (Zend_Session::namespaceIsset(self::$_sessionNamespace)) {

			$session = new Zend_Session_Namespace(self::$_sessionNamespace);
		} else {

			/**
			 * detectorInstance
			 */
			$detector = $this->_getDetectorInstance();

			$isTabletDevice = FALSE;
			if ($detector instanceof L8M_Mobile_Detector_Abstract) {
				$isMobileDevice = $detector->isMobileDevice();
				$deviceName = $detector->getDeviceName();
				$deviceShort = $detector->getDeviceName(FALSE);

				if (in_array('isTabletDevice', get_class_methods($detector))) {
					$isTabletDevice = $detector->isTabletDevice();
				}
			} else {
				$isMobileDevice = FALSE;
				$deviceName = 'n/a';
				$deviceShort = 'n/a';
			}

			/**
			 * store in session
			 */
			$session = new Zend_Session_Namespace(self::$_sessionNamespace);
			$session->isMobileDevice = $isMobileDevice;
			$session->isTabletDevice = $isTabletDevice;
			$session->deviceName = $deviceName;
			$session->deviceShort = $deviceShort;

			/**
			 * modify request object
			 *
			 * @todo allow for options
			 */
//			if ($isMobileDevice) {
//				$request
//					->setModuleName('default')
//					->setControllerName('mobile')
//					->setActionName('index')
//					->setParam('device', $deviceName)
//				;
//			}
		}

		/**
		 * in developer-mode we could overwirte by variable 'mobile-view'
		 */
		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT == L8M_Environment::getInstance()->getEnvironment()) {
			$mobileView = $request->getParam('dev-mobile-view');
			$mobileName = $request->getParam('dev-mobile-name');
			$mobileShort = $request->getParam('dev-mobile-short');
			if ($mobileView != NULL) {
				if ($mobileView == 'true') {
					$session->isMobileDevice = TRUE;
					$session->isTabletDevice = FALSE;

					if ($mobileShort == 'ipad') {
						$session->isTabletDevice = TRUE;
					}
					if ($mobileShort == 'ipad' &&
						L8M_Config::getOption('mobile.detector.easy.ignoreTablet')) {

						$session->isMobileDevice = FALSE;
					} else {
						if ($mobileShort == NULL) {
							$mobileName = 'n/a';
						}

						if ($mobileShort == NULL) {
							$mobileShort = 'n/a';
						}
					}
					$session->deviceName = $mobileName;
					$session->deviceShort = $mobileShort;
				} else
				if ($mobileView == 'false') {
					$session->isMobileDevice = FALSE;
					$session->deviceName = 'n/a';
					$session->deviceShort = 'n/a';
				}
			}
		}

		/**
		 * switch layout if mobile content has been requested
		 */
		if ($session->isMobileDevice) {
			$deviceShort = $session->deviceShort;

			if ($deviceShort == 'n/a') {
				$deviceShort = NULL;
			} else {
				$deviceShort = '-' . $deviceShort;
			}
			$layoutPath = Zend_Layout::getMvcInstance()->getLayoutPath() . DIRECTORY_SEPARATOR;
			if (!L8M_Config::getOption('mobile.detector.easy.ignoreTablet') &&
				$session->isTabletDevice) {

				if (file_exists($layoutPath . 'tablet' . $deviceShort . '.phtml')) {
					Zend_Layout::getMvcInstance()->setLayout('tablet' . $deviceShort);
				} else {
					Zend_Layout::getMvcInstance()->setLayout('tablet');
				}
			} else {
				if (file_exists($layoutPath . 'mobile' . $deviceShort . '.phtml')) {
					Zend_Layout::getMvcInstance()->setLayout('mobile' . $deviceShort);
				} else {
					Zend_Layout::getMvcInstance()->setLayout('mobile');
				}
			}
		}
	}

	/**
	 * Called on post dispatch
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on dispatch loop shutdown
	 *
	 */
	public function dispatchLoopShutdown()
	{

	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Mobile_Detector_Abstract instance.
	 *
	 * @param  string			$type
	 * @param  array|Zend_Config $options
	 * @return L8M_Mobile_Detector_Abstract
	 */
	protected function _getDetectorInstance()
	{
		if (self::$_detectorInstance === NULL) {

			try {

				$detector = L8M_Mobile_Detector::factory(
					$this->_type,
					$this->_options
				);

			} catch (L8M_Mobile_Detector_Exception $exception) {
				throw new L8M_Controller_Plugin_Mobile_Detector_Exception($exception->getMessage());
			}

			self::$_detectorInstance = $detector;

		}

		return self::$_detectorInstance;

	}

}