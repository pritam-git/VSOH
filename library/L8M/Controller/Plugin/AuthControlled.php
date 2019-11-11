<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/AuthControlled.php
 * @author     Norbert Marks <nm@l8m.com>
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AuthControlled.php 567 2018-06-06 13:42:29Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_AuthControlled
 *
 *
 */
class L8M_Controller_Plugin_AuthControlled extends Zend_Controller_Plugin_Abstract
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
	 * current identity is hacked.
	 *
	 * @var array
	 */
	protected $_hackingAttempt = array(
		'module'=>'default',
		'controller'=>'error',
		'action'=>'error-hacking-attempt',
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
	 * Allow resource or part of resource
	 *
	 * @var array
	 */
	protected $_allowedResources = array(
		'default.activation',
	);

	/**
	 * Check only Remote Address (IP) concerning hacking
	 *
	 * @var array
	 */
	protected $_checkOnlyRemoteAddress = array(
		'system.media.upload',
	);

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
	 * Constructs L8M_Controller_Plugin_AuthControlled instance.
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
	 * Called before an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * This callback allows for proxy or filter behavior.  By altering the
	 * request and resetting its dispatched flag (via
	 * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
	 * the current action may be skipped.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{

		if (L8M_Doctrine::isEnabled() == FALSE ||
			!class_exists('Default_Model_Base_Role', TRUE) ||
			!class_exists('Default_Model_Base_Action', TRUE)) {

			return;
		}

		/**
		 * get resource
		 */
		$resource = L8M_Acl_Resource::getResourceName(
			$request->getModuleName(),
			$request->getControllerName(),
			$request->getActionName()
		);

		/**
		 * load acl
		 */
		$acl = $this->getAcl();

		/**
		 * retrieve role from Zend_Auth instance
		 */
		if ($this->_auth->hasIdentity() &&
			(NULL != $identity = $this->_auth->getIdentity()) &&
			$identity instanceof Default_Model_Entity) {

			/**
			 * get user identity
			 */
			$identity = $this->_auth->getIdentity();
			$entitySqlModel = L8M_Sql::factory('Default_Model_Entity')
				->select('disabled, updated_at')
				->addWhere('id = ? ', array($identity->id))
				->limit(1)
				->execute()
				->getFirst()
			;
			if ($entitySqlModel instanceof L8M_Sql_Object) {
				if (strtotime($entitySqlModel->updated_at) > strtotime($identity->updated_at)) {
					if ($entitySqlModel->disabled) {
						$this->_redirectToLogout();
					} else {
						$entityModel = Doctrine_Query::create()
							->from('Default_Model_Entity m')
							->addWhere('m.id = ? ', array($identity->id))
							->limit(1)
							->execute()
							->getFirst()
						;
						$adapter = new L8M_Auth_Adapter_EntityReLogin($entityModel);
						$authResult = Zend_Auth::getInstance()->authenticate($adapter);
						if ($authResult->isValid()) {
							$this->_auth = Zend_Auth::getInstance();
							$identity = $entityModel;
						} else {
							$this->_redirectToLogout();
						}
					}
				}
			} else {
				$this->_redirectToLogout();
			}

			/**
			 * browser - session - identity check
			 */
			$browserIdentityCheck = TRUE;

			/**
			 * standard check remote address
			 */
			$userServerVars = array();
			if (L8M_Config::getOption('authentication.session.recheck')) {
				$userServerVars = array(
					'REMOTE_ADDR',
				);
			}

			/**
			 * do we have to check other parts to?
			 */
			if (Zend_Registry::get('environment') !== L8M_Environment::ENVIRONMENT_DEVELOPMENT &&
				!in_array($resource, $this->_checkOnlyRemoteAddress)) {

				/**
				 * update some user-server-variables
				 */
				if (L8M_Config::getOption('authentication.session.recheck')) {
					$userServerVars = array(
						'HTTP_USER_AGENT',
						'HTTP_ACCEPT_CHARSET',
						'HTTP_ACCEPT_LANGUAGE',
						'HTTP_ACCEPT_ENCODING',
					);
				}
			}

			/**
			 * start checking
			 */
			foreach ($userServerVars as $userServerVar) {
				if (isset($_SERVER[$userServerVar])) {
					$serverVarValue = $_SERVER[$userServerVar];
					if ($userServerVar == 'HTTP_USER_AGENT') {
						$serverVarValues = explode(' ', $_SERVER[$userServerVar]);
						if (isset($serverVarValues[0])) {
							$serverVarValue = $serverVarValues[0];
						}
					}
				} else {
					$serverVarValue = NULL;
				}
				$identityUserServerVar = $identity->get('login_with_' . strtolower($userServerVar));
				if ((isset($_SERVER[$userServerVar]) && $identityUserServerVar !== $serverVarValue) ||
					(!isset($_SERVER[$userServerVar]) && $identityUserServerVar !== NULL)) {

					if ($userServerVar == 'HTTP_ACCEPT_LANGUAGE' &&
						isset($_SERVER[$userServerVar]) &&
						$identityUserServerVar) {

						$langIdentityArray = explode('-', $identityUserServerVar);
						$langServerArray = explode('-', $_SERVER[$userServerVar]);

						if (strtolower(array_shift($langIdentityArray)) !== strtolower(array_shift($langServerArray))) {
							/**
							 * user is not the right one
							 */
							$browserIdentityCheck = FALSE;
						}
					} else
					if ($identityUserServerVar !== NULL) {
						/**
						 * user is not the right one
						 */
						$browserIdentityCheck = FALSE;
					}
				}
			}

			/**
			 * is user the right one?
			 */
			if ($browserIdentityCheck) {

				/**
				 * yes - get him the right role
				 */
				$role = strtolower($identity->Role->id);
			} else {

				/**
				 * no, prevent hacking and get him the guest-role and exit (return)
				 */
				$role = $this->getGuestRoleId();
				$request->setModuleName($this->_hackingAttempt['module']);
				$request->setControllerName($this->_hackingAttempt['controller']);
				$request->setActionName($this->_hackingAttempt['action']);
				return;
			}
		} else {
			$role = $this->getGuestRoleId();
		}

		/**
		 * retrieve layout
		 */
		$layout = Zend_Layout::getMvcInstance();

		/**
		 * checking for exception and setup process
		 */
		if ($this->getResponse()->isException() &&
			isset($layout->getView()->layout()->systemSetupProcessConfirmed) &&
			$layout->getView()->layout()->systemSetupProcessConfirmed &&
			isset($layout->getView()->layout()->setupWithoutDatabase) &&
			$layout->getView()->layout()->setupWithoutDatabase) {

			$this->_response->clearBody();
			return;
		}

		/**
		 * get roles
		 * $acl->getRegisteredRoles() is deprecated and will be removed soon
		 */
		$roles = $acl->getRoles();

		/**
		 * check resource translation
		 */
		if (class_exists('Default_Model_ResourceTranslator', TRUE)) {
			try {
				$resourceTranslatorModel = Doctrine_Query::create()
					->from('Default_Model_ResourceTranslator m')
					->leftJoin('m.Translation mt')
					->where('mt.uresource = ? AND mt.lang = ? ', array($resource, L8M_Locale::getLang()))
					->limit(1)
					->execute()
					->getFirst()
				;
				if ($resourceTranslatorModel) {
					$tmpResource = $resourceTranslatorModel->resource;
					$tmpResourceArray = explode('.', $tmpResource);

					if (count($tmpResourceArray) == 3) {
						$resource = $tmpResource;
						$request->setModuleName($tmpResourceArray[0]);
						$request->setControllerName($tmpResourceArray[1]);
						$request->setActionName($tmpResourceArray[2]);
					}
				} else {
					$resourceTranslatorModel = Doctrine_Query::create()
						->from('Default_Model_ResourceTranslator m')
						->where('m.resource = ? ', array($resource))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($resourceTranslatorModel) {
						$tmpResource = $resourceTranslatorModel->Translation[L8M_Locale::getLang()]['uresource'];
						$tmpResourceArray = explode('.', $tmpResource);

						if ($tmpResource != $resource &&
							count($tmpResourceArray) == 3) {

							/**
							 * fall-back url
							 */
							$url = Zend_Layout::getMvcInstance()->getView()->url(array('module'=>$tmpResourceArray[0], 'controller'=>$tmpResourceArray[1], 'action'=>$tmpResourceArray[2]));

							/**
							 * redirect using Zend_Register, that is called again in init-function of L8M_Controller_Action
							 */
							Zend_Registry::set('L8M_Application_Resource_Router_Redirect', $url);
						}
					}
				}
				if ($resourceTranslatorModel) {
					$resourceTranslatorModel->free(TRUE);
				}
			} catch (Doctrine_Connection_Exception $exception) {
				/**
				 * @todo maybe do something
				 */
			}
		}

		/**
		 * check resource
		 */
		if (!$acl->has($resource)) {

			/**
			 * check resource and action
			 */
			if (class_exists('Default_Model_Action', TRUE)) {
				try {

					/**
					 * action
					 */
					$action = FALSE;
					$cache = L8M_Cache::getCache('Default_Model_Action');

					if ($cache) {
						$action = $cache->load(L8M_Cache::getCacheId('resource', $resource));
					}

					if ($action === FALSE) {

						/**
						 * let's execute query
						 * @var Doctrine_Query
						 */
						$action = Doctrine_Query::create()
							->from('Default_Model_Action a')
							->where('a.resource = ?', array($resource))
							->execute()
							->getFirst()
						;

						if ($cache) {
							$cache->save($action, L8M_Cache::getCacheId('resource', $resource));
						}
					}

					if ($action) {
						$acl->addResource(new Zend_Acl_Resource($resource));
						if ($action->is_allowed) {

							try {
								/**
								 * add allow-rule for the action-role-id
								 */
								$acl->allow($action->role_id, $resource);
							} catch (Exception $e) {
								$resourceArray = explode('.', $resource);
								if (count($resourceArray) == 3 &&
									$resourceArray[0] == 'default' &&
									$resourceArray[1] == 'error' &&
									count($acl->getRoles()) === 0 &&
									$this->getGuestRoleId() == $action->role_id) {

									L8M_Session::clearAll(TRUE);

									/**
									 * redirect using Zend_Register, that is called again in init-function of L8M_Controller_Action
									 */
									Zend_Registry::set('L8M_Application_Resource_Router_Redirect', $_SERVER['REQUEST_URI']);
								} else {
									throw $e;
								}
							}
						}
					} else {

						/**
						 * do we have a hard coded allowed resource
						 */
						if (in_array($resource, $this->_allowedResources)) {

							/**
							 * add allow-rule for the action-role-id
							 */
							$acl->allow($action['role_id'], $resource);
						} else {

							$partResource = L8M_Acl_Resource::getResourceName(
								$request->getModuleName(),
								$request->getControllerName()
							);
							if (in_array($partResource, $this->_allowedResources)) {

								/**
								 * add allow-rule for the action-role-id
								 */
								$acl->allow($action['role_id'], $resource);
							} else {

								$partResource = L8M_Acl_Resource::getResourceName(
									$request->getModuleName()
								);
								if (in_array($partResource, $this->_allowedResources)) {

									/**
									 * add allow-rule for the action-role-id
									 */
									$acl->allow($action['role_id'], $resource);
								}
							}
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}
		}

		/**
		 * check if is exception and remember resource
		 */
		if ($this->getResponse()->isException()) {
			$layout->getView()->layout()->isException = TRUE;
			$layout->getView()->layout()->rememberIsActionMethod = $layout->getView()->layout()->isActionMethod;
			$layout->getView()->layout()->rememberCalledForResource = $layout->getView()->layout()->calledForResource;
			$layout->getView()->layout()->rememberCalledForModuleName = $layout->getView()->layout()->calledForModuleName;
			$layout->getView()->layout()->rememberCalledForControllerName = $layout->getView()->layout()->calledForControllerName;
			$layout->getView()->layout()->rememberCalledForActionName = $layout->getView()->layout()->calledForActionName;

			$rememberOldIsActionMethod = $layout->getView()->layout()->rememberOldIsActionMethod;
			$rememberOldIsActionMethod[] = $layout->getView()->layout()->isActionMethod;
			$layout->getView()->layout()->rememberOldIsActionMethod = $rememberOldIsActionMethod;

			$rememberOldCalledForResource = $layout->getView()->layout()->rememberOldCalledForResource;
			$rememberOldCalledForResource[] = $layout->getView()->layout()->calledForResource;
			$layout->getView()->layout()->rememberOldCalledForResource = $rememberOldCalledForResource;

			$rememberOldCalledForModuleName = $layout->getView()->layout()->rememberOldCalledForModuleName;
			$rememberOldCalledForModuleName[] = $layout->getView()->layout()->calledForModuleName;
			$layout->getView()->layout()->rememberOldCalledForModuleName = $rememberOldCalledForModuleName;

			$rememberOldCalledForControllerName = $layout->getView()->layout()->rememberOldCalledForControllerName;
			$rememberOldCalledForControllerName[] = $layout->getView()->layout()->calledForControllerName;
			$layout->getView()->layout()->rememberOldCalledForControllerName = $rememberOldCalledForControllerName;

			$rememberOldCalledForActionName = $layout->getView()->layout()->rememberOldCalledForActionName;
			$rememberOldCalledForActionName[] = $layout->getView()->layout()->calledForActionName;
			$layout->getView()->layout()->rememberOldCalledForActionName = $rememberOldCalledForActionName;
		} else {
			$layout->getView()->layout()->isException = FALSE;
			$layout->getView()->layout()->rememberIsActionMethod = NULL;
			$layout->getView()->layout()->rememberCalledForResource = NULL;
			$layout->getView()->layout()->rememberCalledForModuleName = NULL;
			$layout->getView()->layout()->rememberCalledForControllerName = NULL;
			$layout->getView()->layout()->rememberCalledForActionName = NULL;
			$layout->getView()->layout()->rememberOldIsActionMethod = array();
			$layout->getView()->layout()->rememberOldCalledForResource = array();
			$layout->getView()->layout()->rememberOldCalledForModuleName = array();
			$layout->getView()->layout()->rememberOldCalledForControllerName = array();
			$layout->getView()->layout()->rememberOldCalledForActionName = array();
		}

		/**
		 * set isActionMethod
		 */
		$layout->getView()->layout()->isActionMethod = FALSE;
		$layout->getView()->layout()->calledForResource = $resource;
		$layout->getView()->layout()->calledForModuleName = $request->getModuleName();
		$layout->getView()->layout()->calledForControllerName = $request->getControllerName();
		$layout->getView()->layout()->calledForActionName = $request->getActionName();

		/**
		 * resource does not exist - maybe action param?
		 */
		if (!$acl->has($resource)) {

			/**
			 * check param to action class
			 */
			if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Param.php') &&
				class_exists('PRJ_Controller_Action_Param')) {

				$actionParam = new PRJ_Controller_Action_Param();
				$paramAction = $request->getActionName();
				$paramController = $request->getControllerName();
				$paramModule = $request->getModuleName();
				if ($actionParam instanceof L8M_Controller_Action_Param &&
					$actionParam->checkController($paramAction, $paramController, $paramModule, L8M_Locale::getLang())) {

					try {
						/**
						 * let's execute query
						 * @var Doctrine_Query
						 */

						/**
						 * action
						 */
						$roleOfActionParam = $actionParam->getRole($paramAction, $paramController, $paramModule, L8M_Locale::getLang());
						$roleForParamAction = FALSE;

						$cache = L8M_Cache::getCache('Default_Model_Role');
						if ($cache) {
							$roleForParamAction = $cache->load(L8M_Cache::getCacheId('roleOfActionParam', $roleOfActionParam));
						}
						if ($roleForParamAction === FALSE) {
							$roleForParamAction = Doctrine_Query::create()
								->from('Default_Model_Role r')
								->where('r.short = ? ', array($roleOfActionParam))
								->execute()
								->getFirst()
							;

							if ($cache &&
								$roleForParamAction) {

								$cache->save($roleForParamAction, L8M_Cache::getCacheId('roleOfActionParam', $roleOfActionParam));
							}
						}

						if ($roleForParamAction) {

							/**
							 * add allow-rule for the action-role-id
							 */
							$acl->addResource(new Zend_Acl_Resource($resource));
							$acl->allow($roleForParamAction->id, $resource);

							/**
							 * rewrite action and return
							 */
							$acl->addActionParam(
								$paramAction,
								$paramController,
								$paramModule,
								$actionParam->getController($paramAction, $paramController, $paramModule, L8M_Locale::getLang()),
								$actionParam->getAction($paramAction, $paramController, $paramModule, L8M_Locale::getLang()),
								$actionParam->getParam($paramAction, $paramController, $paramModule, L8M_Locale::getLang())
							);
						}
					} catch (Doctrine_Connection_Exception $exception) {
						/**
						 * @todo maybe do something
						 */
					}
				}
			}
		}

		/**
		 * no access granted
		 */
		$accessGranted = FALSE;

		/**
		 * resource does not exist
		 */
		if (!$acl->has($resource)) {

			/**
			 * check whether we are in development or not (try to prevent an everytime setup)
			 */
			if (Zend_Registry::get('environment') === L8M_Environment::ENVIRONMENT_DEVELOPMENT) {

				/**
				 * do nothing else and return try to reach the resource
				 */
				$accessGranted = TRUE;
			} else {

				/**
				 * HTTP 404
				 */
				$request->setModuleName($this->_noAction['module']);
				$request->setControllerName($this->_noAction['controller']);
				$request->setActionName($this->_noAction['action']);
			}
		} else

		/**
		 * resource does exist.
		 * does the role exists?
		 */
		if ($acl->hasRole($role)) {

			/**
			 * is resource allowed
			 */
			if (!$acl->isAllowed($role, $resource)) {
				/**
				 * resource is not allowed
				 */
				$request->setModuleName($this->_notAllowed['module']);
				$request->setControllerName($this->_notAllowed['controller']);
				$request->setActionName($this->_notAllowed['action']);
			} else {
				$accessGranted = TRUE;
			}
		} else {
			/**
			 * role does not exist - so it's not allowed
			 */
			$request->setModuleName($this->_notAllowed['module']);
			$request->setControllerName($this->_notAllowed['controller']);
			$request->setActionName($this->_notAllowed['action']);
		}

		$aclCalledFor = new L8M_Acl_CalledFor();
		$aclCalledFor->setResource(L8M_Acl_Resource::getResourceName($request->getModuleName(), $request->getControllerName(), $request->getActionName()));
		$aclCalledFor->setModule($request->getModuleName());
		$aclCalledFor->setController($request->getControllerName());
		$aclCalledFor->setAction($request->getActionName());

		/**
		 * check for action methode
		 */
		$isActionMethod = FALSE;
		if ($accessGranted) {
			if (class_exists('Default_Model_Action', TRUE)) {
				try {
					/**
					 * action method
					 */

					$actionMethodModel = FALSE;
					$cache = L8M_Cache::getCache('Default_Model_Action');

					if ($cache) {
						$actionMethodModel = $cache->load(L8M_Cache::getCacheId('resource', $resource));
					}

					if ($actionMethodModel === FALSE) {

						/**
						 * let's execute query
						 * @var Doctrine_Query
						 */
						$actionMethodModel = Doctrine_Query::create()
							->from('Default_Model_Action a')
							->where('a.resource = ?', array($resource))
							->execute()
							->getFirst()
						;

						if ($cache &&
							$actionMethodModel) {

							$cache->save($actionMethodModel, L8M_Cache::getCacheId('resource', $resource));
						}
					}

					if ($actionMethodModel &&
						$actionMethodModel->is_action_method) {

						$request->setModuleName('default');
						$request->setControllerName('index');
						$request->setActionName('empty');
						$request->setParam('formerResource', $resource);
						$aclCalledFor->setIsActionMethod(TRUE);

						/**
						 * set isActionMethod
						 */
						$layout->getView()->layout()->isActionMethod = TRUE;
						$isActionMethod = TRUE;
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}

			/**
			 * checking for action param
			 */
			if (!$isActionMethod) {

				/**
				 * check param to action class
				 */
				$paramAction = $request->getActionName();
				$paramController = $request->getControllerName();
				$paramModule = $request->getModuleName();
				if ($acl instanceof L8M_Controller_Plugin_AuthControlled_Acl &&
					$acl->checkActionParam($paramModule, $paramController)) {

					if (!isset($actionParam) ||
						!($actionParam instanceof PRJ_Controller_Action_Param)) {

						/**
						 * check param to action class
						 */
						if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Param.php') &&
							class_exists('PRJ_Controller_Action_Param')) {

							$actionParam = new PRJ_Controller_Action_Param();
						}
					}

					if (isset($actionParam) &&
						$actionParam instanceof PRJ_Controller_Action_Param &&
						!$actionParam->checkControllerForUrlAuthControlled($paramAction, $paramController, $paramModule, L8M_Locale::getLang())) {

						$actionParamNewController = $acl->getActionParamNewController($paramModule, $paramController);
						$actionParamNewAction = $acl->getActionParamNewAction($paramModule, $paramController);
						$request->setControllerName($actionParamNewController);
						$request->setActionName($actionParamNewAction);
						$request->setParam($acl->getActionParamNewParam($paramModule, $paramController), $paramAction);

						$aclCalledFor->setResource(L8M_Acl_Resource::getResourceName($paramModule, $actionParamNewController, $actionParamNewAction));
						$aclCalledFor->setModule($paramModule);
						$aclCalledFor->setController($actionParamNewController);
						$aclCalledFor->setAction($actionParamNewAction);
					}
				}
			}

			/**
			 * if access granted try loading content from cache
			 */
			$possibleContent = L8M_Content::getContentFromCache($this->getRequest()->isXmlHttpRequest());
			$layout->getView()->layout()->isContentFromCache = FALSE;
			if ($possibleContent !== FALSE &&
				$possibleContent !== NULL) {

				$layout->getView()->layout()->isContentFromCache = TRUE;
				$this->getResponse()->setBody($possibleContent);
				$request->setModuleName('default');
				$request->setControllerName('index');
				$request->setActionName('empty');
				$request->setParam('formerResource', $resource);
			}
		}
	}

	/**
	 *
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Attempts to login with the specified login and password, and validates
	 * against the specified table.
	 *
	 * @param string $login
	 * @param string $password
	 * @param double $longitude
	 * @param double $latitude
	 * @param double $accuracy
	 * @param double $altitude
	 * @param double $altitudeAccuracy
	 * @param double $heading
	 * @param double $speed
	 * @return Zend_Auth_Result
	 */
	public static function login($login = NULL, $password = NULL, $latitude = NULL, $longitude = NULL, $accuracy = NULL, $altitude = NULL, $altitudeAccuracy = NULL, $heading = NULL, $speed = NULL)
	{
		$adapter = new L8M_Auth_Adapter_Doctrine($login, $password, $latitude, $longitude, $accuracy, $altitude, $altitudeAccuracy, $heading, $speed);
		return Zend_Auth::getInstance()->authenticate($adapter);
	}

	/**
	 * Attempts to login with the specified login, and validates
	 * against the specified table.
	 *
	 * @param string $login
	 * @param double $longitude
	 * @param double $latitude
	 * @param double $accuracy
	 * @param double $altitude
	 * @param double $altitudeAccuracy
	 * @param double $heading
	 * @param double $speed
	 * @return Zend_Auth_Result
	 */
	public static function loginWithoutPassword($login = NULL, $latitude = NULL, $longitude = NULL, $accuracy = NULL, $altitude = NULL, $altitudeAccuracy = NULL, $heading = NULL, $speed = NULL)
	{
		$adapter = new L8M_Auth_Adapter_Doctrine($login, NULL, $latitude, $longitude, $accuracy, $altitude, $altitudeAccuracy, $heading, $speed, FALSE);
		return Zend_Auth::getInstance()->authenticate($adapter);
	}

	/**
	 * Attempts to logout currently logged in entity.
	 *
	 * @return bool
	 */
	public static function logout()
	{
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$user = Zend_Auth::getInstance()->getIdentity();
			if ($user instanceof Default_Model_Entity) {
				$user->last_logout = date('Y-m-d H:i:s');
				$user->save();
			}
			Zend_Auth::getInstance()->clearIdentity();
		}
		Zend_Session::namespaceUnset(self::$_sessionNamespace);
		return TRUE;
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
				$session->acl = new L8M_Controller_Plugin_AuthControlled_Acl();
				$session->acl = $this->addRole($session->acl);
			}
			self::$_acl = $session->acl;
		}
		return self::$_acl;
	}

	/**
	 * Returns guest-role-id.
	 *
	 * @return integer
	 */
	public function getGuestRoleId()
	{
		/**
		 * get guest role id, but first:
		 * do we have a database connection?
		 */
		if (class_exists('Default_Model_Base_Role', TRUE)) {
			if (L8M_Doctrine_Database::databaseExists() &&
				L8M_Doctrine_Table::tableExists('role')) {

				$guestRole = FALSE;

				$cache = L8M_Cache::getCache('Default_Model_Role');
				if ($cache) {
					$guestRole = $cache->load(L8M_Cache::getCacheId('guestRole', 'guest'));
				}
				if ($guestRole === FALSE) {

					/**
					 * let's execute query
					 * @var Doctrine_Query
					 */
					$guestRoleSqlCollection = L8M_Sql::factory('Default_Model_Role')
						->select('id')
						->addWhere('disabled = ?', array(0))
						->addWhere('short = ?', array('guest'))
						->limit(1)
						->execute()
					;

					if ($guestRoleSqlCollection instanceof L8M_Sql_ObjectCollection &&
						$guestRoleSqlCollection->count() == 1) {

						$guestRole = $guestRoleSqlCollection->getFirst();

						if ($cache) {
							$cache->save($guestRole, L8M_Cache::getCacheId('guestRole', 'guest'));
						}
					}
				}
				if ($guestRole instanceof L8M_Sql_Object &&
					isset($guestRole['id']) &&
					$guestRole['id'] !== NULL) {

					/**
					 * database guest role id
					 */
					$role = $guestRole['id'];
				} else {

					/**
					 * @todo add an exception, 'cause there should be one guest-role!
					 */
					throw new L8M_Exception('This can never ever happen: If you have a database, the system will retrieve a role.');
				}
			} else {
				$role = L8M_Acl::ROLE_GUEST_ID;
			}
		} else {
			$role = L8M_Acl::ROLE_GUEST_ID;
		}

		return $role;
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
				//$resultAcl->addRole(new L8M_Acl_Role(L8M_Acl::ROLE_GUEST_ID, L8M_Acl::ROLE_GUEST_SHORT), NULL);
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
			//$resultAcl->addRole(new L8M_Acl_Role(L8M_Acl::ROLE_GUEST_ID, L8M_Acl::ROLE_GUEST_SHORT), NULL);
		}
		return $resultAcl;
	}

	protected function _redirectToLogout()
	{
		$session = new Zend_Session_Namespace(self::$_sessionNamespace);

		if (!isset($session->isInRedirect)) {
			$session->isInRedirect = TRUE;
		} else
		if (!$session->isInRedirect) {
			$session->isInRedirect = TRUE;
		} else
		if ($session->isInRedirect) {
			$session->isInRedirect = FALSE;
		}

		if ($session->isInRedirect) {
			$redirectorHelper = new Zend_Controller_Action_Helper_Redirector();
			$redirectorHelper->gotoUrlAndExit(L8M_Library::getSchemeAndHttpHost() . '/logout');
		}
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
				$sqlWhere = 'role.role_id is NULL';
				$parentRoleID = array();
				$executeParams = array();

				/**
				 * let's execute query
				 * @var Doctrine_Query
				 */
				$roles = L8M_Sql::factory()
					->execute('SELECT role.id, role.short, role.role_id AS parent_id FROM role WHERE role.disabled = ? AND ' . $sqlWhere . ' LIMIT 1', array_merge(array(0), $executeParams))
				;
			} else {
				$sqlWhere = 'role.role_id = ?';
				$executeParams = array($parentRoleID);

				/**
				 * let's execute query
				 * @var Doctrine_Query
				 */
				$roles = L8M_Sql::factory()
					->execute('SELECT role.id, role.short, rp.id AS parent_id, rp.short AS parent_short FROM role, role AS rp WHERE role.disabled = ? AND role.role_id = rp.id AND ' . $sqlWhere, array_merge(array(0), $executeParams))
				;
			}

			if ($roles instanceof L8M_Sql_ObjectCollection &&
				$cache) {

				$cache->save($roles, L8M_Cache::getCacheId('addRole', $parentRoleID));
			}
		}

		/**
		 * do we have a result
		 */
		if ($roles instanceof L8M_Sql_ObjectCollection &&
			$roles->count() == 1) {

			$currentRole = $roles->getFirst();

			/**
			 * do we have no role added already?
			 */
			if (count($parentsParentRoleIDs) == 0) {

				/**
				 * check yourself before recursion
				 */
				if (is_array($parentRoleID) &&
					$currentRole->short != 'admin') {

					/**
					 * throw exception
					 */
					throw new L8M_Exception('Failure in AuthControled detected. There seems to be no admin-role.');
				}

				/**
				 * start recursion
				 */
				$resultArray = $this->_addRecursiveRole($resultAcl, $currentRole->id, $parentsParentRoleIDs);
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
					$currentRole->short != 'guest') {

					/**
					 * check for ACL splited in trees
					 */
					if (is_array($parentsParentRoleIDs) &&
						count($parentsParentRoleIDs) == 0) {

						$guestRole = L8M_Sql::factory('Default_Model_Role')
							->select('id, short')
							->addWhere('disabled = ?', array(0))
							->addWhere('short = ?', array('guest'))
							->limit(1)
							->execute()
							->getFirst()
						;
						if ($guestRole instanceof L8M_Sql_Object) {

							$parentRoleIDs[] = $guestRole->id;
							if (!$resultAcl->hasRole(new L8M_Acl_Role($guestRole->id, $guestRole->short))) {
								$resultAcl->addRole(new L8M_Acl_Role($guestRole->id, $guestRole->short), array());
							}
						} else {

							/**
							 * throw exception
							 */
							throw new L8M_Exception('Failure in AuthControled detected. There seems to be no guest-role.');
						}
					}

					/**
					 * throw exception
					 */
// 					throw new L8M_Exception('Failure in AuthControled detected. There seems to be no guest-role.');
				}

				/**
				 * add role with its parent, if parent roles are all existing
				 */
				if (!$resultAcl->hasRole(new L8M_Acl_Role($currentRole->id, $currentRole->short))) {
					$resultAcl->addRole(new L8M_Acl_Role($currentRole->id, $currentRole->short), $parentsParentRoleIDs);
				}

				$parentsParentRoleIDs[] = $currentRole->id;

				$returnValue = array(
					'resultAcl'=>$resultAcl,
					'parentRoleIDs'=>array_unique($parentsParentRoleIDs),
				);
			}
		} else

		/**
		 * do we have more then one subrole?
		 */
		if ($roles instanceof L8M_Sql_ObjectCollection &&
			$roles->count() > 1) {

			$tempParentsParentRoleIDs = $parentsParentRoleIDs;

			foreach ($roles->toArray() as $key => $roleArray) {

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

						$guestRole = L8M_Sql::factory('Default_Model_Role')
							->select('id, short')
							->addWhere('disabled = ?', array(0))
							->addWhere('short = ?', array('guest'))
							->limit(1)
							->execute()
							->getFirst()
						;
						if ($guestRole instanceof L8M_Sql_Object) {

							$parentRoleIDs[] = $guestRole->id;
							if (!$resultAcl->hasRole(new L8M_Acl_Role($guestRole->id, $guestRole->short))) {
								$resultAcl->addRole(new L8M_Acl_Role($guestRole->id, $guestRole->short), array());
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
}