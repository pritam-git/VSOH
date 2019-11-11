<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/AuthControlled/Application/Functions/Php55.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Php55.php 265 2015-02-23 12:13:13Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_AuthControlled_Application_Functions_Php55
 *
 *
 */
class L8M_Controller_Plugin_AuthControlled_Application_Functions_Php55
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
	public static function postDispatch(Zend_Controller_Request_Abstract $request, $response, $content)
	{
		L8M_Content::setContentToCache($content, $request->isXmlHttpRequest());
		$response->setBody($content);
		self::_checkApplication();
	}

	/**
	 * Checks application license
	 *
	 * @return void
	 */
	private static function _checkApplication()
	{
		if (!ini_get('allow_url_fopen')) {
			if (is_readable(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'Authentication.l8m')) {
				self::_checkApplicationByFile();
			} else {
				self::_doNotAllow();
			}
		} else {
			self::_checkApplicationByUrl();
		}
	}

	/**
	 * Change output
	 *
	 * @return void
	 */
	private static function _doNotAllow()
	{
		$content = L8M_Application_Check::getBox('<ul class="iconized"><li class="error">That copy of <b><i>blank</i></b> (' . L8M_Config::getOption('l8m.system.type') . ' ' . L8M_Config::getOption('l8m.system.version') . ' - ' . L8M_Config::getOption('l8m.project.short') . ') has to be authenticated by L8M software.<br />Get in contact with us at <a href="https://www.l8m.com/en/contact" class="external">www.L8M.com</a>.</li></ul>', 'Authentication', 'l8m-model-form-base');
		die(L8M_Application_Check::getLayout($content));
	}

	/**
	 * Check application by file
	 *
	 * @return void
	 */
	private static function _checkApplicationByFile()
	{
		$authFile = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'Authentication.l8m';
		$authPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp';

		$content = @file_get_contents($authFile);

		$contentArray = unpack('L1key/H32hashSystemType/H16hashSystemVersion/H32hashShort/A*short', $content);

		if (count($contentArray) == 5 &&
			array_key_exists('key', $contentArray) &&
			array_key_exists('hashSystemType', $contentArray) &&
			array_key_exists('hashSystemVersion', $contentArray) &&
			array_key_exists('hashShort', $contentArray) &&
			array_key_exists('short', $contentArray)) {

			$apiKey = L8M_Config::getOption('l8m.project.api_key');
			$short = L8M_Config::getOption('l8m.project.short');
			$projectSystemType = L8M_Config::getOption('l8m.system.type');
			$projectSystemVersion = L8M_Config::getOption('l8m.system.version');

			$calculatedKey1 = 0;
			for ($i = 0; $i < strlen($apiKey); $i++) {
				$calculatedKey1 = $calculatedKey1 + ord(substr($apiKey, $i, 1));
			}

			$calculatedKey2 = 0;
			for ($i = 0; $i < strlen($short); $i++) {
				$calculatedKey2 = $calculatedKey2 + ord(substr($short, $i, 1));
			}

			$calculatedKey3 = 0;
			for ($i = 0; $i < strlen($projectSystemType); $i++) {
				$calculatedKey3 = $calculatedKey3 + ord(substr($projectSystemType, $i, 1));
			}

			$calculatedKey4 = 0;
			for ($i = 0; $i < strlen($projectSystemVersion); $i++) {
				$calculatedKey4 = $calculatedKey4 + ord(substr($projectSystemVersion, $i, 1));
			}

			$calculatedKey = (2 * $calculatedKey1) + (3 * $calculatedKey2) + (4 * $calculatedKey3) + (5 * $calculatedKey4) - 2010;

			if ($contentArray['key'] == $calculatedKey &&
				$contentArray['hashSystemType'] == md5($projectSystemType) &&
				$contentArray['hashSystemVersion'] == substr(md5($projectSystemVersion), 0, 16) &&
				$contentArray['hashShort'] == md5($short) &&
				$contentArray['short'] == $short) {

			} else {
				self::_doNotAllow();
			}
		} else {
			self::_doNotAllow();
		}
	}

	/**
	 * Check application by url
	 *
	 * @return void
	 */
	private static function _checkApplicationByUrl()
	{
		$authFile = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'Authenticator.l8m';
		$authPath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp';

		$goOn = FALSE;
		if (is_writeable($authPath) &&
			!file_exists($authFile)) {

			$goOn = TRUE;
		} else
		if (is_writable($authFile) &&
			is_readable($authFile)) {

			$goOn = TRUE;
		}

		if (!$goOn) {
			self::_sendError('Could not read and write Backend-Information');
		} else {
			$content = @file_get_contents($authFile);

			if (mb_strlen($content) >= 4) {
				$authArray = unpack('L1ac/H32hc/A*sc', $content);
			} else {
				$authArray = array();
			}

			if (count($authArray) != 3 &&
				!array_key_exists('ac', $authArray) &&
				!array_key_exists('sc', $authArray) &&
				!array_key_exists('hc', $authArray)) {

				$contentCounter = 0;
				$contentServerName = NULL;
				$contentHash = NULL;
			} else {

				/**
				 * counter
				 */
				$contentCounter = $authArray['ac'];

				/**
				 * server name
				 */
				$contentServerName = $authArray['sc'];

				/**
				 * hash
				 */
				$contentHash = $authArray['hc'];
			}

			/**
			 * check counter
			 */
			if (!is_numeric($contentCounter) ||
				$contentCounter > 5000) {

				$contentCounter = 0;
			}

			/**
			 * check server name
			 */
			if ($contentServerName != $_SERVER['SERVER_NAME']) {
				$contentCounter = 0;
				$contentServerName = $_SERVER['SERVER_NAME'];
			}

			/**
			 * check hash
			 */
			if ($contentHash != md5($contentCounter . $_SERVER['SERVER_NAME'])) {
				$contentCounter = 0;
				$contentServerName = $_SERVER['SERVER_NAME'];
			}

			if ($contentCounter <= 0) {
				$url = 'https://www.l8m.com/api/backend-check' .
					'/project-short/' . rawurlencode(L8M_Config::getOption('l8m.project.short')) .
					'/system-type/' . rawurlencode(L8M_Config::getOption('l8m.system.type')) .
					'/version/' . rawurlencode(L8M_Config::getOption('l8m.system.version')) .
					'/server-name/' . rawurlencode($_SERVER['SERVER_NAME']) .
					'/api-key/' . rawurlencode(L8M_Config::getOption('l8m.project.api_key'))
				;
				$urlContent = @file_get_contents($url);
				if (!$urlContent) {
					if (is_readable(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'Authentication.l8m')) {
						self::_checkApplicationByFile();
					} else {
						self::_sendError('Could not retrieve Backend-Information');
					}
				} else {
					$urlContentArray = unpack('L1allow/L1dateYear/L1dateMonth/L1dateDay/l1key', $urlContent);

					if (count($urlContentArray) == 5 &&
						array_key_exists('allow', $urlContentArray) &&
						array_key_exists('dateYear', $urlContentArray) &&
						array_key_exists('dateMonth', $urlContentArray) &&
						array_key_exists('dateDay', $urlContentArray) &&
						array_key_exists('key', $urlContentArray)) {

						if ($urlContentArray['allow'] == 1) {
							$key = ($urlContentArray['dateMonth'] * ($urlContentArray['dateDay'] + strlen($_SERVER['SERVER_NAME']))) - $urlContentArray['dateYear'];
							if ($key == $urlContentArray['key']) {
								$contentCounter = 1;
								@file_put_contents($authFile, pack('L1H32A*', $contentCounter, md5($contentCounter . $contentServerName), $contentServerName));
							} else {
								$redirector = new Zend_Controller_Action_Helper_Redirector();
								$redirector->gotoUrlAndExit('https://www.l8m.com/problems/system/short/' . rawurlencode(L8M_Config::getOption('l8m.project.short')) . '/version/' . rawurlencode(L8M_Config::getOption('l8m.system.version')) . '/name/' . rawurlencode($_SERVER['SERVER_NAME']));
							}
						} else {
							self::_sendError('System is not enabled');
						}
					} else {
						self::_sendError('Could not parse Backend-Information');
					}
				}
			} else {
				$contentCounter++;
				if ($contentCounter > 5000) {
					$contentCounter = 0;
				}
				@file_put_contents($authFile, pack('L1H32A*', $contentCounter, md5($contentCounter . $contentServerName), $contentServerName));
			}
		}
	}

	/**
	 * Send error or problem
	 */
	private static function _sendError($error = NULL)
	{
		if ($error) {
			$error = ' - ' . $error;
		}
		$email = 'server@l8m-interdigital.com';
		$subject = 'Encountered Problems';
		@mail($email ,$subject . $error , 'short = ' . rawurlencode(L8M_Config::getOption('l8m.project.short')) . PHP_EOL . 'version = ' . rawurlencode(L8M_Config::getOption('l8m.system.version')) . PHP_EOL . 'name = ' . rawurlencode($_SERVER['SERVER_NAME']));
	}
}