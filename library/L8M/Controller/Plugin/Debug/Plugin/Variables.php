<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Variables.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Variables.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Variables
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Variables extends ZFDebug_Controller_Plugin_Debug_Plugin_Variables
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{

		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewVars = $viewRenderer->view->getVars();

		ob_start();

?>
<h4>Variables</h4>
<?php
		L8M_Library::dataShow(array(
			'$_POST'=>$this->_request->getPost(),
			'$_GET'=>$this->_request->getQuery(),
			'$_COOKIE'=>$this->_request->getCookie(),
		));

?>
<h4>Request</h4>
<?php
		L8M_Library::dataShow($this->_request->getParams());

		/**
		 * view variables
		 */
		if ($viewVars) {

?>
<h4>View Variables</h4>
<?php
			L8M_Library::dataShow($viewVars);

		}

		return ob_get_clean();
	}

}