<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Memory.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Memory.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Memory
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Memory extends ZFDebug_Controller_Plugin_Debug_Plugin_Memory
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Gets menu tab for the Debugbar
	 *
	 * @return string
	 */
	public function getTab()
	{
		$memoryUsage = 'n/a';
		if (function_exists('memory_get_peak_usage')) {
			$memoryUsage = memory_get_peak_usage();
		}
		return L8M_Library::getBytes($memoryUsage);
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{

		ob_start();

?>
<h4>Memory</h4>
<?php

		L8M_Library::dataShow(array(
			'Limit'=>ini_get('memory_limit'),
			'Usage'=>L8M_Library::getBytes(memory_get_peak_usage()),
			'Controller'=>L8M_Library::getBytes($this->_memory['postDispatch'] - $this->_memory['preDispatch']),
		));

		if (isset($this->_memory['user']) && count($this->_memory['user'])) {
			L8M_Library::dataShow($this->_memory['user']);
		}

		return ob_get_clean();;
	}

}