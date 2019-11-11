<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Acl/Resource.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Role.php 144 2014-07-24 06:53:01Z nm $
 */

/**
 *
 *
 * L8M_Acl_Resource
 *
 *
 */
class L8M_Acl_Role implements Zend_Acl_Role_Interface
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
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
	 * Unique id of Role
	 *
	 * @var string
	 */
	protected $_roleId;

		/**
	 * A more human readable identifier that is unique, too.
	 *
	 * @var string
	 */
	protected $_roleName = NULL;

	/**
	 *
	 *
	 * Class Constructors
	 *
	 *
	 */

	/**
	 * Sets the Role identifier
	 *
	 * @param  string $id
	 * @return void
	 */
	public function __construct($id = NULL, $name = NULL)
	{
		$this->_roleId = (string) $id;
		$this->_roleName = (string) $name;
	}

	/**
	 * Defined by Zend_Acl_Role_Interface; returns the Role identifier
	 *
	 * @return string
	 */
	public function getRoleId()
	{
		return $this->_roleId;
	}

	/**
	 * Defined by Zend_Acl_Role_Interface; returns the Role identifier
	 *
	 * @return string
	 */
	public function getRoleName()
	{
		return $this->_roleName;
	}

	/**
	 * Returns an array of all child roleIDs
	 *
	 * @return array
	 */
	public function getChildRoleIDs()
	{
		$returnValue = array();

		if (class_exists('Default_Model_Role', TRUE)) {
			try {
				$roleCollection = Doctrine_Query::create()
					->from('Default_Model_Role m')
					->where('m.role_id = ? ', array($this->_roleId))
					->execute()
				;
				foreach ($roleCollection as $roleModel) {
					$returnValue = $this->_retrieveChildRoleId($roleModel->id);
				}
				$returnValue = array_unique($returnValue);

				/**
				 * cause of non ending trees we have to add guestID, if not yet in here
				 */
				$roleGuestModel = Doctrine_Query::create()
					->from('Default_Model_Role m')
					->where('m.short = ? ', array('guest'))
					->limit(1)
					->execute()
					->getFirst()
				;
				if ($roleGuestModel) {
					if (!in_array($roleGuestModel->id, $returnValue) &&
						$this->_roleId != $roleGuestModel->id) {

						$returnValue[] = $roleGuestModel->id;
					}
				}
			} catch (Doctrine_Connection_Exception $exception) {
				/**
				 * @todo maybe do something
				 */
			}
		}

		return $returnValue;
	}

	private function _retrieveChildRoleId($parentRoleId) {
		$returnValue = array();

		$roleCollection = Doctrine_Query::create()
			->from('Default_Model_Role m')
			->where('m.role_id = ? ', array($parentRoleId))
			->execute()
		;

		foreach ($roleCollection as $roleModel) {
			if ($roleModel) {
				$returnValue = array_merge($returnValue, $this->_retrieveChildRoleId($roleModel->id));
			}
		}
		$returnValue[] = $parentRoleId;

		return $returnValue;
	}

	/**
	 * Returns roleID of the Zend_Instance-Entity
	 *
	 * @return Integer
	 */
	public static function getEntityRoleID()
	{

		/**
		 * retrieve role from Zend_Auth instance
		 */
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$entityModel = Zend_Auth::getInstance()->getIdentity();
		} else {
			$entityModel = NULL;
		}

		if ($entityModel instanceof Default_Model_Entity) {
			$roleID = $entityModel->Role->id;
		} else {
			$roleID = self::getRoleIdByShort('guest');
		}

		return $roleID;
	}

	/**
	 * Returns roleID by given short.
	 *
	 * @param String $roleShort
	 * @return Integer
	 */
	public static function getRoleIdByShort($roleShort = 'guest')
	{
		/**
		 * get role id, but first:
		 * do we have a database connection?
		 */
		if (class_exists('Default_Model_Base_Role', TRUE)) {
			try {
				/**
				 * let's execute query
				 * @var Default_Model_Role
				 */
				$roleModel = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->where('r.disabled = ?', FALSE)
					->addWhere('r.short = ?', $roleShort)
					->select('r.id')
					->execute()
					->getFirst()
				;
				if ($roleModel) {

					/**
					 * database guest role id
					 */
					$roleID = $roleModel->id;
				} else {

					/**
					 * @todo add an exception, 'cause there should be one guest-role!
					 */
				}
			} catch (Doctrine_Connection_Exception $exception) {
				$roleID = self::ROLE_GUEST_ID;
			}
		} else {
			$roleID = self::ROLE_GUEST_ID;
		}

		return $roleID;
	}
}