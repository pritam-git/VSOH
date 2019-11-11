<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/LogoutController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: LogoutController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * LogoutController
 *
 *
 */
class LogoutController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		if (Zend_Auth::getInstance()->hasIdentity()) {

			 /**
			 * get user identity
			 */
			$brandSession = new Zend_Session_Namespace('brand');
			if (isset($brandSession->id)) {
				unset($brandSession->id);
			}
			Zend_Auth::getInstance()->clearIdentity();
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}
	}
}