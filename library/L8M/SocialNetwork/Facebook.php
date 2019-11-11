<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/SocialNetwork/Facebook.php
 * @author     Norbert Marks <nm@l8m.com>
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Facebook.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *		 http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

/**
 * Extends the L8M_SocialNetwork_Facebook_Abstract class with the intent of using
 * PHP sessions to store user ids and access tokens.
 */
class L8M_SocialNetwork_Facebook extends L8M_SocialNetwork_Facebook_Abstract
{
	const FBSS_COOKIE_NAME = 'fbss';

	// We can set this to a high number because the main session
	// expiration will trump this.
	const FBSS_COOKIE_EXPIRE = 31556926; // 1 year

	// Stores the shared session ID if one is set.
	protected $sharedSessionID;

	/**
	 * session namespace
	 *
	 * @var Zend_Session_Namespace
	 */
	protected $_session = NULL;

	/**
	 * support keys
	 * @var array
	 */
	protected static $kSupportedKeys = array(
		'state',
		'code',
		'access_token',
		'user_id'
	);

	/**
	 * Identical to the parent constructor, except that
	 * we start a PHP session to store the user ID and
	 * access token if during the course of execution
	 * we discover them.
	 *
	 * @param Array $config the application configuration. Additionally
	 * accepts "sharedSession" as a boolean to turn on a secondary
	 * cookie for environments with a shared session (that is, your app
	 * shares the domain with other apps).
	 * @see L8M_SocialNetwork_Facebook_Abstract::__construct in facebook.php
	 */
	public function __construct($config) {

		if (!function_exists('curl_init')) {
			throw new Exception('Facebook needs the CURL PHP extension.');
		}
		if (!function_exists('json_decode')) {
			throw new Exception('Facebook needs the JSON PHP extension.');
		}

		$this->_session = new Zend_Session_Namespace('L8M_SocialNetwork_Facebook');

		parent::__construct($config);
		if (!empty($config['sharedSession'])) {
			$this->initSharedSession();
		}
	}

	/**
	 * returns an L8M_SocialNetwork_Facebook instance
	 *
	 * @param string $key
	 * @return L8M_SocialNetwork_Facebook
	 */
	public static function factory($key = 'base') {
		$returnValue = NULL;

		if (L8M_Config::getOption('facebook.enabled')) {
			$facebookConfig = L8M_Config::getOption('facebook.apps.' . $key);

			if (array_key_exists('appId', $facebookConfig) &&
				array_key_exists('secret', $facebookConfig)) {

				$configArray = array(
					'appId'  => $facebookConfig['appId'],
					'secret' => $facebookConfig['secret'],
					'cookie' => TRUE,
				);
			} else {
				$configArray = array();
			}

			$returnValue = new L8M_SocialNetwork_Facebook($configArray);
		}

		return $returnValue;
	}

	protected function initSharedSession() {
		$goOn = TRUE;
		$cookie_name = $this->getSharedSessionCookieName();
		if (isset($_COOKIE[$cookie_name])) {
			$data = $this->parseSignedRequest($_COOKIE[$cookie_name]);
			if ($data && !empty($data['domain']) &&
					self::isAllowedDomain($this->getHttpHost(), $data['domain'])) {
				// good case
				$this->sharedSessionID = $data['id'];
				$goOn = FALSE;
			}
			// ignoring potentially unreachable data
		}
		// evil/corrupt/missing case

		if ($goOn) {
			$base_domain = $this->getBaseDomain();
			$this->sharedSessionID = md5(uniqid(mt_rand(), true));
			$cookie_value = $this->makeSignedRequest(
				array(
					'domain' => $base_domain,
					'id' => $this->sharedSessionID,
				)
			);
			$_COOKIE[$cookie_name] = $cookie_value;
			if (!headers_sent()) {
				$expire = time() + self::FBSS_COOKIE_EXPIRE;
				setcookie($cookie_name, $cookie_value, $expire, '/', '.'.$base_domain);
			} else {
				// @codeCoverageIgnoreStart
				self::errorLog(
					'Shared session ID cookie could not be set! You must ensure you '.
					'create the Facebook instance before headers have been sent. This '.
					'will cause authentication issues after the first request.'
				);
				// @codeCoverageIgnoreEnd
			}
		}
	}

	/**
	 * Provides the implementations of the inherited abstract
	 * methods.	The implementation uses PHP sessions to maintain
	 * a store for authorization codes, user ids, CSRF states, and
	 * access tokens.
	 */
	protected function setPersistentData($key, $value) {
		if (!in_array($key, self::$kSupportedKeys)) {
			self::errorLog('Unsupported key passed to setPersistentData.');
		} else {
			$session_var_name = $this->constructSessionVariableName($key);
			$this->_session->$session_var_name = $value;
		}
	}

	protected function getPersistentData($key, $default = FALSE) {
		$returnValue = $default;

		if (!in_array($key, self::$kSupportedKeys)) {
			self::errorLog('Unsupported key passed to getPersistentData.');
		} else {
			$session_var_name = $this->constructSessionVariableName($key);
			if (isset($this->_session->$session_var_name)) {
				$returnValue = $this->_session->$session_var_name;
			}
		}

		return $returnValue;
	}

	protected function clearPersistentData($key) {
		if (!in_array($key, self::$kSupportedKeys)) {
			self::errorLog('Unsupported key passed to clearPersistentData.');
		} else {
			$session_var_name = $this->constructSessionVariableName($key);
			unset($this->_session->$session_var_name);
		}
	}

	protected function clearAllPersistentData() {
		foreach (self::$kSupportedKeys as $key) {
			$this->clearPersistentData($key);
		}
		if ($this->sharedSessionID) {
			$this->deleteSharedSessionCookie();
		}
	}

	protected function deleteSharedSessionCookie() {
		$cookie_name = $this->getSharedSessionCookieName();
		unset($_COOKIE[$cookie_name]);
		$base_domain = $this->getBaseDomain();
		setcookie($cookie_name, '', 1, '/', '.'.$base_domain);
	}

	protected function getSharedSessionCookieName() {
		return self::FBSS_COOKIE_NAME . '_' . $this->getAppId();
	}

	protected function constructSessionVariableName($key) {
		$parts = array('fb', $this->getAppId(), $key);
		if ($this->sharedSessionID) {
			array_unshift($parts, $this->sharedSessionID);
		}
		return implode('_', $parts);
	}
}
