<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Auth/Adapter/EntityReLogin.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version	$Id: EntityReLogin.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Auth_Adapter_EntityReLogin
 *
 *
 */
class L8M_Auth_Adapter_EntityReLogin implements Zend_Auth_Adapter_Interface
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The Model.
	 *
	 * @var Default_Model_Entity
	 */
	protected $_entityModel = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs PRJ_Auth_Adapter_Facebook instance
	 *
	 * @param  Default_Model_Entity $entityModel
	 * @return void
	 */
	public function __construct($entityModel = NULL)
	{
		$this->_entityModel = $entityModel;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
	 * attempt an authentication.  Previous to this call, this adapter would have already
	 * been configured with all necessary information to successfully connect to a database
	 * table and attempt to find a record matching the provided identity.
	 *
	 * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
	 * @return Zend_Auth_Result
	 */
	public function authenticate()
	{
		$entity = $this->_entityModel;

		/**
		 * disabled
		 */
		if ($entity instanceof Default_Model_Entity &&
			$entity->disabled != FALSE) {

			$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $entity->login, array('The account associated with the specified login has been disabled.'));
			L8M_Log::info('Authenticate: Login disabled');
		} else

		/**
		 * not activated
		 */
		if ($entity instanceof Default_Model_Entity &&
			($entity->activated_at == NULL ||
			$entity->activated_at > date('Y-m-d H:i:s'))) {

			$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $entity->login, array('The account associated with the specified login has not been activated.'));
			L8M_Log::info('Authenticate: Login is not activated');
		} else {

			/**
			 * user found and credential valid and enabled
			 */
			$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $entity, array('User successfully logged in.'));
			L8M_Log::info('Authenticate: Login successful.');
		}

		/**
		 * return authentication result
		 */
		return $authenticationResult;
	}
}
