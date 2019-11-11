<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Acl/CalledFor.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: CalledFor.php 381 2015-07-08 16:42:13Z nm $
 */

/**
 *
 *
 * L8M_Acl_CalledFor
 *
 *
 */
class L8M_Acl_CalledFor
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
	private static $_resource = NULL;
	private static $_module = NULL;
	private static $_controller = NULL;
	private static $_action = NULL;
	private static $_isActionMethod = FALSE;

	/**
	 *
	 *
	 * Class Constructors
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
	 * Returns called resource.
	 *
	 * @return string
	 */
	public static function resource()
	{
		return self::$_resource;
	}

	/**
	 * Returns called module.
	 *
	 * @return string
	 */
	public static function module()
	{
		$returnValue = self::$_module;

		if (!$returnValue) {
			/**
			 * check redirect to right URL, prevent double URL to same action
			 */
			$requestUri = NULL;
			$front = Zend_Controller_Front::getInstance();
			if ($front) {
				$request = $front->getRequest();
				if ($request) {
					$requestUri = $request->getPathInfo();
				}
			}

			if ($requestUri === NULL &&
				isset($_SERVER['REQUEST_URI'])) {

				$requestUri = $_SERVER['REQUEST_URI'];
			}

			if (is_string($requestUri)) {
				$uriParts = explode('/', ltrim($requestUri, '/\\'));
				if (count($uriParts) > 0) {
					if (in_array($uriParts[0], L8M_Config::getOption('locale.supported')) ||
						in_array($uriParts[0], L8M_Config::getOption('locale.backend.supported'))) {

						unset($uriParts[0]);

					}

					$returnValue = 'default';
					if (count($uriParts) > 0) {
						$secondUriPart = array_shift($uriParts);
						$possibleModules = Zend_Controller_Front::getInstance()->getControllerDirectory();
						if (array_key_exists($secondUriPart, $possibleModules)) {
							$returnValue = $secondUriPart;
						}
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Returns called controller.
	 *
	 * @return string
	 */
	public static function controller()
	{
		return self::$_controller;
	}

	/**
	 * Returns called action.
	 *
	 * @return string
	 */
	public static function action()
	{
		return self::$_action;
	}

	/**
	 * Returns called as action method.
	 *
	 * @return string
	 */
	public static function isActionmethod()
	{
		return self::$_isActionMethod;
	}

	/**
	 * Store called resource.
	 *
	 * @param $resource
	 */
	public function setResource($resource)
	{
		self::$_resource = $resource;
	}

	/**
	 * Store called module.
	 *
	 * @param $module
	 */
	public function setModule($module)
	{
		self::$_module = $module;
	}

	/**
	 * Store called controller.
	 *
	 * @param $controller
	 */
	public function setController($controller)
	{
		self::$_controller = $controller;
	}

	/**
	 * Store called action.
	 *
	 * @param $action
	 */
	public function setAction($action)
	{
		self::$_action = $action;
	}

	/**
	 * Store called as action method.
	 *
	 * @param $action
	 */
	public function setIsActionMethod($isActionMethod)
	{
		self::$_isActionMethod = $isActionMethod;
	}
}