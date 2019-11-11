<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Session.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Session.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Session
 *
 *
 */
class L8M_Controller_Plugin_Session extends Zend_Controller_Plugin_Abstract
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

	/**
	 * An array of module, controller and action resources allowed to use session id.
	 *
	 * @var array
	 */
	protected $_allowedSessionIdResources = array();

	/**
	 * Save session name.
	 *
	 * @var unknown_type
	 */
	protected $_sessionName = NULL;

	/**
	 *
	 *
	 * Class Constructors
	 *
	 *
	 */

	/**
	 * Constructs L8M_Controller_Plugin_Session instance.
	 *
	 * @param  Zend_Auth $auth
	 * @return void
	 */
	public function __construct($sessionName = NULL, $allowedSessionIdResources = array())
	{
		/**
		 * check
		 */
		if (!is_string($sessionName) ||
			$sessionName == NULL ||
			trim($sessionName) == '') {

			throw new L8M_Exception('Bootstrap-Name has to be set.');
		}

		if (!is_array($allowedSessionIdResources)) {

			throw new L8M_Exception('Failure concerning allowed Bootstrap-Name ID-Resources.');
		}

		/**
		 * setup the vars
		 */
		$this->_sessionName = $sessionName;
		$this->_allowedSessionIdResources = $allowedSessionIdResources;
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
		if (!Zend_Session::isStarted()) {

			/**
			 * get resource
			 */
			$resource = L8M_Acl_Resource::getResourceName(
				$request->getModuleName(),
				$request->getControllerName(),
				$request->getActionName()
			);

			$sessionID = NULL;

			if (in_array($resource, $this->_allowedSessionIdResources)) {
				$sessionID = $request->getParam($this->_sessionName);
			}

			if ($sessionID) {
				Zend_Session::setId($sessionID);
			}

//			if (!Zend_Session::isStarted()) {
				Zend_Session::start();
//			}

			/**
			 * session fixation
			 */
			$defaultNamespace = new Zend_Session_Namespace();

			if (!isset($defaultNamespace->initialized)) {
				Zend_Session::regenerateId();
				$defaultNamespace->initialized = true;
			}
		}
	}
}