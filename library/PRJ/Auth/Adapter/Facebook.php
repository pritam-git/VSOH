<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/Auth/Adapter/Facebook.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version	$Id: Facebook.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_Auth_Adapter_Facebook
 *
 *
 */
class PRJ_Auth_Adapter_Facebook implements Zend_Auth_Adapter_Interface
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing the login with wich an authentication attempt is
	 * made.
	 *
	 * @var string
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
	 * @param  string $login
	 * @param  string $password
	 * @return void
	 */
	public function __construct($entityModel = NULL)
	{
		/**
		 * Facebook enabled?
		 */
		if (L8M_Config::getOption('facebook.enabled') == FALSE) {
			throw new L8M_Auth_Adapter_Doctrine_Exception('Facebook is disabled.');
		}
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
			 * user is logged in, update user
			 */
			$entity->last_login = date('Y-m-d H:i:s');

			/**
			 * update some user-server-variables
			 */
			$userServerVars = array(
				'HTTP_USER_AGENT',
				'HTTP_ACCEPT_CHARSET',
				'HTTP_ACCEPT_LANGUAGE',
				'HTTP_ACCEPT_ENCODING',
				'REMOTE_ADDR',
			);
			foreach ($userServerVars as $userServerVar) {
				if (isset($_SERVER[$userServerVar])) {
					$serverVarValue = $_SERVER[$userServerVar];
					if ($userServerVar == 'HTTP_USER_AGENT') {
						$serverVarValues = explode(' ', $_SERVER[$userServerVar]);
						if (isset($serverVarValues[0])) {
							$serverVarValue = $serverVarValues[0];
						}
					}
					$entity->merge(array('login_with_' . strtolower($userServerVar) => $serverVarValue));
				} else {
					$entity->merge(array('login_with_' . strtolower($userServerVar) => NULL));
				}
			}

			$entity->save();

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
