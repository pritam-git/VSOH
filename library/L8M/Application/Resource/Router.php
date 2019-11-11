<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Resource/Router.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Router.php 378 2015-07-08 10:16:41Z nm $
 */

/**
 *
 *
 * L8M_Application_Resource_Router
 *
 *
 */
class L8M_Application_Resource_Router extends Zend_Application_Resource_Router
{
	public $_explicitType = 'router';

	protected $_front;
	protected $_locale;


	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	/**
	 * Retrieve router object
	 *
	 * @return Zend_Controller_Router_Rewrite
	 */
	public function getRouter()
	{
		$bootstrap = $this->getBootstrap();
		$supportedLocales = L8M_Locale::getSupported();
		$possibleModules = Zend_Controller_Front::getInstance()->getControllerDirectory();
		$routeKey = 'controller_index';

		/**
		 * Retrieve FrontController
		 */
		if (!$this->_front) {
			$bootstrap->bootstrap('FrontController');
			$this->_front = $bootstrap->getContainer()->frontcontroller;
		}

		/**
		 * Set Router
		 */
		$router = new L8M_Controller_Router_Rewrite();
		$this->_front->setRouter($router);

		/**
		 * Set default Navigation Page to L8M
		 */
		Zend_Navigation_Page::setDefaultPageType('L8M_Navigation_Page_Mvc');

		/**
		 *
		 * @var Zend_Controller_Request_Http
		 */
		$request = $this->_front->getRequest();

		/**
		 * fall-back default
		 */
		$defaultLocale = L8M_Config::getOption('locale.default');

		/**
		 * locale default concerning TLD
		 */
		$defaultTldLocales = L8M_Config::getOption('locale.tldLocales');
		if (is_array($defaultTldLocales) &&
			count($defaultTldLocales) > 0) {

			/**
			 * Check host header TLD.
			 */
			$usedTLD = preg_replace('/^.*\./', '', $request->getHeader('Host'));

			if (array_key_exists($usedTLD, $defaultTldLocales) &&
				$defaultTldLocales[$usedTLD]) {

				$defaultLocale = $defaultTldLocales[$usedTLD];
				L8M_Locale::setTldDefaultLang($defaultLocale);
			}
		}

		/**
		 * check redirect to right URL, prevent double URL to same action
		 */
		$uriParts = explode('/', ltrim($request->getPathInfo(), '/\\'));
		if (count($uriParts) > 0 &&
			$uriParts[0] == $defaultLocale) {

			unset($uriParts[0]);
			$url = '/' . ltrim(implode('/', $uriParts), '/\\');

			/**
			 * redirect using Zend_Register, that is called again in init-function of L8M_Controller_Action
			 */
			Zend_Registry::set('L8M_Application_Resource_Router_Redirect', $url);
		}

		/**
		 * check whether to route with lang or not
		 */
		$firstUriPart = array_shift($uriParts);
		if ($firstUriPart &&
			in_array($firstUriPart, $supportedLocales)) {

			/**
			 * route with lang part
			 */
			$routeWithLang = TRUE;

			/**
			 * check for module
			 */
			$secondUriPart = array_shift($uriParts);
			if (array_key_exists($secondUriPart, $possibleModules)) {
				$routeKey = 'module_default';
			}

			/**
			 * check if backend and supported lang
			 */
			if (in_array($secondUriPart, L8M_Config::getOption('locale.backend.modules'))) {
				if (!in_array($firstUriPart, L8M_Config::getOption('locale.backend.supported'))) {

					$url = '/' . rtrim(ltrim($secondUriPart . '/' . implode('/', $uriParts), '/\\'), '/');
					if (L8M_Config::getOption('locale.backend.default') != $defaultLocale) {
						$url = '/' . L8M_Config::getOption('locale.backend.default') . $url;
					}

					/**
					 * redirect using Zend_Register, that is called again in init-function of L8M_Controller_Action
					 */
					Zend_Registry::set('L8M_Application_Resource_Router_Redirect', $url);
				}
			}
		} else {

			/**
			 * route without lang part
			 */
			$routeWithLang = FALSE;

			/**
			 * check for module
			 */
			if (array_key_exists($firstUriPart, $possibleModules)) {
				$routeKey = 'module_default';
			} else
			if (in_array($firstUriPart, L8M_Config::getOption('locale.backend.supported'))) {

				/**
				 * check for module and if backend and supported lang
				 */
				$secondUriPart = array_shift($uriParts);
				if (array_key_exists($secondUriPart, $possibleModules) &&
					in_array($secondUriPart, L8M_Config::getOption('locale.backend.modules'))) {

					/**
					 * route with lang part
					 */
					$routeWithLang = TRUE;
					$routeKey = 'module_default';
					$supportedLocales = L8M_Config::getOption('locale.backend.supported');
					$defaultLocale = L8M_Config::getOption('locale.backend.default');
				}
			}
		}


		/**
		 * set locales regex for routes
		 */
		$requiredLocalesRegex = '^(' . join('|', $supportedLocales) . ')$';

		/**
		 * set routes
		 */
		$routeValue = L8M_Config::getOption('resources.router.routes.' . $routeKey);
		if (is_array($routeValue)) {

			/**
			 * First let's add the default locale to this routes defaults.
			 */
			if (isset($routeValue['defaults'])) {
				$defaults = $routeValue['defaults'];
			} else {
				$defaults = array();
			}

			/**
			 * Always default all routes to the Zend_Locale default
			 */
			$routeValue['defaults'] = array_merge(array( 'lang' => $defaultLocale ), $defaults);

			/**
			 * Get our route and make sure to remove the first forward slash since it's not needed.
			 */
			$routeString = $routeValue['route'];
			$routeString = ltrim($routeString, '/\\');

			/**
			 * Modify our normal route to have the locale parameter.
			 */
			if (!isset($routeValue['type']) ||
				$routeValue['type'] === 'Zend_Controller_Router_Route' ||
				$routeValue['type'] === 'L8M_Controller_Router_Route') {

				$routeValue['type'] = 'L8M_Controller_Router_Route';

				if ($routeWithLang) {
					$routeValue['route'] = ':lang/' . $routeString;
				} else {
					$routeValue['route'] = $routeString;
				}

				$routeValue['reqs']['lang'] = $requiredLocalesRegex;
				$newRoutes['lang_' . $routeKey] = $routeValue;
			}
		}

		$options = $this->getOptions();
		$options['routes'] = $newRoutes;
		$this->setOptions($options);
		return parent::getRouter();
	}
}