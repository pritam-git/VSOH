<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Acl/Resource.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Resource.php 567 2018-06-06 13:42:29Z nm $
 */

/**
 *
 *
 * L8M_Acl_Resource
 *
 *
 */
class L8M_Acl_Resource extends Zend_Acl_Resource
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	const PARAM_NAME_LOGIN = 'login';
	const PARAM_NAME_PASSWORD = 'password';

	const ROLE_GUEST_ID = 1;
	const ROLE_GUEST_SHORT = 'guest';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array with authentication data.
	 *
	 * @var Zend_Acl
	 */
	protected static $_acl = NULL;

	/**
	 * A name to be used for the Zend_Session_Namespace needed by this plugin
	 *
	 * @var string
	 * @todo consider name
	 */
	protected static $_sessionNamespace = 'L8M_Controller_Plugin_AuthControlled';

	/**
	 *
	 *
	 * Class Constructors
	 *
	 *
	 */

	/**
	 * Constructs Zend_Acl_Resource instance.
	 *
	 * @param  string $module
	 * @param  string $controller
	 * @param  string $action
	 * @return void
	 */
	public function __construct($module = NULL, $controller = NULL, $action = NULL)
	{
		$this->_resourceId = self::getResourceName($module, $controller, $action);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns resource name for the specified module, controller and action.
	 *
	 * @param  string $module
	 * @param  string $controller
	 * @param  string $action
	 * @return string
	 */
	public static function getResourceName($module = NULL, $controller = NULL, $action = NULL)
	{
		return $module . ($controller ? '.' . $controller . ($action ? '.' . $action : ''): '');
	}

	/**
	 * Returns action name from resource
	 *
	 * @param  string $resource
	 * @return string
	 */
	public static function getActionNameFromResource($resource = NULL)
	{
		$returnValue = NULL;

		$resourceArray = explode('.', $resource);
		if (count($resourceArray) == 3) {
			$returnValue = $resourceArray[2];
		}
		return $returnValue;
	}

	/**
	 * Returns controller name from resource
	 *
	 * @param  string $resource
	 * @return string
	 */
	public static function getControllerNameFromResource($resource = NULL)
	{
		$returnValue = NULL;

		$resourceArray = explode('.', $resource);
		if (count($resourceArray) == 3) {
			$returnValue = $resourceArray[1];
		}
		return $returnValue;
	}

	/**
	 * Returns module name from resource
	 *
	 * @param  string $resource
	 * @return string
	 */
	public static function getModuleNameFromResource($resource = NULL)
	{
		$returnValue = NULL;

		$resourceArray = explode('.', $resource);
		if (count($resourceArray) == 3) {
			$returnValue = $resourceArray[0];
		}
		return $returnValue;
	}

	/**
	 * Returns resource name for the specified module, controller and action.
	 *
	 * @param  string $module
	 * @param  string $controller
	 * @param  string $action
	 * @return string
	 */
	public static function getResourceNameMedia($resource = NULL, $id = NULL)
	{
		return $resource . $id;
	}

	/**
	 * Returns a boolean whether the specified module, controller and action are existing.
	 *
	 * @param  string $module
	 * @param  string $controller
	 * @param  string $action
	 * @return Default_Model_Action
	 */
	public static function existsInDatabaseAndReturn($module = NULL, $controller = NULL, $action = NULL)
	{
		$returnValue = FALSE;

		$resourceId =  $module . ($controller ? '.' . $controller . ($action ? '.' . $action : ''): '');

		$actionModel = FALSE;
		$cache = L8M_Cache::getCache('Default_Model_Action');

		if ($cache) {
			$actionModel = $cache->load(L8M_Cache::getCacheId('resourceLike', $resourceId));
		}

		if ($actionModel === FALSE) {
			$actionModel = Doctrine_Query::create()
				->from('Default_Model_Action a')
				->addWhere('a.resource LIKE ? ', array($resourceId . '%'))
				->limit(1)
				->execute()
				->getFirst()
			;

			if ($cache) {
				$cache->save($actionModel, L8M_Cache::getCacheId('resourceLike', $resourceId));
			}
		}

		if ($actionModel) {
			$returnValue = $actionModel;
		}

		return $returnValue;
	}

	/**
	 * Returns a boolean whether the specified module, controller and action are existing.
	 *
	 * @param  string $module
	 * @param  string $controller
	 * @param  string $action
	 * @return boolean
	 */
	public static function existsInDatabase($module = NULL, $controller = NULL, $action = NULL)
	{
		$returnValue = FALSE;
		$actionModel = L8M_Acl_Resource::existsInDatabaseAndReturn($module, $controller, $action);

		if ($actionModel) {
			$returnValue = TRUE;
			$actionModel->free(TRUE);
		}

		return $returnValue;
	}

	/**
	 * Returns a boolean whether the specified resource is existing.
	 *
	 * @param  string $resource
	 * @param  string $controller
	 * @param  string $action
	 * @return boolean
	 */
	public static function existsInDatabaseByResource($resource = NULL, $controller = NULL, $action = NULL, $lang = NULL)
	{
		$returnValue = FALSE;

		if ($resource) {
			if ($controller) {
				$resource .= '.' . $controller;
				if ($action) {
					$resource .= '.' . $action;
				}
			}

			$resourceParts = explode('.', $resource);
			if (count($resourceParts) == 3) {
				$returnValue = self::existsInDatabase($resourceParts[0], $resourceParts[1], $resourceParts[2]);

				if (!$returnValue) {
					if (!$lang ||
						!in_array($lang, L8M_Locale::getSupported())) {

						$lang = L8M_Locale::getLang();
					}

					$resourceTranslatorModel = Doctrine_Query::create()
						->from('Default_Model_ResourceTranslator m')
						->leftJoin('m.Translation mt')
						->addWhere('mt.lang = ? AND mt.uresource = ? ', array($lang, $resource))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($resourceTranslatorModel) {
						$resourceParts = explode('.', $resourceTranslatorModel->resource);
						$resourceTranslatorModel->free(TRUE);

						if (count($resourceParts) == 3) {
							$returnValue = self::existsInDatabase($resourceParts[0], $resourceParts[1], $resourceParts[2]);
						}
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Returns acl.
	 *
	 * @return Zend_Acl
	 */
	public function getAcl()
	{
		if (self::$_acl === NULL) {
			$session = new Zend_Session_Namespace(self::$_sessionNamespace);
			if (!isset($session->acl)) {

				/**
				 * load acl as Zend_Acl and try to load some more from database
				 */
				$session->acl = new Zend_Acl();
				$session->acl = $this->addRole($session->acl);
			}
			self::$_acl = $session->acl;
		}
		return self::$_acl;
	}

	/**
	 * check action with acl
	 *
	 * @param $actionModel Default_Model_Action
	 * @return boolean
	 */
	public static function checkAction($actionModel)
	{
		if (L8M_Doctrine::isEnabled() == FALSE ||
			!class_exists('Default_Model_Base_Role', TRUE) ||
			!class_exists('Default_Model_Action', TRUE)) {

			return FALSE;
		}

		if (!($actionModel instanceof Default_Model_Action)) {
			throw new L8M_Exception('Action must be type of Action.');
		}

		/**
		 * default return value not allowed
		 */
		$returnValue = self::checkResource($actionModel->resource, $actionModel->role_id);

		return $returnValue;
	}

	/**
	 * check resource with acl
	 *
	 * @param $resource String
	 * @param $resourceRoleID String|Integer
	 * @return boolean
	 */
	public static function checkResource($resource, $resourceRoleID)
	{
		if (L8M_Doctrine::isEnabled() == FALSE ||
			!class_exists('Default_Model_Base_Role', TRUE)) {

			return FALSE;
		}

		if (!is_numeric($resourceRoleID) &&
			is_string($resourceRoleID)) {

			$resourceRoleID = L8M_Acl_Role::getRoleIdByShort($resourceRoleID);
		} else
		if (!is_numeric($resourceRoleID)) {
			return FALSE;
		}

		/**
		 * load acl
		 */
		$acl = L8M_Acl::getAcl();

		/**
		 * load roleID
		 */
		$roleID = L8M_Acl_Role::getEntityRoleID();

		/**
		 * check resource
		 */
		if (!$acl->has($resource) &&
			$acl->hasRole($resourceRoleID)) {

			$acl->addResource(new Zend_Acl_Resource($resource));
			$acl->allow($resourceRoleID, $resource);
		}

		/**
		 * default return value not allowed
		 */
		$returnValue = FALSE;

		/**
		 * is allowed if same role
		 */
		if ($acl->has($resource) &&
			$acl->isAllowed($roleID, $resource)) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}
}