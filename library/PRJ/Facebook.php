<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/Facebook.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Facebook.php 311 2015-04-01 12:41:55Z nm $
 */

/**
 *
 *
 * PRJ_Facebook
 *
 *
 */
class PRJ_Facebook
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Stores instnce of Facebook API
	 *
	 * @var PRJ_Facebook_Abstract
	 */
	private static $_facebookInstance = NULL;

	/**
	 * Stores all user inforamtion loaded via API
	 *
	 * @var array
	 */
	private static $_userInformation = array();

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * Returns Facebook API Class.
	 *
	 * @return  PRJ_Facebook_Abstract
	 */
	public static function factory()
	{
		if (!self::$_facebookInstance) {

			self::$_facebookInstance = new PRJ_Facebook_Abstract(array(
				'appId'=>L8M_Config::getOption('facebook.app_id'),
				'secret'=>L8M_Config::getOption('facebook.secret'),
			));
		}

		return self::$_facebookInstance;
	}

	/**
	 * Retruns LoginUrl
	 * Have a look at: https://developers.facebook.com/docs/reference/php/facebook-getLoginUrl/
	 *
	 * @param $scope
	 * @param $redirectUrl
	 * @param $display
	 * @return string
	 */
	public static function getLoginUrl($scope = FALSE, $redirectUrl = FALSE, $display = 'page')
	{
		if ($redirectUrl === FALSE) {
			throw new L8M_Exception('RedirectUrl has to be set as a valid url.');
		}

		$facebook = PRJ_Facebook::factory();

		$params = array(
			'scope'=>$scope,
			'redirect_uri'=>$redirectUrl,
			'display'=>$display,
		);

		return str_replace('&', '&amp;', $facebook->getLoginUrl($params));
	}

	/**
	 * Retruns user information
	 *
	 * @param $user
	 * @return array
	 */
	public static function getUser($user = 'me')
	{
		if (!is_string($user) &&
			strlen($user) == 0) {

			throw new L8M_Exception('Username has to be a String with length greater then 0.');
		}

		if (!array_key_exists($user, self::$_userInformation)) {
			self::$_userInformation[$user] = PRJ_Facebook::factory()->api('/' . $user);
		}
		return self::$_userInformation[$user];
	}

	/**
	 * Retruns user "me" information
	 *
	 * @return array
	 */
	public static function getMe()
	{
		return self::getUser('me');
	}

	/**
	 * Retruns if register
	 *
	 * @return FALSE or TRUE
	 */
	public static function isRegistered()
	{

		$isRegistered = FALSE;

		$userProfile = PRJ_Facebook::getMe();

		$entityModel = Doctrine_Query::create()
			->from('Default_Model_Entity ent')
			->where('ent.email = ?', $userProfile['email'])
			->limit(1)
			->execute()
			->getFirst()
		;

		if ($entityModel) {
			$isRegistered = TRUE;
		}

		return $isRegistered;
	}

	/**
	 * Retruns login
	 *
	 * @return FALSE or TRUE
	 */
	public static function login()
	{
		$authResult = FALSE;

		$userProfile = PRJ_Facebook::getMe();

		$entityModel = Doctrine_Query::create()
			->from('Default_Model_Entity ent')
			->where('ent.email = ?', $userProfile['email'])
			->limit(1)
			->execute()
			->getFirst()
		;

		$adapter = new PRJ_Auth_Adapter_Facebook($entityModel);
		$authResult = Zend_Auth::getInstance()->authenticate($adapter);

		return $authResult;

	}

	public static function getSalutation($user = NULL)
	{
		if (!$user) {
			$userProfile = PRJ_Facebook::getMe();
		} else {
			$userProfile = PRJ_Facebook::getUser($user);
		}
		$salutation = array('female'=>1, 'male'=>2);

		return $salutation[$userProfile['gender']];
	}
}