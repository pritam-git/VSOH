<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Acl/Media.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Media.php 289 2015-03-23 15:02:30Z nm $
 */

/**
 *
 *
 * L8M_Acl_Media
 *
 *
 */
class L8M_Acl_Media
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
	 * A Zend_Auth instance
	 *
	 * @var Zend_Auth
	 */
	protected $_auth = NULL;

	/**
	 * An array with authentication data.
	 *
	 * @var Zend_Acl
	 */
	protected static $_acl = NULL;

	/**
	 * A Default_Model_Action instance.
	 *
	 * @var Default_Model_Action
	 */
	protected $_action = NULL;

	/**
	 * An array of module, controller and action parameters to be used when the
	 * current entity is not authenticated yet.
	 *
	 * @var array
	 */
	protected $_notAllowed = array(
		'module'=>'default',
		'controller'=>'error',
		'action'=>'error403',
	);

	/**
	 * An array of module, controller and action parameters to be used when the
	 * current entity is authenticated, but not allowed to request a given
	 * resource, i.e., if it is not on the ACL.
	 *
	 * @var array
	 */
	protected $_noAction = array(
		'module'=>'default',
		'controller'=>'error',
		'action'=>'error404',
	);

	/**
	 * An array of module, controller and action parameters to be used for
	 * forwarding to login.
	 *
	 * @var array
	 */
	protected $_forceLogin = array(
		'module'=>'default',
		'controller'=>'user',
		'action'=>'login',
	);

	/**
	 * A name to be used for the Zend_Session_Namespace needed by this plugin
	 *
	 * @var string
	 * @todo consider name
	 */
	protected static $_sessionNamespace = 'L8M_Acl_Media';

	/**
	 *
	 *
	 * Class Constructors
	 *
	 *
	 */

	/**
	 * Constructs L8M_Acl_Media instance.
	 *
	 * @param  Zend_Auth $auth
	 * @return void
	 */
	public function __construct ($auth = NULL)
	{
		if (!($auth instanceof Zend_Auth)) {
			$auth = Zend_Auth::getInstance();
		}
		$this->_auth = $auth;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	/**
	 * check media acl
	 *
	 * @param Default_Model_Media $mediaModel
	 * @param Default_Model_Entity $entityModel
	 * @throws L8M_Exception
	 * @return boolean
	 */
	public static function checkMedia($mediaModel, $entityModel = NULL)
	{
		if (L8M_Doctrine::isEnabled() == FALSE ||
			!class_exists('Default_Model_Base_Role', TRUE) ||
			!class_exists('Default_Model_Base_Media', TRUE)) {

			return FALSE;
		}

		if (!($mediaModel instanceof Default_Model_Media)) {
			throw new L8M_Exception('Media must be type of Media.');
		}

		/**
		 * get resource
		 */
		$resource = L8M_Acl_Resource::getResourceName(
			'media',
			$mediaModel->id
		);

		/**
		 * default return value not allowed
		 */
		$returnValue = L8M_Acl_Resource::checkResource($resource, $mediaModel->role_id);

		/**
		 * retrieve Entity by Zend_Auth
		 */
		if ($entityModel != NULL &&
			!($entityModel instanceof Default_Model_Entity)) {

			$entityModel = NULL;
		}
		if ($entityModel == NULL &&
			Zend_Auth::getInstance()->hasIdentity()) {

			$entityModel = Zend_Auth::getInstance()->getIdentity();
		} else {
			$entityModel = NULL;
		}

		/**
		 * is allowed if same user (owner)
		 */
		if ($entityModel instanceof Default_Model_Entity &&
			isset($mediaModel['entity_id']) &&
			$entityModel->id === $mediaModel->entity_id) {

			$returnValue = TRUE;
		}

		/**
		 * is temporary access granted?
		 */
		if ($returnValue == FALSE) {
			$session = new Zend_Session_Namespace('L8M_Acl_Media');
			if ($session->grant !== NULL &&
				is_array($session->grant) &&
				array_key_exists($mediaModel->short, $session->grant) &&
				$session->grant[$mediaModel->short] > 0) {

				$session->grant[$mediaModel->short] = $session->grant[$mediaModel->short] - 1;
				$returnValue = TRUE;
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
	 * Returns acl with help from databas or the hard-coded one from L8M_Acl
	 *
	 * @param $resultAcl Zend_Acl
	 * @param $parentRoleID integer
	 * @return Zend_Acl
	 */
	protected function addRole($resultAcl, $parentRoleID = NULL, $parentsParentRoleIDs = array())
	{
		/**
		 * let's start building Acl recursiv, but first:
		 * do we have a database connection?
		 */
		if (class_exists('Default_Model_Base_Role', TRUE)) {
			try {

				$returnValue = $this->_addRecursiveRole($resultAcl, $parentRoleID, $parentsParentRoleIDs);
				if (is_array($returnValue) &&
					array_key_exists('resultAcl', $returnValue)) {

					$resultAcl = $returnValue['resultAcl'];
				} else {
					$resultAcl = new L8M_Acl();
				}

			} catch (Doctrine_Connection_Exception $exception) {

				/**
				 * database connection problem, so retrieve base acl from L8M_Acl
				 */
				$resultAcl = new L8M_Acl();
				/**
				 * this is redundant, cause already initialized
				 * with class L8M_Acl, but maybe there is failure
				 */
				//$resultAcl->addRole(new L8M_Acl_Role(self::ROLE_GUEST_ID, self::ROLE_GUEST_SHORT), NULL);
			}
		} else {

			/**
			 * no database model, so retrieve base acl from L8M_Acl
			 */
			$resultAcl = new L8M_Acl();
			/**
			 * this is redundant, cause already initialized
			 * with class L8M_Acl, but maybe there is failure
			 */
			//$resultAcl->addRole(new L8M_Acl_Role(self::ROLE_GUEST_ID, self::ROLE_GUEST_SHORT), NULL);
		}
		return $resultAcl;
	}

	/**
	 * Returns acl with help from databas or the hard-coded one from L8M_Acl
	 *
	 * @param $resultAcl Zend_Acl
	 * @param $parentRoleID integer
	 * @return array
	 */
	protected function _addRecursiveRole($resultAcl, $parentRoleID = NULL, $parentsParentRoleIDs = array())
	{
		$returnValue = NULL;

		/**
		 * retrieve already added role IDs
		 */
		$roles = FALSE;

		$cache = L8M_Cache::getCache('Default_Model_Role');
		if ($cache) {
			$roles = $cache->load(L8M_Cache::getCacheId('addRole', $parentRoleID));
		}
		if ($roles === FALSE) {

			/**
			 * if $parentRoleID is NULL, we have to search for first role
			 */
			if ($parentRoleID == NULL) {
				$sqlWhere = 'r.role_id is NULL';
				$parentRoleID = array();
			} else {
				$sqlWhere = 'r.role_id = ?';
			}

			/**
			 * let's execute query
			 * @var Doctrine_Query
			 */
			$roles = Doctrine_Query::create()
				->from('Default_Model_Role r')
				->leftJoin('r.Role rp')
				->where('r.disabled = ?', 0)
				->addWhere($sqlWhere, $parentRoleID)
				->select('r.id, r.short, rp.id AS parent_id, rp.short AS parent_short')
				->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
				->execute()
			;

			if ($cache) {
				$cache->save($roles, L8M_Cache::getCacheId('addRole', $parentRoleID));
			}
		}

		/**
		 * do we have a result
		 */
		if (is_array($roles) &&
			count($roles) == 1) {

			/**
			 * do we have no role added already?
			 */
			if (count($parentsParentRoleIDs) == 0) {

				/**
				 * check yourself before recursion
				 */
				if (is_array($parentRoleID) &&
					$roles[0]['short'] != 'admin') {

					/**
					 * throw exception
					 */
					throw new L8M_Exception('Failure in AuthControled detected. There seems to be no admin-role.');
				}

				/**
				 * start recursion
				 */
				$resultArray = $this->_addRecursiveRole($resultAcl, $roles[0]['id'], $parentsParentRoleIDs);
				if (is_array($resultArray) &&
					array_key_exists('resultAcl', $resultArray) &&
					array_key_exists('parentRoleIDs', $resultArray)) {

					$resultAcl = $resultArray['resultAcl'];
					$parentsParentRoleIDs = $resultArray['parentRoleIDs'];
				} else {
					throw new L8M_Exception('Failure in AuthControled detected. There seems to be some information in array missing.');
				}

				/**
				 * check yourself after recursion
				 */
				if (count($parentsParentRoleIDs) == 0 &&
					$roles[0]['short'] != 'guest') {

					/**
					 * throw exception
					 */
					throw new L8M_Exception('Failure in AuthControled detected. There seems to be no guest-role.');
				}

				/**
				 * add role with its parent, if parent roles are all existing
				 */
				if (!$resultAcl->hasRole(new L8M_Acl_Role($roles[0]['id'], $roles[0]['short']))) {
					$resultAcl->addRole(new L8M_Acl_Role($roles[0]['id'], $roles[0]['short']), $parentsParentRoleIDs);
				}

				$parentsParentRoleIDs[] = $roles[0]['id'];

				$returnValue = array(
					'resultAcl'=>$resultAcl,
					'parentRoleIDs'=>array_unique($parentsParentRoleIDs),
				);
			}
		} else

		/**
		 * do we have more then one subrole?
		 */
		if (is_array($roles) &&
			count($roles) > 1) {

			$tempParentsParentRoleIDs = $parentsParentRoleIDs;

			foreach ($roles as $key => $roleArray) {

				/**
				 * start recursion
				 */
				$resultArray = $this->_addRecursiveRole($resultAcl, $roleArray['id'], $parentsParentRoleIDs);
				if (is_array($resultArray) &&
					array_key_exists('resultAcl', $resultArray) &&
					array_key_exists('parentRoleIDs', $resultArray)) {

					$resultAcl = $resultArray['resultAcl'];
					$parentRoleIDs = $resultArray['parentRoleIDs'];

					/**
					 * check for ACL splited in trees
					 */
					if (is_array($parentRoleIDs) &&
						count($parentRoleIDs) == 0) {

						$guestRole = Doctrine_Query::create()
							->from('Default_Model_Role r')
							->leftJoin('r.Role rp')
							->where('r.disabled = ?', 0)
							->addWhere('r.short = ? ', array('guest'))
							->select('r.id, r.short, rp.id AS parent_id, rp.short AS parent_short')
							->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
							->limit(1)
							->execute()
						;
						if (is_array($guestRole) &&
							count($guestRole) == 1) {

							$parentRoleIDs[] = $guestRole[0]['id'];
							if (!$resultAcl->hasRole(new L8M_Acl_Role($guestRole[0]['id'], $guestRole[0]['short']))) {
								$resultAcl->addRole(new L8M_Acl_Role($guestRole[0]['id'], $guestRole[0]['short']), array());
							}
						} else {

							/**
							 * throw exception
							 */
							throw new L8M_Exception('Failure in AuthControled detected. There seems to be no guest-role.');
						}
					}
				} else {
					throw new L8M_Exception('Failure in AuthControled detected. There seems to be some information in array missing.');
				}

				/**
				 * add role with its parent, if parent roles are all existing
				 */
				$resultAcl->addRole(new L8M_Acl_Role($roleArray['id'], $roleArray['short']), $parentRoleIDs);

				$tempParentsParentRoleIDs = array_merge($tempParentsParentRoleIDs, $parentRoleIDs);
				$tempParentsParentRoleIDs[] = $roleArray['id'];
			}

			$returnValue = array(
				'resultAcl'=>$resultAcl,
				'parentRoleIDs'=>array_unique($tempParentsParentRoleIDs),
			);
		} else

		/**
		 * do we have no role added already?
		 */
		if (count($parentsParentRoleIDs) == 0) {

			$returnValue = array(
				'resultAcl'=>$resultAcl,
				'parentRoleIDs'=>$parentsParentRoleIDs,
			);
		} else

		/**
		 * something went wrong
		 */
		{

			/**
			 * throw exception
			 */
			throw new L8M_Exception('Failure in AuthControled detected.');
		}

		return $returnValue;
	}

	public static function grantTemporaryAccess($mediaModel = NULL, $counter = 1)
	{
		if ($mediaModel instanceof Default_model_Media &&
			is_int($counter)) {

			$session = new Zend_Session_Namespace('L8M_Acl_Media');
			if ($session->grant == NULL) {
				$session->grant = array();
			}
			if (array_key_exists($mediaModel->short, $session->grant)) {
				$session->grant[$mediaModel->short] = $session->grant[$mediaModel->short] + $counter;
			} else {
				$session->grant[$mediaModel->short] = $counter;
			}
		}
	}
}