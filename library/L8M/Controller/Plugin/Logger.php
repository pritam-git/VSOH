<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Logger.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Logger.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Logger
 *
 *
 */
class L8M_Controller_Plugin_Logger extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Controller_Plugin_Logger instance.
	 *
	 * @return void
	 */
	public function __construct ()
	{

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Called on route startup
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function routeStartup (Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on route shutdown
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on dispatch loop startup
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on pre dispatch
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		/**
		 * logData
		 */
		$logCreated		= date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
		$logRemoteIP	   = $_SERVER['REMOTE_ADDR'];
		$logRequestURI	 = $_SERVER['REQUEST_URI'];
		$logRedirectStatus = array_key_exists('REDIRECT_STATUS',$_SERVER) ? $_SERVER['REDIRECT_STATUS'] : NULL;
		$logGet			= count($_GET)>0 ? serialize($_GET) : NULL;
		$logPost		   = count($_POST)>0 ? serialize($_POST) : NULL;
		$logModule		 = $request->getModuleName();
		$logController	 = $request->getControllerName();
		$logAction		 = $request->getActionName();
		$logUser		   = NULL;
		/**
		 * @todo an error occurs as userID does not exist in identity storage
		 */
		if (Zend_Auth::getInstance()->hasIdentity() &&
			Zend_Auth::getInstance()->getIdentity()) {
			$logUser = Zend_Auth::getInstance()->getIdentity()->userID;
		}
		/**
		 * log
		 */
		$logTableInstance = new L8M_Model_Db_Table_Log();
		$logTableInstance->insert(array('logCreated'=>$logCreated,
										'logRemoteIP'=>$logRemoteIP,
										'logRequestURI'=>$logRequestURI,
										'logRedirectStatus'=>$logRedirectStatus,
										'logGet'=>$logGet,
										'logPost'=>$logPost,
										'logModule'=>$logModule,
										'logController'=>$logController,
										'logAction'=>$logAction,
										'logUser'=>$logUser));
	}

	/**
	 * Called on post dispatch
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{

	}

	/**
	 * Called on dispatch loop shutdown
	 *
	 */
	public function dispatchLoopShutdown()
	{

	}

}