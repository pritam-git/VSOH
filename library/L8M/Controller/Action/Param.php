<?php
/**
 * L8M
 *
 *
 * @filesource library/PRJ/Controller/Action/Param.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Param.php 261 2015-03-05 16:50:13Z nm $
 */


/**
 *
 *
 * L8M_Controller_Action_Param
 *
 *
 */
class L8M_Controller_Action_Param extends L8M_Controller_Action_Param_Abstract
{
	private $_resourceParts = array();
	private static $_resourcePartTransaltion = array();
	private static $_resourceTransaltion = array();
	private static $_resourceActionTransaltion = array();

	/**
	 * set resource parts
	 *
	 * @param array $resourceParts
	 */
	public function setResourceParts($resourceParts)
	{
		$this->_resourceParts = $resourceParts;
	}

	/**
	 * check Controller
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return boolean
	 */
	public function checkController($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$returnValue = FALSE;
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts) &&
			isset($this->_resourceParts[$resource]['action'])) {

			$returnValue = TRUE;
		}
		return $returnValue;
	}

	/**
	 * check Controller for URL Creator
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return boolean
	 */
	public function checkControllerForUrlCreator($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$returnValue = FALSE;
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts) &&
			isset($this->_resourceParts[$resource]['action']) &&
			$this->_resourceParts[$resource]['action'] == $action &&
			L8M_Acl_Resource::existsInDatabaseByResource($resource, $action, NULL, $lang)) {

			$returnValue = TRUE;
		}
		return $returnValue;
	}

	/**
	 * check Controller for Url AuthControlled
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return boolean
	 */
	public function checkControllerForUrlAuthControlled($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$returnValue = FALSE;
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts) &&
			isset($this->_resourceParts[$resource]['action']) &&
			$this->_resourceParts[$resource]['action'] != $action &&
			L8M_Acl_Resource::existsInDatabaseByResource($resource, $action, NULL, $lang)) {

			$returnValue = TRUE;
		}
		return $returnValue;
	}

	/**
	 * retrieve action
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	public function getAction($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts)) {
			return $this->_resourceParts[$resource]['action'];
		}
		return NULL;
	}

	/**
	 * retrieve controller
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	public function getController($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		$resourceArray = explode('.', $resource);
		if (count($resourceArray) == 2) {
			return $resourceArray[1];
		}
		return NULL;
	}

	/**
	 * retrieve param
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	public function getParam($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts)) {
			return $this->_resourceParts[$resource]['param'];
		}
		return NULL;
	}

	/**
	 * retrieve role
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	public function getRole($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$resource = $this->_getRealResourcePart($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts)) {
			return $this->_resourceParts[$resource]['role'];
		}
		return NULL;
	}

	/**
	 * Retrieve real Resource Part.
	 *
	 * @param String $module
	 * @param String $controller
	 * @param String $action
	 * @param String $lang
	 * @return string
	 */
	private function _getRealResourcePart($module = 'default', $controller = NULL, $action = NULL, $lang = NULL)
	{
		$returnValue = NULL;

		if ($lang) {
			$oldResourcePart = L8M_Acl_Resource::getResourceName($module, $controller);
			$oldResource = L8M_Acl_Resource::getResourceName($module, $controller, $action);

			if (array_key_exists($lang, self::$_resourcePartTransaltion) &&
				array_key_exists($oldResourcePart, self::$_resourcePartTransaltion[$lang])) {

				$returnValue = self::$_resourcePartTransaltion[$lang][$oldResourcePart];
			} else {

				$fromCache = $this->_getFromCache($oldResource, $lang);
				if ($fromCache !== FALSE &&
					is_array($fromCache) &&
					array_key_exists('resourcePart', $fromCache)) {

					if (isset(self::$_resourcePartTransaltion[$lang])) {
						self::$_resourcePartTransaltion[$lang][$oldResourcePart] = $fromCache['resourcePart'];
					} else {
						self::$_resourcePartTransaltion[$lang] = array(
							$oldResourcePart=>$fromCache['resourcePart']
						);
					}
					$returnValue = self::$_resourcePartTransaltion[$lang][$oldResourcePart];
				} else {
					if (class_exists('Default_Model_ResourceTranslator', TRUE)) {
						$resource = L8M_Acl_Resource::getResourceName($module, $controller, '%');
						try {
							/**
							 * do we have a translation for possible original resource
							 */
							$resourceTranslatorSqlCollection = L8M_Translate_Resource::getResourceByUresourceWithLang($resource, $lang);
							if ($resourceTranslatorSqlCollection->count() == 1) {
								$tmpResource = $resourceTranslatorSqlCollection->getFirst()->resource;

								$tmpResourceArray = explode('.', $tmpResource);

								if (count($tmpResourceArray) == 3) {

									$module = $tmpResourceArray[0];
									$controller = $tmpResourceArray[1];

									$tryResource = L8M_Acl_Resource::getResourceName($module, $controller);
									if (array_key_exists($tryResource, $this->_resourceParts) &&
										array_key_exists('action', $this->_resourceParts[$tryResource])) {

										if (isset(self::$_resourcePartTransaltion[$lang])) {
											self::$_resourcePartTransaltion[$lang][$oldResourcePart] = L8M_Acl_Resource::getResourceName($module, $controller);
										} else {
											self::$_resourcePartTransaltion[$lang] = array(
												$oldResourcePart=>L8M_Acl_Resource::getResourceName($module, $controller),
											);
										}
									}
								}
							} else {
								if (isset(self::$_resourcePartTransaltion[$lang])) {
									self::$_resourcePartTransaltion[$lang][$oldResourcePart] = L8M_Acl_Resource::getResourceName($module, $controller);
								} else {
									self::$_resourcePartTransaltion[$lang] = array(
										$oldResourcePart=>L8M_Acl_Resource::getResourceName($module, $controller),
									);
								}
							}
							if (array_key_exists($lang, self::$_resourcePartTransaltion) &&
								array_key_exists($oldResourcePart, self::$_resourcePartTransaltion[$lang])) {

								$this->_setToCache($oldResource, $lang, array(
									'resourcePart'=>self::$_resourcePartTransaltion[$lang][$oldResourcePart],
								));
							}
						} catch (Doctrine_Connection_Exception $exception) {
						/**
						 * @todo maybe do something
						 */

						}
					}
				}
			}
		}

		if (!$returnValue) {
			$returnValue = L8M_Acl_Resource::getResourceName($module, $controller);
		}

		return $returnValue;
	}

	/**
	 * Retrieve menu array from cache
	 *
	 * @param $oldResource
	 * @param $lang
	 * @return array
	 */
	protected function _getFromCache($oldResource, $lang)
	{
		$returnValue = FALSE;

		$cache = L8M_Cache::getCache('L8M_Controller_Action_Param');
		if ($cache) {
			$returnValue = $cache->load('resource_' . $lang . '_' . str_replace('.', '_', str_replace('-', '_dash_', $oldResource)));
		}

		return $returnValue;
	}

	/**
	 * Set menu array to cache
	 *
	 * @param $oldResource
	 * @param $lang
	 * @param $resourceArray
	 */
	protected function _setToCache($oldResource, $lang, $resourceArray)
	{
		$cache = L8M_Cache::getCache('L8M_Controller_Action_Param');
		if ($cache) {
			$cache->save($resourceArray, 'resource_' . $lang . '_' . str_replace('.', '_', str_replace('-', '_dash_', $oldResource)));
		}
	}
}