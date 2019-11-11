<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/IndexController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: IndexController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Admin_IndexController
 *
 *
 */
class Admin_IndexController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_IndexController.
	 *
	 * @return void
	 */
	public function init ()
	{
		$this->_helper->layout()->headline = $this->view->translate('Administration');

		parent::init();
	}

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
		$session = new Zend_Session_Namespace(get_class($this));
		if (!$session->backendInfoLoaded ||
			$session->backendInfoTimestamp + 86400 < time()) {

			$session->backendInfoLoaded = TRUE;
			$session->backendInfoTimestamp = time();
			$backendInfoUrl = 'http://www.l8m.com/api/backend-information' .
				'/project-short/' . rawurlencode(L8M_Config::getOption('l8m.project.short')) .
				'/system-type/' . rawurlencode(L8M_Config::getOption('l8m.system.type')) .
				'/version/' . rawurlencode(L8M_Config::getOption('l8m.system.version')) .
				'/server-name/' . rawurlencode($_SERVER['SERVER_NAME']) .
				'/api-key/' . rawurlencode(L8M_Config::getOption('l8m.project.api_key'))
			;
			$backendInfoContent = @file_get_contents($backendInfoUrl);
			if ($backendInfoContent) {
				$session->backendInfoContent = $backendInfoContent;
			}
		}
		$this->view->backendInfo = $session->backendInfoContent;

	}

}