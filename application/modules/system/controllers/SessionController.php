<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/SetupController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SessionController.php 261 2015-03-05 16:50:13Z nm $
 */

/**
 *
 *
 * System_SessionController
 *
 *
 */
class System_SessionController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Clear action. Clears sessions that can be cleared.
	 *
	 * @return void
	 */
	public function clearAction()
	{

		/**
		 * sessionCount
		 */
		$sessionCount = L8M_Session::clearAll();

		/**
		 * no ajax
		 */
		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->view->sessionClearResult = $sessionCount;
		} else

		/**
		 * ajax
		 */
		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->view->ajaxResponse = $sessionCount;
		}
	}

}