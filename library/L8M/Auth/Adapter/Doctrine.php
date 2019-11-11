<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Auth/Adapter/Doctrine.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version	$Id: Doctrine.php 508 2016-07-21 12:36:13Z nm $
 */

/**
 *
 *
 * L8M_Auth_Adapter_Doctrine
 *
 *
 */
class L8M_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
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
	protected $_login = NULL;

	/**
	 * A string representing the password with wich an authentication attempt is
	 * made.
	 *
	 * @var string
	 */
	protected $_password = NULL;

	/**
	 * geo datas
	 */
	protected $_longitude = NULL;
	protected $_latitude = NULL;
	protected $_accuracy = NULL;
	protected $_altitude = NULL;
	protected $_altitudeAccuracy = NULL;
	protected $_heading = NULL;
	protected $_speed = NULL;

	/**
	 * Use the password for login process.
	 *
	 * @var boolean
	 */
	protected $_usePassword = TRUE;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Auth_Adapter_Doctrine instance
	 *
	 * @param  string $login
	 * @param  string $password
	 * @return void
	 */
	public function __construct($login = NULL, $password = NULL, $latitude = NULL, $longitude = NULL, $accuracy = NULL, $altitude = NULL, $altitudeAccuracy = NULL, $heading = NULL, $speed = NULL, $usePassword = TRUE)
	{
		/**
		 * Doctrine enabled?
		 */
		if (L8M_Doctrine::isEnabled() == FALSE) {
			throw new L8M_Auth_Adapter_Doctrine_Exception('Doctrine is disabled.');
		}
		$this->_login = $login;
		$this->_password = $password;

		if (!is_float($latitude) ||
			!is_integer($latitude)) {

			$latitude = NULL;
		}
		$this->_latitude = $latitude;

		if (!is_float($longitude) ||
			!is_integer($longitude)) {

			$longitude = NULL;
		}
		$this->_longitude = $longitude;

		if (!is_float($accuracy) ||
			!is_integer($accuracy)) {

			$accuracy = NULL;
		}
		$this->_accuracy = $accuracy;

		if (!is_float($altitude) ||
			!is_integer($altitude)) {

			$altitude = NULL;
		}
		$this->_altitude = $altitude;

		if (!is_float($altitudeAccuracy) ||
			!is_integer($altitudeAccuracy)) {

			$altitudeAccuracy = NULL;
		}
		$this->_altitudeAccuracy = $altitudeAccuracy;

		if (!is_float($heading) ||
			!is_integer($heading)) {

			$heading = NULL;
		}
		$this->_heading = $heading;

		if (!is_float($speed) ||
			!is_integer($speed)) {

			$speed = NULL;
		}
		$this->_speed = $speed;

		$this->_usePassword = $usePassword;
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
		/**
		 * classes exist?
		 */
		if (class_exists('Default_Model_Base_Entity', TRUE) &&
			class_exists('Default_Model_Entity', TRUE)) {

			try {

				L8M_Log::info('Attempting to authenticate user with login "' . $this->_login .'" and password "' . $this->_password . '".');
				/* @var $entityModel Default_Model_Entity*/
				$entityModel = Doctrine_Query::create()
					->from('Default_Model_Entity e')
					->where('e.login = ? ', array($this->_login))
					->limit(1)
					->execute()
					->getFirst()
				;

				/**
				 * identity not found
				 */
				if ($entityModel === FALSE) {

					$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, NULL, array('An account with the specified login could not be found.'));
					L8M_Log::info('Authenticate: Login not found');
				} else

				/**
				 * we have a Default_Model_Entity
				 */
				if ($entityModel instanceof Default_Model_Entity) {

					$wrongLonginResource = FALSE;
					$resourceName = $viewFromMVC = Zend_Layout::getMvcInstance()->getView()->layout()->calledForResource;

					$defaultAction = Doctrine_Query::create()
						->from('Default_Model_Action a')
						->where('a.resource = ?', $entityModel->Role->default_action_resource)
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($defaultAction) {
						$loginAction = Doctrine_Query::create()
							->from('Default_Model_Action a')
							->where('a.resource = ?', $defaultAction->Controller->Module->name . '.login.index')
							->limit(1)
							->execute()
							->getFirst()
						;
						if ($loginAction) {
							$wrongLonginResource = TRUE;
							if ($resourceName == $loginAction->resource) {
								$wrongLonginResource = FALSE;
							}
						}
					}

					/**
					 * wrong login resource
					 */
					if ($wrongLonginResource) {
						$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $entityModel->login, array('The account associated with the specified login can not be accessed that way.'));
						L8M_Log::info('Authenticate: Login is not allowed this way');
					} else

					/**
					 * disabled
					 */
					if ($entityModel->disabled != FALSE) {

						$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $entityModel->login, array('The account associated with the specified login has been disabled.'));
						L8M_Log::info('Authenticate: Login disabled');
					} else

					/**
					 * not activated
					 */
					if ($entityModel->activated_at == NULL ||
						$entityModel->activated_at > date('Y-m-d H:i:s')) {

						$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $entityModel->login, array('The account associated with the specified login has not been activated.'));
						L8M_Log::info('Authenticate: Login is not activated');
					} else {

						if ($this->_usePassword) {
							/**
							 * check for hash
							 */
							$couldLogin = L8M_Library::checkPasswordHash($entityModel->password, $this->_password);
						} else {
							$couldLogin = TRUE;
						}

						/**
						 * last check
						 */
						if (!$couldLogin) {
							$entityModel->password_attempt = $entityModel->password_attempt + 1;
							$entityModel->save();

							if ($entityModel->password_attempt > 5) {
								$entityModel->disableBecauseOfSecurityReasons();
							}

							$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $entityModel->login, array('The specified password does not seem to be correct.'));
							L8M_Log::info('Authenticate: Password invalid');
						} else {

							/**
							 * user is logged in, update user
							 */
							$entityModel->last_login = date('Y-m-d H:i:s');

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
									$entityModel->merge(array('login_with_' . strtolower($userServerVar) => $serverVarValue));
								} else {
									$entityModel->merge(array('login_with_' . strtolower($userServerVar) => NULL));
								}
							}

							$entityModel->password_attempt = 0;
							$entityModel->save();

							/**
							 * user found and credential valid and enabled
							 */
							$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $entityModel, array('User successfully logged in.'));
							L8M_Log::info('Authenticate: Login successful.');
						}
					}
					if (isset($authenticationResult) &&
						$authenticationResult instanceof Zend_Auth_Result) {

						$entityModel->logMessage($authenticationResult->getMessages(), $this->_latitude, $this->_longitude, $this->_accuracy, $this->_altitude, $this->_altitudeAccuracy, $this->_heading, $this->_speed);
					}
				}

			} catch(Doctrine_Exception $exception) {
				throw new L8M_Auth_Adapter_Doctrine_Exception($exception->getMessage());
			}
		}

		if (!($authenticationResult instanceof Zend_Auth_Result)) {
			$authenticationResult = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, NULL, array('Login could not be processed because of a technical failure..'));
		}

		/**
		 * return authentication result
		 */
		return $authenticationResult;
	}
}
