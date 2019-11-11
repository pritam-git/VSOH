<?php

/**
 * L8M
 *
 *
 * This file contains the media handler script. It handles requests directed to
 * non-existent images or files (as defined via mod_rewrite rules in the
 * .htaccess file in /public).
 *
 * @filesource /public/utility/media.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: mediashort.php 431 2015-09-28 13:15:12Z nm $
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
 *       scanning through include paths
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
	->bootstrap('cachemanager')
	->bootstrap('doctrine')
	->bootstrap('moduleautoloader')
	->bootstrap('session')
;

/**
 * request
 */
$request = new Zend_Controller_Request_Http();

/**
 * media
 */
$media = Default_Service_Media::fromRequest($request, TRUE);

/**
 * start respons
 */
$response = new Zend_Controller_Response_Http();
$response
	->clearAllHeaders()
	->setHeader('Expires', date('r', time() + 365*24*60*60), TRUE)
;
if (!($media instanceof Default_Model_Media)) {
	/**
	 * media could not be retrieved
	 */
	$fileShort = 'not_found.png';
	$fileName = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $fileShort;
	if (!file_exists($fileName)) {

		/**
		 * Create the image
		 */
		$im = imagecreatetruecolor(140, 30);

		/**
		 * Create some colors
		 */
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);

		/**
		 * create background
		 */
		imagefilledrectangle($im, 0, 0, 139, 29, $white);

		/**
		 * The text to draw
		 */
		$text = 'HTTP/1.1 404 Not Found';

		/**
		 * Add the text
		 */
		imagestring($im, 1, 10, 10, $text, $black);

		/**
		 * save image using imagepng()
		 */
		imagepng($im, $fileName);

		imagedestroy($im);
	}

	$response
		->setHeader('Content-Type', 'image/png')
		->setHeader('Content-Disposition', 'attachment; filename="' . $fileShort . '"')
		->setBody(file_get_contents($fileName))
	;
} else {

	/**
	 * ACL
	 */
	$mediaAcl = new L8M_Acl_Media();
	if ($mediaAcl->checkMedia($media)) {

		/**
		 * media could be retrieved, let's send it
		 */
		$response
			->setHeader('Content-Type', $media->mime_type)
			->setHeader('Content-Disposition', 'attachment; filename="' . $media->file_name . '"')
			->setBody($media->getContent())
		;
	} else {

		/**
		 * media is not allowed to be seen with that role
		 */
		$fileShort = 'not_allowed.png';
		$fileName = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $fileShort;
		if (!file_exists($fileName)) {

			/**
			 * Create the image
			 */
			$im = imagecreatetruecolor(140, 30);

			/**
			 * Create some colors
			 */
			$white = imagecolorallocate($im, 255, 255, 255);
			$black = imagecolorallocate($im, 0, 0, 0);

			/**
			 * create background
			 */
			imagefilledrectangle($im, 0, 0, 139, 29, $white);

			/**
			 * The text to draw
			 */
			$text = 'HTTP/1.1 403 Forbidden';

			/**
			 * Add the text
			 */
			imagestring($im, 1, 10, 10, $text, $black);

			/**
			 * save image using imagepng()
			 */
			imagepng($im, $fileName);

			imagedestroy($im);
		}

		$response
			->setHeader('Content-Type', 'image/png')
			->setHeader('Content-Disposition', 'attachment; filename="' . $fileShort . '"')
			->setBody(file_get_contents($fileName))
		;
	}
}

/**
 * response
 */
$response
	->sendResponse()
;