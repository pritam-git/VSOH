<?php

/**
 * L8M
 *
 *
 * @filesource /application/Bootstrap.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Bootstrap.php 518 2016-10-24 14:46:54Z nm $
 */

/**
 *
 *
 * Bootstrap
 *
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A Zend_Config instance used by this application
	 *
	 * @var Zend_Config
	 */
	protected $_config = NULL;

	/**
	 * A Zend_Locale instance used by this application
	 *
	 * @var Zend_Locale
	 */
	protected $_locale = NULL;

	/**
	 * A Zend_Log instance used by this application
	 *
	 * @var Zend_Log
	 */
	protected $_log = NULL;

	/**
	 * An array of modules registered by this applicaiton.
	 *
	 * @var array
	 */
	protected $_modules = NULL;

	/**
	 * The Zend_Translate instance used by this application
	 *
	 * @var Zend_Translate
	 */
	protected $_translate = NULL;

	/**
	 * The Zend_View instance used by this application
	 *
	 * @var Zend_View
	 */
	protected $_view = NULL;

	/**
	 * The Zend_Controller_Request_Http instance used by this application
	 * @var Zend_Controller_Request_Http
	 */
	protected $_request = NULL;

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */

	/**
	 * Retrieves Zend_Cache_Manager instance and stores it in Zend_Registry.
	 *
	 * @return void
	 */
	protected function _initApplicationCacheManager()
	{
		$this
			->bootstrap('config')
			->bootstrap('cachemanager')
		;

		$l8mOptions = $this->getOption('l8m');
		if (isset($l8mOptions['cache']) &&
			isset($l8mOptions['cache']['enabled']) &&
			$l8mOptions['cache']['enabled']) {

			Zend_Registry::set('Zend_Cache_Manager', $this->getResource('CacheManager'));
		}
	}

	/**
	 * Initializes multiple Zend_Db instances, assigns priorly instantiated Zend_Log
	 * instance as database profiler and also sets priorly instantiated
	 * Zend_Cache instance as default meta data cache.
	 *
	 * @return Zend_Db_Adapter_Abstract
	 */
	protected function _initApplicationDatabases ()
	{
		$this
			->bootstrap('config')
			->bootstrap('cachemanager')
			->bootstrap('multidb')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$databaseOptions = $this->getOption('database');

		/**
		 * database meta data caching enabled?
		 */
		if (isset($databaseOptions['cache']['enabled']) &&
			$databaseOptions['cache']['enabled'] == TRUE) {

			/**
			 * cache manager
			 */
			$cacheManager = $this->getResource('CacheManager');
			if ($cacheManager->hasCacheTemplate('Zend_Db_Table')) {

				Zend_Db_Table::setDefaultMetadataCache($cacheManager->getCache('Zend_Db_Table'));
				$this->_log->info('Bootstrap: Zend_Db_Table meta data cache bootstrapped.');

			}

		}

		/**
	 	 * resources options
		 */
		$resourcesOptions = $this->getOption('resources');

		/**
		 * multi db
		 */
		if (isset($resourcesOptions['multidb']) &&
			is_array($resourcesOptions['multidb']) &&
			count($resourcesOptions['multidb']) > 0) {

			/**
			 * retrieve all adapter names
			 */
			$adapterNames = array_keys($resourcesOptions['multidb']);

			/**
			 * iterate over adapters
			 */
			foreach ($adapterNames as $adapterName) {

				/**
				 * adapter
				 */
				$adapter = $this->getResource('MultiDb')->getDb($adapterName);

				/**
				 * store in registry
				 */
				$registryKey = 'database'
							 . ucfirst($adapterName)
				;
				Zend_Registry::set($registryKey, $adapter);

				/**
				 * profiling enabled?
				 */
				if (L8M_Environment::ENVIRONMENT_DEVELOPMENT == $this->getEnvironment() &&
					isset($databaseOptions['profiler']['enabled']) &&
					$databaseOptions['profiler']['enabled'] == TRUE &&
					isset($databaseOptions['profiler']['class'])) {

					/**
					 * profiler
					 */
					$adapterProfiler = new $databaseOptions['profiler']['class'];
					$adapterProfiler->setEnabled(TRUE);

					$adapter->setProfiler($adapterProfiler);

					$this->_log->info('Bootstrap: Database profiler for "' . $adapterName . '" bootstrapped.');

				}

			}

		}

	}

	/**
	 * Initializes Dojo.
	 *
	 * @todo   consider Zend_Locale . . . !!
	 * @return void
	 */
	protected function _initApplicationDojo ()
	{
		return;

		$this
			->bootstrap('locale')
			->bootstrap('translate')
			->bootstrap('dojo')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$options = $this->getOptions();

		/**
		 * dojo enabled?
		 */
		if (isset($options['resources']['dojo'])) {

			/**
			 * locale
			 */
			if ($this->_locale instanceof Zend_Locale) {
				$this->_view->dojo()->setDjConfigOption('locale', $this->_locale->getLanguage());

				/**
				 * extra locale
				 */
				if ($this->_translate instanceof Zend_Translate) {
					$extraLocale = array_diff_key(
						$this->_translate->getAdapter()->getList(),
						array(
							$this->_locale->getLanguage()=>$this->_locale->getLanguage(),
						)
					);

					$extraLocale = implode(',', $extraLocale);
					$this->_view->dojo()->setDjConfigOption('extralocale', $extraLocale);
				}
			}

			/**
			 * programmatic or declarative mode
			 */
//			if (isset($dojoOptions['mode']['declarative']) &&
//				$dojoOptions['mode']['declarative']) {
//				Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
//			} else {
//				Zend_Dojo_View_Helper_Dojo::setUseProgrammatic();
//			}

		}

		/**
		 * @todo remove this
		 */
		Zend_Registry::set('dojoEnabled', TRUE);
	}

	/**
	 * Initializes jQuery.
	 *
	 * @return void
	 */
	protected function _initApplicationJQuery()
	{
		$this
			->bootstrap('jquery')
			->bootstrap('view')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$options = $this->getOptions();

		/**
		 * jquery enabled?
		 */
		if (isset($options['resources']['jquery'])) {
			Zend_Registry::set('jQueryEnabled', TRUE);

			if (isset($options['resources']['jquery']['ui_enable']) &&
				$options['resources']['jquery']['ui_enable'] == TRUE) {

				/**
				 * register jQuery UI enabled
				 */
				Zend_Registry::set('jQueryUI', TRUE);
			}

			if (isset($options['resources']['jquery']['tools']['enable']) &&
				$options['resources']['jquery']['tools']['enable'] == TRUE) {

				/**
				 * register jQuery Tools
				 */
				Zend_Registry::set('jQueryTools', $options['resources']['jquery']['tools']);
			}
		}

	}


	/**
	 * Initializes router.
	 *
	 * @return void
	 */
	protected function _initApplicationRouter()
	{
		$this->bootstrap('frontcontroller');

		/**
		 * route to default module, index controller
		 */
//		$route = new Zend_Controller_Router_Route('/:action', array('module'=>'default',
//																	'controller'=>'index',
//																	'action'=>'index'));
//
//		/**
//		 * router
//		 */
//		$router = $this->getResource('frontController')->getRouter();
//		$router->addRoute('default_index', $route);
	}

	/**
	 * Initializes application session.
	 *
	 * @return void
	 */
	protected function _initApplicationSession ()
	{
		$this
			->bootstrap('doctrine')
			->bootstrap('moduleautoloader')
			->bootstrap('session')
			->bootstrap('frontcontroller')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$resourceOptions = $this->getOption('resources');
		$authenticationOptions = $this->getOption('authentication');

		/**
		 * register plugin
		 */
		$sessionPlugin = new L8M_Controller_Plugin_Session(
			$resourceOptions['session']['name'],
			$authenticationOptions['session']['allowed_session_id_resources']
		);
		$this->getResource('FrontController')->registerPlugin($sessionPlugin);

		$this->_log->info('Bootstrap: Session bootstrapped.');
	}

	/**
	 * Initializes application session.
	 *
	 * @return void
	 */
	protected function _initApplicationAdmin ()
	{
		$this
			->bootstrap('doctrine')
			->bootstrap('moduleautoloader')
			->bootstrap('session')
			->bootstrap('frontcontroller')
			->bootstrap('log')
		;

		/**
		 * register plugin
		 */
		$adminPlugin = new L8M_Controller_Plugin_Admin();

		$this->getResource('FrontController')->registerPlugin($adminPlugin);

		$this->_log->info('Bootstrap: Admin bootstrapped.');
	}

	/**
	 * Initializes Zend_View instance.
	 *
	 * @return Zend_View
	 */
	protected function _initApplicationView ()
	{
		$this
			->bootstrap('config')
			->bootstrap('frontcontroller')
			->bootstrap('view')
			->bootstrap('log')
		;

		/**
		 * start MVC
		 */
		Zend_Layout::startMvc();
		$this->_view = Zend_Layout::getMvcInstance()->getView();

		/**
		 * doctype
		 */
		$this->_view->doctype()->setDoctype(Zend_View_Helper_Doctype::HTML5);

		/**
		 * fonts
		 */
		$module = L8M_Acl_CalledFor::module();
		if (in_array($module, L8M_Config::getOption('locale.backend.modules'))) {
			$module = 'system';
		} else
		if ($module == 'shop') {
			$module = 'default';
		}
		if (file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'all' . DIRECTORY_SEPARATOR . 'imports.css')) {
			$this->_view->headLink()->appendStylesheet('/css/' . $module . '/all/imports.css', 'all');
		}

		/**
		 * reset css
		 */
		$this->_view->headLink()->appendStylesheet('/css/default/all/reset.css', 'all');
		$this->_log->info('Bootstrap: View bootstrapped.');
		return $this->_view;
	}

	/**
	 * Initializes auth controlled plugin. Requires to have the front controller
	 * Doctrine and session to be initialized.
	 *
	 * @return void
	 */
	protected function _initAuthControlled ()
	{
		$this
			->bootstrap('frontcontroller')
			->bootstrap('applicationsession')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$authenticationOptions = $this->getOption('authentication');

		/**
		 * authentication enabled?
		 */
		if (isset($authenticationOptions['enabled']) &&
			$authenticationOptions['enabled'] == TRUE) {
			/**
			 * authentication plugin
			 */
			$authenticationPlugin = new L8M_Controller_Plugin_AuthControlled_ContentInjector(Zend_Auth::getInstance());
			$this->getResource('FrontController')->registerPlugin($authenticationPlugin);
			$this->_log->info('Bootstrap: Authentication Plugin bootstrapped.');
		}
	}

	/**
	 * Initializes Zend_Config instance.
	 *
	 * @return Zend_Config
	 */
	protected function _initConfig ()
	{
		/**
		 * retrieve config
		 */
		$this->_config = new Zend_Config($this->getOptions());
		date_default_timezone_set($this->_config->get('timezone', 'Europe/Berlin'));
		Zend_Registry::set('Zend_Config', $this->_config);
		return $this->_config;
	}

	/**
	 * Initializes Zend_Date by setting Zend_Cache adapter as cache.
	 *
	 * @return void
	 */
	protected function _initDate ()
	{
		$this
			->bootstrap('config')
			->bootstrap('cachemanager')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$dateOptions = $this->getOption('date');

		/**
		 * date cache enabled?
		 */
		if (isset($dateOptions['cache']['enabled']) &&
			$dateOptions['cache']['enabled'] == TRUE) {
			$cacheManager = $this->getResource('CacheManager');
			if ($cacheManager->hasCacheTemplate('Zend_Date')) {
				Zend_Date::setOptions(array(
					'cache'=>$cacheManager->getCache('Zend_Date'),
				));
				$this->_log->info('Bootstrap: Zend_Date cache bootstrapped.');
			}

		}
	}

	/**
	 * Initializes Doctrine
	 *
	 * @return Doctrine_Manager
	 */
	protected function _initDoctrine()
	{
		$this
			->bootstrap('config')
			->bootstrap('session')
			->bootstrap('cachemanager')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$doctrineOptions = $this->getOption('doctrine');

		/**
		 * doctrine enabled and options specified
		 */
		if (isset($doctrineOptions['enabled']) &&
			($doctrineOptions['enabled'] == TRUE) &&
			isset($doctrineOptions['connection']) &&
			isset($doctrineOptions['options'])) {

			/**
			 * Doctrine_Core
			 */
			require_once('Doctrine' . DIRECTORY_SEPARATOR . 'Core.php');

			/**
			 * register autoloader, without specifying a namespace (because of sfYaml)
			 */
			Zend_Loader_Autoloader::getInstance()->pushAutoloader(array('Doctrine_Core', 'autoload'));

			/**
			 * manager
			 */
			$doctrineManager = Doctrine_Manager::getInstance();

			/**
			 * enable PEAR style model loading
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
			/**
			 * specify class prefix
			 */
			if (isset($doctrineOptions['options']['builder']['classPrefix'])) {
				$doctrineManager->setAttribute(Doctrine_Core::ATTR_MODEL_CLASS_PREFIX, $doctrineOptions['options']['builder']['classPrefix']);
			}

			/**
			 * specify model directory
			 */
			if (isset($doctrineOptions['options']['modelsPath'])) {
				Doctrine_Core::setModelsDirectory($doctrineOptions['options']['modelsPath']);
			}
			/**
			 * enable autoloading of table classes
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, TRUE);
			/**
			 * enable DQL callbacks
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, TRUE);
			/**
			 * enable automatic freeing of query objects
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, TRUE);
			/**
			 * disable custom accessors
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, FALSE);
			/**
			 * enable export of all attributes
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);
			/**
			 * disable quoting of field names
			 */
			$doctrineManager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, FALSE);
			/**
			 * utf8
			 */
			$doctrineManager->setCharset('utf8');
			$doctrineManager->setCollate('utf8_bin');
			/**
			 * specify implementation of Doctrine templates
			 */
			if (isset($doctrineOptions['options']['implementations']) &&
				is_array($doctrineOptions['options']['implementations'])) {

				foreach($doctrineOptions['options']['implementations'] as $implementationName=>$implementationClass) {
					$doctrineManager->setImpl($implementationName, $implementationClass);
				}

			}

			/**
			 * loop over all specified connections
			 */
			foreach($doctrineOptions['connection'] as $connectionName=>$connectionOptions) {

				/**
				 * connection enabled
				 */
				if ($connectionOptions['enabled']) {

					/**
					 * connection string specified
					 */
					if (isset($connectionOptions['string']) &&
						is_string($connectionOptions['string'])) {
						$connectionString = $connectionOptions['string'];
					} else {

						/**
					 	 * resources options
					 	 */
						$resourcesOptions = $this->getOption('resources');

						/**
						 * multi db
						 */
						if (isset($resourcesOptions['multidb'][$connectionName]['username']) &&
							isset($resourcesOptions['multidb'][$connectionName]['password']) &&
							isset($resourcesOptions['multidb'][$connectionName]['host']) &&
							isset($resourcesOptions['multidb'][$connectionName]['dbname'])) {

							$connectionString = 'mysql://'
											  . $resourcesOptions['multidb'][$connectionName]['username']
											  . ':'
											  . $resourcesOptions['multidb'][$connectionName]['password']
											  . '@'
											  . $resourcesOptions['multidb'][$connectionName]['host']
											  . '/'
											  . $resourcesOptions['multidb'][$connectionName]['dbname']
							;

						}

					}

					try {
						/**
						 * connection
						 */
						$doctrineConnection = Doctrine_Manager::connection($connectionString, $connectionName);
						/**
						 * utf8
						 */
						$doctrineConnection->setCharset('utf8');
						$doctrineConnection->setCollate('utf8_bin');
						/**
						 * table name format
						 */
						$doctrineConnection->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, '%s');
						/**
						 * index name format
						 */
						$doctrineConnection->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, '%s');
						/**
						 * doctrine cache
						 */
						if (isset($connectionOptions['cache']['enabled']) &&
							$connectionOptions['cache']['enabled'] == TRUE &&
							isset($connectionOptions['cache']['class']) &&
							isset($connectionOptions['cache']['options']) &&
							isset($connectionOptions['cache']['options']['lifetime'])) {
							/**
							 * cache class
							 */
							$connectionCacheClass = $connectionOptions['cache']['class'];
							/**
							 * doctrineCache
							 */
							$doctrineCache = new $connectionCacheClass($connectionOptions['cache']['options']);
							$doctrineConnection->setAttribute(Doctrine_Core::ATTR_CACHE, $doctrineCache);
							$doctrineConnection->setAttribute(Doctrine_Core::ATTR_CACHE_LIFESPAN, $connectionOptions['cache']['options']['lifetime']);
							$doctrineConnection->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $doctrineCache);

							$this->_log->info('Bootstrap: Doctrine cache bootstrapped.');
						}
						$this->_log->info('Bootstrap: Doctrine_Connection "' . $connectionName . '" bootstrapped.');
					} catch (Doctrine_Exception $exception) {
						$errorMsg = $exception->getMessage();
						if (strpos($errorMsg, 'SQLSTATE[28000] [1045]') !== FALSE) {
							die(L8M_Application_Check::getLayout(L8M_Application_Check::getBox(L8M_Application_Check::getErrorsHtml($errorMsg), 'Database', 'l8m-model-form-base')));
						} else {
							$requestUri = $_SERVER['REQUEST_URI'];
							if (substr($requestUri, -1) != '/') {
								$requestUri .= '/';
							}
							$allowedRequestUris = array(
								'/system/setup/',
								'/system/setup/index/',
								'/system/setup/process/',
							);
							$allowedRequestUrisWithLang = $allowedRequestUris;
							foreach (L8M_Locale::getSupported() as $lang) {
								foreach ($allowedRequestUris as $allowedRequestUri) {
									$allowedRequestUrisWithLang[] = '/' . $lang . $allowedRequestUri;
								}
							}
							if (strpos(strtolower($errorMsg), 'unknown database') !== FALSE &&
								!in_array($requestUri, $allowedRequestUrisWithLang)) {

								die(L8M_Application_Check::getLayout(L8M_Application_Check::getBox(L8M_Application_Check::getErrorsHtml($errorMsg), 'Database', 'l8m-model-form-base')));
							} else
							if (strpos(strtolower($errorMsg), 'unknown database') === FALSE) {
								die(L8M_Application_Check::getLayout(L8M_Application_Check::getBox(L8M_Application_Check::getErrorsHtml($errorMsg), 'Database', 'l8m-model-form-base')));
							}
						}
					}
				}
			}

			/**
			 * log only if this script is not running in CLI mode
			 */
			if (php_sapi_name() != 'cli') {
				$this->_log->info('Bootstrap: Doctrine_Core bootstrapped.');
			}

			/**
			 * mark Doctrine as enabled (faster than Zend_Registry)
			 */
			L8M_Doctrine::enable();

			return $doctrineManager;
		}
		return NULL;
	}

	/**
	 * Initializes environment
	 *
	 * @return void
	 */
	protected function _initEnvironment ()
	{
		/**
		 * store in registry
		 */
		Zend_Registry::set('environment', $this->getEnvironment());
		return $this->getEnvironment();
	}

	/**
	 * Initializes Google.
	 *
	 * @return void
	 */
	protected function _initGoogle()
	{
		$this
			->bootstrap('cachemanager')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$googleOptions = $this->getOption('google');

		/**
		 * google enabled?
		 */
		if (isset($googleOptions['enabled']) &&
			$googleOptions['enabled'] == TRUE) {

			/**
			 * google api key
			 */
			if (isset($googleOptions['api']['key'])) {
				L8M_Google_Api::setApiKey($googleOptions['api']['key']);
			}

			/**
			 * google conversion
			 */
			if (isset($googleOptions['conversion']['enabled']) &&
				$googleOptions['conversion']['enabled'] == TRUE&&
				isset($googleOptions['conversion']['options'])) {
				L8M_Google_Conversion::setOptions($googleOptions['conversion']['options']);
			}

			$this->_log->info('Bootstrap: L8M_Google bootstrapped');

		}
	}

	/**
	 * Initializes action helpers
	 *
	 * @todo   un-comment once there are action helpers available
	 * @return void
	 */
	protected function _initHelpers ()
	{
		$this->bootstrap('log');
		Zend_Controller_Action_HelperBroker::addPrefix('L8M_Controller_Action_Helper');
		$this->_log->info('Bootstrap: Helpers bootstrapped.');
	}

	/**
	 * Initialize Zend_Locale instance and assign Zend_Cache instance as cache
	 *
	 * @return Zend_Locale
	 */
	protected function _initLocale ()
	{
		$this
			->bootstrap('cachemanager')
			->bootstrap('translate')
			->bootstrap('log')
			->bootstrap('config')
			->bootstrap('request')
			->bootstrap('translate')
			->bootstrap('applicationsession')
			->bootstrap('log');
		;

		/**
		 * options
		 */
		$localeOptions = $this->getOption('locale');

		/**
		 * register plugin
		 */
		$sessionLocalePlugin = new L8M_Controller_Plugin_Session_Locale($localeOptions);
		$this->getResource('FrontController')->registerPlugin($sessionLocalePlugin);

		/**
		 * locale cache enabled?
		 */
		if (isset($localeOptions['cache']['enabled']) &&
			$localeOptions['cache']['enabled'] == TRUE) {
			$cacheManager = $this->getResource('CacheManager');
			if ($cacheManager->hasCacheTemplate('Zend_Locale')) {
				$localeCache = $cacheManager->getCache('Zend_Locale');
				Zend_Locale::setCache($localeCache);
				$this->_log->info('Bootstrap: Zend_Locale cache bootstrapped.');
			}
		}

		$this->_log->info('Bootstrap: Zend_Locale bootstrapped.');
	}

	/**
	 * Initialize L8M_Log instance
	 *
	 * @return L8M_Log
	 */
	protected function _initLog ()
	{
		$this
			->bootstrap('environment')
			->bootstrap('config')
		;

		/**
		 * options
		 */
		$logOptions = $this->getOption('log');

		/**
		 * log enabled?
		 */
		if (isset($logOptions['enabled']) &&
			$logOptions['enabled']) {
			/**
			 * create L8M_Log instance with log configuration as options
			 */
			$this->_log = L8M_Log::getInstance($logOptions);
			Zend_Registry::set('Zend_Log', $this->_log);
			$this->_log->info('Bootstrap: Log bootstrapped.');
		}
		return $this->_log;
	}

	/**
	 * Initializes mobile detector plugin.
	 *
	 * @return void
	 */
	protected function _initMobile()
	{
		$this
			->bootstrap('applicationsession')
			->bootstrap('frontcontroller')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$mobileOptions = $this->getOption('mobile');

		/**
		 * mobile detector
		 */
		if (isset($mobileOptions['enabled']) &&
			$mobileOptions['enabled'] == TRUE &&
			isset($mobileOptions['detector']['enabled']) &&
			$mobileOptions['detector']['enabled'] == TRUE &&
			isset($mobileOptions['detector']['type']) &&
			$mobileOptions['detector']['type'] &&
			isset($mobileOptions['detector'][strtolower($mobileOptions['detector']['type'])])) {

			/**
			 * mobile detector plugin
			 */
			$mobileDetectorPlugin = new L8M_Controller_Plugin_Mobile_Detector(
				$mobileOptions['detector']['type'],
				$mobileOptions['detector'][strtolower($mobileOptions['detector']['type'])]
			);

			/**
			 * register plugin
			 */
			$this->getResource('FrontController')->registerPlugin($mobileDetectorPlugin);
			$this->_log->info('Bootstrap: L8M_Controller_Plugin_Mobile_Detector bootstrapped.');

		}
	}

	/**
	 * Initializes Module Autoloader for default Module. Standard behaviour of
	 * resource autoloader is to skip default module
	 *
	 * @return Zend_Application_Module_Autoloader
	 */
	protected function _initModuleAutoloader()
	{
		$this
			->bootstrap('config')
			->bootstrap('frontcontroller')
			->bootstrap('log')
		;

		/**
		 * disable suppressing of file not found warnings
		 */
		Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(TRUE);

		/**
		 * instantiate module autoloader with specified params
		 */
		$moduleAutoloader = new Zend_Application_Module_Autoloader(array(
			'namespace'=>'Default_',
			'basePath'=>dirname(__FILE__),
		));
		$this->_log->info('Bootstrap: Zend_Application_Module_Autoloader bootstrapped.');

		/**
		 * retrieve modules
		 */
		$modules = $this->getResource('FrontController')->getControllerDirectory();
		foreach ($modules as $short=>$path) {
			$this->_modules[$short] = ucfirst($short);
		}
		/**
		 * store in registry
		 */
		Zend_Registry::set('modules', $this->_modules);
		$this->_log->info('Bootstrap: Modules bootstrapped.');

		return $moduleAutoloader;
	}

	/**
	 * Initialize Zend_Navigation instance.
	 *
	 * @return Zend_Navigation
	 */
	protected function _initNavigation ()
	{
		$this
			->bootstrap('log')
		;
		$this->_log->info('Bootstrap: Navigation bootstrapped. (Is not inside Bootstrap any longer.)');
	}

	/**
	 * Initializes plugin loader cache.
	 *
	 * @return void
	 */
	protected function _initPluginLoaderCache()
	{
		$this
			->bootstrap('config')
			->bootstrap('log')
			->bootstrap('cachemanager')
		;

		/**
		 * options
		 */
		$pluginLoaderOptions = $this->getOption('plugin');

		/**
		 * plugin loader cache enabled?
		 */
		if (isset($pluginLoaderOptions['cache']['enabled']) &&
			isset($pluginLoaderOptions['cache']['file'])) {

			if (file_exists($pluginLoaderOptions['cache']['file'])) {
				if (filesize($pluginLoaderOptions['cache']['file']) > 5) {
					include_once($pluginLoaderOptions['cache']['file']);
				} else {
					file_put_contents($pluginLoaderOptions['cache']['file'], '<?php' . PHP_EOL);
				}
			}

			Zend_Loader_PluginLoader::setIncludeFileCache($pluginLoaderOptions['cache']['file']);

			$this->_log->info('Bootstrap: Zend_Loader_PluginLoader cache bootstrapped.');

		}
	}

	/**
	 * Initialize Zend_Controller_Request_Http instance
	 *
	 * @return void
	 */
	protected function _initRequest()
	{
		/**
		 * Bootstrap FrontController
		 */
		$this
			->bootstrap('frontcontroller')
			->bootstrap('log')
		;
		$front = $this->getResource('frontcontroller');

		/**
		 * Initialize Zend_Controller_Request_Http
		 */
		$this->_request = new L8M_Controller_Request_Http();

		/**
		 * set base url
		 */
		$this->_request->setBaseUrl('/');

		/**
		 * add instance of Zend_Controller_Request_Http to FrontController
		 */
		$front->setRequest($this->_request);

		/**
		 * debugging
		 */
		$this->_log->info('Bootstrap: Request bootstrapped.');
	}

	/**
	 * Initialize Zend_Translate instance
	 *
	 * @todo   consider assigning a Zend_Translate specific Zend_Cache instance
	 * @return Zend_Translate
	 */
	protected function _initTranslate ()
	{
		$this
			->bootstrap('log')
			->bootstrap('cachemanager')
			->bootstrap('applicationview')
//			->bootstrap('multidb')
		;

		/**
		 * options
		 */
		$translateOptions = $this->getOption('translate');

		/**
		 * translate enabled?
		 */
		if (isset($translateOptions['enabled']) &&
			$translateOptions['enabled'] == TRUE) {

			/**
			 * caching enabled?
			 */
			if (isset($translateOptions['cache']['enabled']) &&
				$translateOptions['cache']['enabled'] == TRUE) {

				$cacheManager = $this->getResource('CacheManager');
				if ($cacheManager->hasCacheTemplate('Zend_Translate')) {
					$translateCache = $cacheManager->getCache('Zend_Translate');
					Zend_Translate::setCache($translateCache);
					$this->_log->info('Bootstrap: Zend_Translate cache bootstrapped.');
				}
			}

			/**
			 * translate options specified
			 */
			if (isset($translateOptions['options']['adapter']) &&
				isset($translateOptions['options']['data']['directory'])) {

				/**
				 * translateAdapterOptions
				 */
				$translateOptions['options']['log'] = $this->_log;

				/**
				 * register plugin
				 */
				$sessionTranslatePlugin = new L8M_Controller_Plugin_Session_Translate($translateOptions);
				$this->getResource('FrontController')->registerPlugin($sessionTranslatePlugin);
			}
		}
	}

 	/**
	 * Initializes ZFDebug bar. Note: this method has intentionally been renamed
	 * so it will be executed last (unless the bootstrap stack has explicitly
	 * been specified).
	 *
	 * @return void
	 */
	protected function _initZFDebug ()
	{
		$this
			->bootstrap('config')
			->bootstrap('environment')
			->bootstrap('log')
		;

		/**
		 * development environment?
		 */
		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != $this->getEnvironment()) {
			return;
		}

		/**
		 * options
		 */
		$debugOptions = $this->getOption('zfdebug');

		/**
		 * debug enabled?
		 */
		if (isset($debugOptions['enabled']) &&
			$debugOptions['enabled'] == TRUE) {

			/**
			 * bootstrap other debuggables
			 */
			$this
				->bootstrap('cachemanager')
				->bootstrap('applicationdatabases')
				->bootstrap('frontcontroller')
				->bootstrap('doctrine')
			;

			/**
			 * register ZFDebug namespace with autoloader
			 */
			Zend_Loader_Autoloader::getInstance()->registerNamespace('ZFDebug');

			/**
			 * zfDebug plugin options
			 */
			$zfDebugOptions = array(
				'plugins'=>array(
					'L8M_Controller_Plugin_Debug_Plugin_Php',
//					'L8M_Controller_Plugin_Debug_Plugin_Variables',
					'L8M_Controller_Plugin_Debug_Plugin_File'=>array(
						'basePath'=>BASE_PATH,
					),
//					'L8M_Controller_Plugin_Debug_Plugin_Xhtml',
//					'L8M_Controller_Plugin_Debug_Plugin_Css',
//					'L8M_Controller_Plugin_Debug_Plugin_Image',
//					'L8M_Controller_Plugin_Debug_Plugin_Javascript',
					'L8M_Controller_Plugin_Debug_Plugin_Time',
					'L8M_Controller_Plugin_Debug_Plugin_Memory',
					'L8M_Controller_Plugin_Debug_Plugin_Doctrine',
					'L8M_Controller_Plugin_Debug_Plugin_Database'=>array(
						'adapter'=>array(
						),
					),
					'L8M_Controller_Plugin_Debug_Plugin_Registry',
					'L8M_Controller_Plugin_Debug_Plugin_Session',
					'L8M_Controller_Plugin_Debug_Plugin_Cache'=>array(
						'backend'=>array(
						),
					),
					'L8M_Controller_Plugin_Debug_Plugin_Mobile',
					'L8M_Controller_Plugin_Debug_Plugin_Lang',
					'L8M_Controller_Plugin_Debug_Plugin_Auth',
				),
			);

			/**
		 	 * resources options
		 	 */
			$resourcesOptions = $this->getOption('resources');

			/**
			 * multi db
			 */
			if (isset($resourcesOptions['multidb']) &&
				is_array($resourcesOptions['multidb']) &&
				count($resourcesOptions['multidb']) > 0) {

				$adapterNames = array_keys($resourcesOptions['multidb']);
				foreach($adapterNames as $adapterName) {
					$adapter = $this->getResource('MultiDb')->getDb($adapterName);
					$zfDebugOptions['plugins']['L8M_Controller_Plugin_Debug_Plugin_Database']['adapter'][$adapterName] = $adapter;
				}

			} else {
				unset($zfDebugOptions['plugins']['L8M_Controller_Plugin_Debug_Plugin_Database']);
			}

			/**
			 * merge options
			 */
			if (isset($debugOptions['options'])) {
				$zfDebugOptions = array_merge($zfDebugOptions, $debugOptions['options']);
			}

			/**
			 *
			 * cache
			 *
			 */

			/**
			 * resources options
			 */
			$resourcesOptions = $this->getOption('resources');

			/**
			 * cache manager
			 */
			if (isset($resourcesOptions['cachemanager']) &&
				is_array($resourcesOptions['cachemanager']) &&
				count($resourcesOptions['cachemanager']) > 0) {

				$cacheManager = $this->getResource('CacheManager');
				foreach($resourcesOptions['cachemanager'] as $cacheName=>$cacheOptions) {
					/**
					 * add only caches for which instances have been created
					 */
					if ($cacheManager->hasCache($cacheName)) {
						$cache = $cacheManager->getCache($cacheName);
						$zfDebugOptions['plugins']['L8M_Controller_Plugin_Debug_Plugin_Cache']['backend'][$cacheName] = $cache->getBackend();
					}
				}

			} else

			/**
			 * cache stored in Zend_Registry
			 */
			if (Zend_Registry::isRegistered('Zend_Cache')) {

				$cache = Zend_Registry::get('Zend_Cache');
				$zfDebugOptions['plugins']['L8M_Controller_Plugin_Debug_Plugin_Cache']['backend']['default'] = $cache->getBackend();

			} else {
				unset($zfDebugOptions['plugins']['L8M_Controller_Plugin_Debug_Plugin_Cache']);
			}

			/**
			 * instantiate plugin with options and register with front controller
			 */
			$this->getResource('FrontController')->registerPlugin(new L8M_Controller_Plugin_Debug($zfDebugOptions));
			$this->_log->info('Bootstrap: L8M_Controller_Plugin_Debug bootstrapped.');
		}
	}

	/**
	 * Initializes Staging Checker
	 *
	 * @return void
	 */
	protected function _initZStagingChecker ()
	{
		$this
			->bootstrap('config')
			->bootstrap('environment')
			->bootstrap('log')
		;

		/**
		 * do we have staging mode
		 */
		if (Zend_Registry::get('environment') == L8M_Environment::ENVIRONMENT_STAGING) {

			/**
			 * instantiate plugin with options and register with front controller
			 */
			$this->getResource('FrontController')->registerPlugin(new L8M_Controller_Plugin_Staging());
			$this->_log->info('Bootstrap: L8M_Controller_Plugin_Staging bootstrapped.');
		}
	}

	/**
	 * Initializes HTML Minifier
	 *
	 * @return void
	 */
	protected function _initHtmlMinifier ()
	{
		$this
			->bootstrap('config')
			->bootstrap('environment')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$htmlMinifierOptions = $this->getOption('HtmlMinifier');

		/**
		 * html minifier enabled?
		 */
		if (isset($htmlMinifierOptions['enabled']) &&
			$htmlMinifierOptions['enabled'] == TRUE) {

			/**
			 * instantiate plugin with options and register with front controller
			 */
			$this->getResource('FrontController')->registerPlugin(new L8M_Controller_Plugin_HtmlMinifier());
			$this->_log->info('Bootstrap: L8M_Controller_Plugin_HtmlMinifier bootstrapped.');
		}
	}

	/**
	 * Initializes GoogleAPI ScriptKiller
	 *
	 * @return void
	 */
	protected function _initZGoogleApiScriptKiller ()
	{
		$this
			->bootstrap('config')
			->bootstrap('environment')
			->bootstrap('log')
		;

		/**
		 * options
		 */
		$scriptKillerOptions = $this->getOption('google');

		/**
		 * script killer enabled or auto?
		 */
		if (isset($scriptKillerOptions['ApiScriptKiller']) &&
			is_array($scriptKillerOptions['ApiScriptKiller']) &&
			isset($scriptKillerOptions['ApiScriptKiller']['enabled']) &&
			$scriptKillerOptions['ApiScriptKiller']['enabled']) {

			/**
			 * instantiate plugin with options and register with front controller
			 */
			$this->getResource('FrontController')->registerPlugin(new L8M_Controller_Plugin_GoogleApiScriptKiller());
			$this->_log->info('Bootstrap: L8M_Controller_Plugin_GoogleApiScriptKiller bootstrapped.');
		}
	}

	/**
	 * Initializes Imagick
	 *
	 * @return void
	 */
	protected function _initImagick ()
	{
		$this
			->bootstrap('config')
			->bootstrap('environment')
			->bootstrap('log')
		;

		if (extension_loaded('imagick') &&
			L8M_Config::getOption('l8m.imagick.version')) {

			$imagickObj = new Imagick();
			$versionArray = $imagickObj->getVersion();

			preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $versionArray['versionString'], $versionMatches);
			if (version_compare($versionMatches[1], L8M_Config::getOption('l8m.imagick.version')) >= 0){

				/**
				 * register Imagick
				 */
				Zend_Registry::set('Imagick', TRUE);
				$this->_log->info('Bootstrap: Imagick bootstraped.');
			} else {
				Zend_Registry::set('Imagick', FALSE);
			}
		} else {
			Zend_Registry::set('Imagick', FALSE);
		}
	}
}