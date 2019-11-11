<?php

/**
 * L8M
 *
 *
 * This file contains the Minify handler script. It handles requests
 * directed to non-existent CSS and Javascript files that seem to be located in
 * "/css/" or "/js/" and below and end with ".min.css" or ".min.js",
 * respectively, but don't.
 *
 * @filesource /public/utility/minify.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: minify.php 431 2015-09-28 13:15:12Z nm $
 */

/**
 * applicationStart
 */
if (!defined('APPLICATION_START')) {
	define('APPLICATION_START', microtime());
}

/**
 * basePath
 */
if (!defined('BASE_PATH')) {
	$basePath = dirname(__FILE__)
			  . DIRECTORY_SEPARATOR
			  . '..'
			  . DIRECTORY_SEPARATOR
			  . '..'
	;
	$basePath = realpath($basePath);
	define('BASE_PATH', $basePath);
}

/**
 * applicationPath
 */
if (!defined('APPLICATION_PATH')) {
	$applicationPath = BASE_PATH
					 . DIRECTORY_SEPARATOR
					 . 'application'
	;
	define('APPLICATION_PATH', $applicationPath);
}

/**
 * libraryPath
 */
$libraryPath = BASE_PATH
			 . DIRECTORY_SEPARATOR
			 . 'library'
;

/**
 * publicPath
 */
if (!defined('PUBLIC_PATH')) {
	$publicPath = dirname(__FILE__);
	$publicPath = realpath($publicPath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
	define('PUBLIC_PATH', $publicPath);
}

/**
 * includePaths
 */
set_include_path(implode(PATH_SEPARATOR, array(
	$libraryPath,
	get_include_path(),
)));

/**
 * applicationEnvironment
 */
if (!defined('APPLICATION_ENV')) {
	$applicationEnvironment = getenv('APPLICATION_ENV');
	if (!$applicationEnvironment) {
		$environmentConfiguration = APPLICATION_PATH
								  . DIRECTORY_SEPARATOR
								  . 'configs'
								  . DIRECTORY_SEPARATOR
								  . 'environment.ini'
		;
		require_once('L8M' . DIRECTORY_SEPARATOR . 'Environment.php');
		$applicationEnvironment = L8M_Environment::getInstance($environmentConfiguration)->getEnvironment();
		if ($applicationEnvironment == L8M_Environment::ENVIRONMENT_UNKNOWN) {
			die('Could, but will not run in unknown environment.');
		}
	}
	define('APPLICATION_ENV', $applicationEnvironment);
}

/**
 * classes
 *
 * @todo optimize by requiring classes from where they reside rather than
 *	   scanning through include paths
 */
require_once('Zend' . DIRECTORY_SEPARATOR . 'Application.php');

/**
 * applicationConfiguration
 */
$applicationConfiguration = APPLICATION_PATH
						  . DIRECTORY_SEPARATOR
						  . 'configs'
						  . DIRECTORY_SEPARATOR
						  . 'application.ini'
;

/**
 * application
 */
$application = new Zend_Application(APPLICATION_ENV, $applicationConfiguration);
$application->getBootstrap()
	->bootstrap('config')
	->bootstrap('log')
	->bootstrap('applicationcachemanager')
;

/**
 * if it can't be a request for a resource to be found (as we won't minify
 * anything other), send only a corresponding HTTP status code
 */
if (!preg_match_all(L8M_Utility_Minify_Abstract::PATTERN_URL_FILES_MINIFIED, $_SERVER['REQUEST_URI'], $match)) {


	$response = new Zend_Controller_Response_Http();
	$response
		->clearAllHeaders()
		->setHttpResponseCode(404)
		->sendResponse()
	;


} else {

	/**
	 * minifyType
	 */
	$minifyType = $match['type'][0];

	/**
	 * md5Encoded
	 */
	$key = $match['md5Encoded'][0];

	/**
	 * cache enabled?
	 */
	if (Zend_Registry::isRegistered('Zend_Cache_Manager') &&
		(NULL != $cacheManager = Zend_Registry::get('Zend_Cache_Manager')) &&
		($cacheManager instanceof Zend_Cache_Manager) &&
		$cacheManager->hasCacheTemplate('L8M_Utility_Minify')) {

		/**
		 * cache
		 */
		$cache = $cacheManager->getCache('L8M_Utility_Minify');

		/**
		 * id
		 */
		$id = L8M_Cache::getCacheId('L8M_Utility_Minify', $key);

		/**
		 * files
		 */
		$files = $cache->load($id);

		if (!is_array($files)) {

			$response = new Zend_Controller_Response_Http();
			$response
				->clearAllHeaders()
				->setHttpResponseCode(404)
				->sendResponse()
			;

		} else {


			/**
			 * options
			 */
			$options = Zend_Registry::get('Zend_Config')->toArray();

			/**
			 * if minifying has not been enabled for the requested type of resource,
			 * send a corresponding HTTP status code
			 */
			if (!isset($options['minify']['enabled']) ||
				$options['minify']['enabled'] == FALSE |
				!isset($options['minify'][$minifyType]['enabled']) ||
				$options['minify'][$minifyType]['enabled'] == FALSE) {

				$response = new Zend_Controller_Response_Http();
				$response
					->clearAllHeaders()
					->setHttpResponseCode(503)
					->sendResponse()
				;

			} else {

				/**
				 * reset specific options if none are set
				 */
				if (!isset($options['minify'][$minifyType]['options'])) {
					$options['minify'][$minifyType]['options'] = array();
				}

				/**
				 * pass through global copyright notice if no specific copyright is set
				 */
				if (isset($options['minify']['copyright']) &&
					!isset($options['minify'][$minifyType]['options']['copyright'])) {
					$options['minify'][$minifyType]['options']['copyright'] = $options['minify']['copyright'];
				}

				/**
				 * minifier
				 */
				$minifier = L8M_Utility_Minify::factory($minifyType, $options['minify'][$minifyType]['options']);

				/**
				 * if no proper minifier instance could be created, send a corresponding
				 * HTTP status code
				 */
				if (!($minifier instanceof L8M_Utility_Minify_Abstract)) {

					$response = new Zend_Controller_Response_Http();
					$response
						->clearAllHeaders()
						->setHttpResponseCode(503)
						->sendResponse()
					;

				} else {

					/**
					 * minify resources and store them in cache
					 */
					$minified = $minifier->minifyFiles($files, $options['minify'][$minifyType]['options']);

					/**
					 * cache, if caching is enabled - should only be disabled
					 * in development mode, when developing css
					 */
					if (isset($options['minify']['cache']['enabled']) &&
						$options['minify']['cache']['enabled'] == TRUE) {

						/**
						 * static file name
						 */
						$staticFileName = $_SERVER['REQUEST_URI'];

						/**
						 * staticFilePath
						 */
						$staticFilePath = PUBLIC_PATH
										. $staticFileName;
						;
						$staticFilePath = preg_replace('/\//', DIRECTORY_SEPARATOR, $staticFilePath);

						/**
						 * staticFile
						 */
						$staticFile = fopen($staticFilePath, 'w+');
						fwrite($staticFile, $minified);
						fclose($staticFile);

					}

					/**
					 * HTTP 200, send minified resources
					 */
					$response = new Zend_Controller_Response_Http();
					$response
						->clearAllHeaders()
						->setHeader('Content-Type', 'text/' . $minifyType . '; charset=utf-8')
//						->setHeader('Expires', date('r', $expires), TRUE)
//						->setHeader('Last-Modified', date('r', $lastModified), TRUE)
						->setBody($minified)
						->sendResponse()
					;

				}
			}

		}

	}
}