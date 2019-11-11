<?php

/**
 * L8M
 *
 *
 * This file is the public index of the application. Almost all requests are
 * routed through this file.
 *
 * @filesource /public/index.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: index.php 7 2014-03-11 16:18:40Z nm $
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
	$publicPath = realpath($publicPath);
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
 * define that preventing notice
 */
define('Zend', TRUE);

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
 * application Check
 */
require_once('L8M' . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Check.php');
$aaplicationCheck = L8M_Application_Check::factory();
if (!$aaplicationCheck->checkSystem()) {
	die($aaplicationCheck->generateOutput());
}

/**
 * classes
 *
 * @todo optimize by requiring classes from where they reside rather than
 *       scanning through include paths
 */
require_once('Zend' . DIRECTORY_SEPARATOR . 'Application.php');

/**
 * applicationConfiguration
 */
$applicationConfigurationPath = APPLICATION_PATH
						  . DIRECTORY_SEPARATOR
						  . 'configs'
						  . DIRECTORY_SEPARATOR
;
if (($applicationEnvironment == L8M_Environment::ENVIRONMENT_DEVELOPMENT || $applicationEnvironment == L8M_Environment::ENVIRONMENT_STAGING) &&
	file_exists($applicationConfigurationPath . 'myApplication.ini')) {

	$applicationConfiguration = $applicationConfigurationPath . 'myApplication.ini';
} else {
	$applicationConfiguration = $applicationConfigurationPath . 'application.ini';
}

/**
 * application
 */
$application = new Zend_Application(APPLICATION_ENV, $applicationConfiguration);
$application
	->bootstrap()
	->run()
;