<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Registry.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Registry.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Registry
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Registry extends ZFDebug_Controller_Plugin_Debug_Plugin_Registry
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
		ob_start();

?>
<h4>Registry</h4>
<?php
		$this->_registry->ksort();

		L8M_Library::dataShow($this->_registry->getArrayCopy());

		return ob_get_clean();
	}

}