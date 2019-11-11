<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Action.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Action.php 560 2018-01-31 17:22:24Z nm $
 */

/**
 *
 *
 * L8M_Controller_Action
 *
 *
 */
class L8M_Controller_Action extends Zend_Controller_Action
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the language used by this application.
	 *
	 * @var string
	 */
	protected $_languageIso2 = NULL;

	/**
	 * An array with options that are set or retrieved using
	 * $this->_getOptions().
	 *
	 * @var array
	 */
	protected $_options = NULL;

	/**
	 * A string representing a key in the applications configuration that is
	 * used for retrieving options from it.
	 *
	 * @var string
	 */
	protected $_optionsKey = NULL;

	/**
	 * A Zend_Cache_Frontend_Page instance.
	 *
	 * @var Zend_Cache_Frontend_Page
	 */
	protected static $_cache = NULL;

	/**
	 * A string representing the name of the cache template in the
	 * Zend_Cache_Manager instance.
	 *
	 * @var string
	 */
	protected static $_cacheTemplate = 'Zend_Cache_Frontend_Page';

	/**
	 * An array of module options.
	 */
	protected static $_moduleOptions = array();

	/**
	 * was exception layout directory changed already?
	 */
	protected static $_exceptionLayoutChanged = FALSE;
	protected static $_exceptionLayoutChangedDir = NULL;


	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Controller_Action instance.
	 *
	 * add layoutPath change possibility into L8M_Controller_Action
	 * looking for standard screen inside module and change if file exists into new module layout path
	 *
	 * @return void
	 */
	public function init()
	{
		/**
		 * check for redirect
		 */
		if (Zend_Registry::isRegistered('L8M_Application_Resource_Router_Redirect')) {
			$this->_redirect(Zend_Registry::get('L8M_Application_Resource_Router_Redirect'));
		}

		/**
		 * retrieve layout name
		 */
		$layout = $this->_helper->layout->getLayout();

		/**
		 * retrieve module name
		 */
		$module = $this->getRequest()->getModuleName();

		/**
		 * set module dir
		 */
		$moduleDir = $module;

		/**
		 * retrieve controller name
		 */
		$controller = $this->getRequest()->getControllerName();

		/**
		 * check whether we have an exception or not
		 */
		if ($this->_helper->layout()->isException &&
			!self::$_exceptionLayoutChanged) {

			self::$_exceptionLayoutChanged = TRUE;
			$rememberModule = $this->_helper->layout()->rememberCalledForModuleName;
			if ($rememberModule &&
				($rememberModule == 'admin' ||
				$rememberModule == 'system' ||
				$rememberModule == 'system-model-list')) {

				if (Zend_Auth::getInstance()->hasIdentity()) {
					$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;

					if ($roleShort == 'supervisor' ||
						$roleShort == 'admin') {

						$moduleDir = $rememberModule;
						self::$_exceptionLayoutChangedDir = $moduleDir;
					}
				} else
				if (L8M_Environment::getInstance()->getEnvironment() == L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
					$moduleDir = $rememberModule;
					self::$_exceptionLayoutChangedDir = $moduleDir;
				}
			}
		} else
		if (self::$_exceptionLayoutChanged &&
			self::$_exceptionLayoutChangedDir) {

			$moduleDir = self::$_exceptionLayoutChangedDir;
		}

		/**
		 * change layout path
		 */
		if ($moduleDir !== 'default') {

			/**
			 * change to module path
			 */
			$newLayoutDirectory = APPLICATION_PATH
				. DIRECTORY_SEPARATOR
				. 'modules'
				. DIRECTORY_SEPARATOR
				. $moduleDir
				. DIRECTORY_SEPARATOR
				. 'layouts'
				. DIRECTORY_SEPARATOR
				. 'scripts'
			;
		} else {

			/**
			 * set to default
			 */
			$newLayoutDirectory = APPLICATION_PATH
				. DIRECTORY_SEPARATOR
				. 'layouts'
				. DIRECTORY_SEPARATOR
				. 'scripts'
			;
		}

		/**
		 * layout file
		 */
		$layoutFile = $layout . '.phtml';
		if (stripos($layout, 'mobile-') === 0) {
			$possibleLayoutFile = 'mobile.phtml';
			$possibleLayout = 'mobile';
		} else
		if (stripos($layout, 'tablet-') === 0) {
			$possibleLayoutFile = 'tablet.phtml';
			$possibleLayout = 'tablet';
		} else {
			$possibleLayoutFile = NULL;
			$possibleLayout = NULL;
		}

		/**
		 * change if exists
		 */
		if (file_exists($newLayoutDirectory . DIRECTORY_SEPARATOR . $layoutFile)) {
			$this->_helper->layout->setLayoutPath($newLayoutDirectory);
		} else
		if ($possibleLayoutFile !== NULL &&
			file_exists($newLayoutDirectory . DIRECTORY_SEPARATOR . $possibleLayoutFile)) {

			$this->_helper->layout->setLayout($possibleLayout);
			$this->_helper->layout->setLayoutPath($newLayoutDirectory);
		}

		/**
		 * check for action param
		 */
		/**
		 * retrieve ACL
		 */
		$session = new Zend_Session_Namespace('L8M_Controller_Plugin_AuthControlled');
		$isNotParamAction = TRUE;
		if (isset($session->acl)) {
			$acl = $session->acl;

			/**
			 * checking for action param
			 */
			if ($acl instanceof L8M_Controller_Plugin_AuthControlled_Acl &&
				$acl->checkActionParam($module, $controller)) {

				$isNotParamAction = FALSE;

				/**
				 * retrieve possible action rewritten to param
				 */
				$possibleMeantAction = $this->_request->getParam($acl->getActionParamNewParam($module, $controller));

				/**
				 * check whether possible action exists in controller
				 */
				if (method_exists($this, $possibleMeantAction . 'Action')) {

					/**
					 * rewrite action param back to action
					 */
					$this->_request->setActionName($possibleMeantAction);
					$isNotParamAction = TRUE;
				}
			}
		}

		/**
		 * check for special param only vars to rewrite to param
		 */
		if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Var.php') &&
			class_exists('PRJ_Controller_Action_Var')) {

			/**
			 * retrieve controller name
			 */
			$action = $this->getRequest()->getActionName();

			$paramVar = new PRJ_Controller_Action_Var();

			if ($paramVar instanceof L8M_Controller_Action_Var &&
				$paramVar->checkController($action, $controller, $module, L8M_Locale::getLang())) {

				$keyName = $paramVar->getParam($action, $controller, $module, L8M_Locale::getLang());
				$paramTranslatorSqlCollection = L8M_Translate_Param::getUparamByParamWithLang($keyName, L8M_Locale::getLang());
				if ($paramTranslatorSqlCollection->count() == 1) {
					$keyName = $paramTranslatorSqlCollection->getFirst()->uparam;
				}
				$this->_request->setParam($keyName, $paramVar->getValue($action, $controller, $module, L8M_Locale::getLang(), $isNotParamAction));
				$paramsBehind = $paramVar->getParamsBehind($action, $controller, $module, L8M_Locale::getLang(), $isNotParamAction);
				foreach ($paramsBehind as $key => $value) {
					$this->_request->setParam($key, $value);
				}
			}
		}

		/**
		 * initiate caching
		 */
		if (Zend_Registry::get('environment') === L8M_Environment::ENVIRONMENT_DEVELOPMENT ||
			Zend_Auth::getInstance()->hasIdentity()) {

			$this->getResponse()->setHeader('Expires', 0, TRUE);
			$this->getResponse()->setHeader('Cache-Control', 'no-cache', TRUE);
		} else {
			$this->getResponse()->setHeader('Expires', date('D, d M Y H:i:s', time() + L8M_Config::getOption('l8m.cache.default_lifetime') - 7200) . ' GMT', TRUE);
			$this->getResponse()->setHeader('Cache-Control', 'max-age=' . L8M_Config::getOption('l8m.cache.default_lifetime') . ',public', TRUE);
		}
	}

	/**
	 * Post-dispatch routines
	 *
	 * Called after action method execution. If using class with
	 * {@link Zend_Controller_Front}, it may modify the
	 * {@link $_request Request object} and reset its dispatched flag in order
	 * to process an additional action.
	 *
	 * Common usages for postDispatch() include rendering content in a sitewide
	 * template, link url correction, setting headers, etc.
	 *
	 * @return void
	 */
	public function postDispatch()
	{
		/**
		 * prevent errors run parent's postDispatch
		 */
		parent::postDispatch();

		/**
		 * retrieve module name
		 */
		$module = $this->getRequest()->getModuleName();

		/**
		 * change module path
		 */
		$moduleDirectory = NULL;
		if ($module !== 'default') {

			/**
			 * change to module path
			 */
			$moduleDirectory = 'modules'
				. DIRECTORY_SEPARATOR
				. $module
				. DIRECTORY_SEPARATOR
			;
		}
		/**
		 * render mobileview
		 */
		$session = new Zend_Session_Namespace('L8M_Controller_Plugin_Mobile_Detector');
		if (isset($session->isMobileDevice) &&
			$session->isMobileDevice == TRUE) {

			$mobileScriptFile = APPLICATION_PATH . DIRECTORY_SEPARATOR .
				$moduleDirectory .
				'views' . DIRECTORY_SEPARATOR .
				'scripts' .DIRECTORY_SEPARATOR .
				$this->getRequest()->getControllerName() . DIRECTORY_SEPARATOR .
				$this->getRequest()->getActionName() . '-mobile.phtml'
			;

			$tabletScriptFile = APPLICATION_PATH . DIRECTORY_SEPARATOR .
				$moduleDirectory .
				'views' . DIRECTORY_SEPARATOR .
				'scripts' .DIRECTORY_SEPARATOR .
				$this->getRequest()->getControllerName() . DIRECTORY_SEPARATOR .
				$this->getRequest()->getActionName() . '-tablet.phtml'
			;

			if (file_exists($mobileScriptFile)) {
				$this->_helper->viewRenderer($this->getRequest()->getActionName() . '-mobile');
			}

			$mobileViewEnabled = TRUE;
			$tabletViewEnabled = FALSE;

			if (!L8M_Config::getOption('mobile.detector.easy.ignoreTablet') &&
				$session->isTabletDevice &&
				file_exists($tabletScriptFile)) {

				$this->_helper->viewRenderer($this->getRequest()->getActionName() . '-tablet');
				$tabletViewEnabled = TRUE;
			}
		} else {
			$mobileViewEnabled = FALSE;
			$tabletViewEnabled = FALSE;
		}

		/**
		 * render ajax views
		 */
		if ($this->getRequest()->isXmlHttpRequest()) {

			/**
			 * ajax script files
			 */
			$ajaxScriptFileMobile = APPLICATION_PATH . DIRECTORY_SEPARATOR .
				$moduleDirectory .
				'views' . DIRECTORY_SEPARATOR .
				'scripts' .DIRECTORY_SEPARATOR .
				$this->getRequest()->getControllerName() . DIRECTORY_SEPARATOR .
				$this->getRequest()->getActionName() . '-mobile-ajax.phtml'
			;

			$ajaxScriptFileTablet = APPLICATION_PATH . DIRECTORY_SEPARATOR .
				$moduleDirectory .
				'views' . DIRECTORY_SEPARATOR .
				'scripts' .DIRECTORY_SEPARATOR .
				$this->getRequest()->getControllerName() . DIRECTORY_SEPARATOR .
				$this->getRequest()->getActionName() . '-tablet-ajax.phtml'
			;

			$ajaxScriptFileWeb = APPLICATION_PATH . DIRECTORY_SEPARATOR .
				$moduleDirectory .
				'views' . DIRECTORY_SEPARATOR .
				'scripts' .DIRECTORY_SEPARATOR .
				$this->getRequest()->getControllerName() . DIRECTORY_SEPARATOR .
				$this->getRequest()->getActionName() . '-ajax.phtml'
			;

			/**
			 * check
			 */
			if ($mobileViewEnabled &&
				file_exists($ajaxScriptFileMobile)) {

				$this->_helper->viewRenderer($this->getRequest()->getActionName() . '-mobile-ajax');

				if ($tabletViewEnabled &&
					file_exists($ajaxScriptFileTablet)) {

					$this->_helper->viewRenderer($this->getRequest()->getActionName() . '-tablet-ajax');
				}

				Zend_Layout::getMvcInstance()->disableLayout();
			} else
			if (file_exists($ajaxScriptFileWeb)) {

				$this->_helper->viewRenderer($this->getRequest()->getActionName() . '-ajax');
				Zend_Layout::getMvcInstance()->disableLayout();
			}
		}
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns language used by this application.
	 *
	 * @return void
	 */
	protected function _getLanguage()
	{
		if ($this->_languageIso2 === NULL) {
			if (Zend_Registry::isRegistered('Zend_Locale')) {
				$this->_languageIso2 = Zend_Registry::get('Zend_Locale')->getLanguage();
			} else {
				$this->_languageIso2 = 'en';
			}
		}
		return $this->_languageIso2;
	}

	/**
	 * Returns array with options for the specified key, retrieved from the
	 * application configuration.
	 *
	 * @return array
	 */
	public function getOption($key = NULL)
	{
		if (!is_string($key)) {
			throw new L8M_Controller_Action_Exception('Key needs to be specified as a string.');
		}

		$keys = explode('.', $key);

		if (is_array($keys) &&
			count($keys) >= 1) {

			$key = $keys[0];
		}

		$option = NULL;

		$defaultOption = $this->getInvokeArg('bootstrap')->getOption($key);
		if (is_array($defaultOption)) {
			$option = $defaultOption;
		}

		$moduleOption = $this->getModuleOption($key);
		if (is_array($moduleOption)) {
			if (!is_array($defaultOption)) {
				$option = $moduleOption;
			} else {
				$option = array_merge(
					$defaultOption,
					$moduleOption
				);
			}
		}

		if (is_array($keys) &&
			count($keys) >= 2) {

			for ($i = 1; $i < count($keys); $i++) {
				if (is_array($option) &&
					isset($option[$keys[$i]])) {

					$option = $option[$keys[$i]];
				} else {
					$option = NULL;
				}
			}
		}

		/**
		 * return option
		 */
		return $option;
	}

	/**
	 * Returns array with options for the specified key, retrieved from the
	 * module configuration.
	 *
	 * @return array
	 */
	public function getModuleOption($key = NULL)
	{
		if (!is_string($key)) {
			throw new L8M_Controller_Action_Exception('Key needs to be specified as a string.');
		}

		/**
		 * module name
		 */
		$moduleName = $this->getRequest()->getModuleName();

		if (!isset(self::$_moduleOptions[$moduleName])) {

			/**
			 * path to module configuration
			 */
			$moduleConfigPath = $this->getFrontController()->getModuleDirectory($moduleName)
							  . DIRECTORY_SEPARATOR
							  . 'configs'
							  . DIRECTORY_SEPARATOR
							  . 'module.ini'
			;

			try {

				/**
				 * module configuration
				 */
				$moduleConfig = new Zend_Config_Ini($moduleConfigPath);
				$environment = $this->getInvokeArg('bootstrap')->getEnvironment();
				$moduleConfig = $moduleConfig->get($environment);
				$moduleOptions = $moduleConfig->toArray();

			} catch (Exception $exception) {
				$moduleOptions = array();
			}

			self::$_moduleOptions[$moduleName] = $moduleOptions;

		}

		if (isset(self::$_moduleOptions[$moduleName][$key])) {
			return self::$_moduleOptions[$moduleName][$key];
		}

		return NULL;

	}

	/**
	 * Returns database adapter with the specified name.
	 *
	 * @param  string $adapterName
	 * @return Zend_Db_Adapter_Abstract
	 */
	public function getDatabaseAdapter($adapterName = NULL)
	{
		if (!$adapterName ||
			!is_string($adapterName)) {
			throw new L8M_Controller_Action_Exception('Adapter name needs to be specified as a string.');
		}
		try {
			$adapter = $this->getInvokeArg('bootstrap')->getPluginResource('multidb')->getDb($adapterName);
		} catch (Zend_Application_Resource_Exception $exception) {
			throw new L8M_Controller_Action_Exception('Specified adapter has not been initialized.');
		}

		return $adapter;
	}

	/**
	 * Returns the previously cached page.
	 *
	 * @param  string $id
	 * @return void
	 */
	public function getCached($id = FALSE)
	{
		$cache = $this->_getCache();

		if ($cache) {
			$cache->start($id);
		}
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns Zend_Cache_Frontend_Page instance.
	 *
	 * @return Zend_Cache_Frontend_Page
	 */
	protected function _getCache()
	{
		if (self::$_cache === NULL) {
			if (Zend_Registry::isRegistered('Zend_Cache_Manager')) {
				$cacheManager = Zend_Registry::get('Zend_Cache_Manager');
				if ($cacheManager instanceof Zend_Cache_Manager &&
					$cacheManager->hasCacheTemplate(self::$_cacheTemplate)) {
					$cache = $cacheManager->getCache(self::$_cacheTemplate);
					if (!($cache instanceof Zend_Cache_Frontend_Page)) {
						throw new L8M_Controller_Action_Exception('Cannot use a cache that does not extend Zend_Cache_Frontend_Page.');
					}
					self::$_cache = $cache;
				}
			}
		}
		return self::$_cache;
	}

}