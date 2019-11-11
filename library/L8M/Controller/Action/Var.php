<?php
/**
 * L8M
 *
 *
 * @filesource library/L8M/Controller/Action/Var.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Var.php 560 2018-01-31 17:22:24Z nm $
 */


/**
 *
 *
 * L8M_Controller_Action_Var
 *
 *
 */
class L8M_Controller_Action_Var extends L8M_Controller_Action_Var_Abstract
{

	private $_resourceParts = array();
	private static $_resourceTransaltion = array();

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
	 * @return boolean
	 */
	public function checkController($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		return array_key_exists($this->_getRealResource($module, $controller, $action, $lang), $this->_resourceParts);
	}

	/**
	 * check Controller By Real Resource
	 *
	 * @param String $resource
	 * @return boolean
	 */
	public function checkControllerByRealResource($resource = NULL)
	{
		return array_key_exists($resource, $this->_resourceParts);
	}

	/**
	 * retrieve value
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @return String
	 */
	public function getValue($action = NULL, $controller = NULL, $module = 'default', $lang = NULL, $isNotParamAction = TRUE)
	{
		$returnValue = NULL;

		if ($this->checkController($action, $controller, $module, $lang)) {
			$requesUri = $_SERVER['REQUEST_URI'];
			$urlArray = explode('/', trim($requesUri, '/'));

			if (count($urlArray) >= 2) {
				/**
				 * is key 0 (lang) not one of language-short standards remove it
				 */
				if ($lang == L8M_Locale::getTldDefaultLang() ){

				} else
				if (!L8M_Locale::getTldDefaultLang() &&
					$lang == L8M_Locale::getLang() &&
					$lang == L8M_Locale::getDefault()) {

				} else {
					unset($urlArray[0]);
				}

				/**
				 * remove module
				 */
				if ($module != 'default') {
					unset($urlArray[1]);
				}

				if (count($urlArray) >= 2) {
					/**
					 * set keys new
					 */
					$urlArray = array_merge($urlArray);

					/**
					 * remove controller
					 */
					unset($urlArray[0]);

					/**
					 * set keys new
					 */
					$urlArray = array_merge($urlArray);

					if ($isNotParamAction) {
						if (array_key_exists(1, $urlArray)) {
							$returnValue = urldecode($urlArray[1]);
						}
					} else {
						if (array_key_exists(1, $urlArray)) {
							$returnValue = urldecode($urlArray[1]);
						}
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 * get params behind
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @return String
	 */
	public function getParamsBehind($action = NULL, $controller = NULL, $module = 'default', $lang = NULL, $isNotParamAction = TRUE)
	{
		$returnValue = array();

		if ($this->checkController($action, $controller, $module, $lang)) {
			$requesUri = $_SERVER['REQUEST_URI'];
			$urlArray = explode('/', trim($requesUri, '/'));

			if (count($urlArray) >= 2) {
				/**
				 * is key 0 (lang) not one of language-short standards remove it
				 */
				if ($lang == L8M_Locale::getTldDefaultLang() ){

				} else
				if (!L8M_Locale::getTldDefaultLang() &&
					$lang == L8M_Locale::getLang() &&
					$lang == L8M_Locale::getDefault()) {

				} else {
					unset($urlArray[0]);
				}

				/**
				 * remove module
				 */
				if ($module != 'default') {
					unset($urlArray[1]);
				}

				if (count($urlArray) >= 2) {
					/**
					 * set keys new
					 */
					$urlArray = array_merge($urlArray);

					/**
					 * remove controller
					 */
					unset($urlArray[0]);

					/**
					 * set keys new
					 */
					$urlArray = array_merge($urlArray);

					if ($isNotParamAction) {
						if (array_key_exists(1, $urlArray)) {
							unset($urlArray[0]);
							unset($urlArray[1]);

							/**
							 * set keys new
							 */
							$urlArray = array_merge($urlArray);

							/**
							 * walk through
							 */
							$tempParamName = NULL;
							foreach ($urlArray as $urlItem) {
								if ($tempParamName == NULL) {
									$tempParamName = $urlItem;
									$returnValue[$urlItem] = NULL;
								} else {
									$returnValue[$tempParamName] = $urlItem;
									$tempParamName = NULL;
								}
							}
						}
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 * retrieve param
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @return String
	 */
	public function getParam($action = NULL, $controller = NULL, $module = 'default', $lang = NULL)
	{
		$returnValue = NULL;
		$resource = $this->_getRealResource($module, $controller, $action, $lang);

		if (array_key_exists($resource, $this->_resourceParts) &&
			array_key_exists('param', $this->_resourceParts[$resource])) {

			$returnValue = $this->_resourceParts[$resource]['param'];
		}
		return $returnValue;
	}



	/**
	 * Retrieve real Resource.
	 *
	 * @param String $module
	 * @param String $controller
	 * @param String $action
	 * @param String $lang
	 * @return string
	 */
	private function _getRealResource($module = 'default', $controller = NULL, $action = NULL, $lang = NULL)
	{
		$returnValue = NULL;

		if ($lang) {
			$oldResource = L8M_Acl_Resource::getResourceName($module, $controller, $action);

			if (array_key_exists($lang, self::$_resourceTransaltion) &&
				array_key_exists($oldResource, self::$_resourceTransaltion[$lang])) {

				$returnValue = self::$_resourceTransaltion[$lang][$oldResource];
			} else {

				$fromCache = $this->_getFromCache($oldResource, $lang);
				if ($fromCache !== FALSE &&
					is_array($fromCache) &&
					array_key_exists('resource', $fromCache)) {

					if (isset(self::$_resourceTransaltion[$lang])) {
						self::$_resourceTransaltion[$lang][$oldResource] = $fromCache['resource'];
					} else {
						self::$_resourceTransaltion[$lang] = array(
							$oldResource=>$fromCache['resource']
						);
					}
					$returnValue = self::$_resourceTransaltion[$lang][$oldResource];
				} else {
					if (class_exists('Default_Model_ResourceTranslator', TRUE)) {
						$resource = L8M_Acl_Resource::getResourceName($module, $controller, $action);
						try {
							/**
							 * do we have a translation for possible original resource
							 */
							$resourceTranslatorSqlCollection = L8M_Translate_Resource::getResourceByUresourceWithLang($resource, $lang, FALSE);
							if ($resourceTranslatorSqlCollection->count() == 1) {
								$tmpResource = $resourceTranslatorSqlCollection->getFirst()->resource;
								$tmpResourceArray = explode('.', $tmpResource);

								if (count($tmpResourceArray) == 3) {
									$module = $tmpResourceArray[0];
									$controller = $tmpResourceArray[1];
									$action = $tmpResourceArray[2];
								}
							}

							if (isset(self::$_resourceTransaltion[$lang])) {
								self::$_resourceTransaltion[$lang][$oldResource] = L8M_Acl_Resource::getResourceName($module, $controller, $action);
							} else {
								self::$_resourceTransaltion[$lang] = array(
									$oldResource=>L8M_Acl_Resource::getResourceName($module, $controller, $action),
								);
							}

							if (array_key_exists($lang, self::$_resourceTransaltion) &&
								array_key_exists($oldResource, self::$_resourceTransaltion[$lang])) {

								$this->_setToCache($oldResource, $lang, array(
									'resource'=>self::$_resourceTransaltion[$lang][$oldResource],
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
			$returnValue = L8M_Acl_Resource::getResourceName($module, $controller, $action);
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