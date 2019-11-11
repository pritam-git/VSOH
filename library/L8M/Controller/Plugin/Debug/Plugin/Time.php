<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Time.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Time.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Time
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Time extends ZFDebug_Controller_Plugin_Debug_Plugin_Time
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
		return number_format(round($this->_timer['postDispatch'], 2), 0, ',', '.') . ' ms';
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
<h4>Time</h4>
<p><a class="iconized time timers-clear" href="/?ZFDebug" title="Reset timers">Reset timers</a></p>
<div id="ZFDebug_timers">
<h5>Current Timer</h5>
<?php

		L8M_Library::dataShow(array(
			'Controller'=>number_format(round($this->_timer['postDispatch'], 2), 0, ',', '.') . ' ms',
		));

?>
<h5>Custom Timers</h5>
<?php

		L8M_Library::dataShow(array(
			'Controller'=>number_format(round(($this->_timer['postDispatch'] - $this->_timer['preDispatch']), 2), 0, ',', '.') . ' ms',
		));

		if (isset($this->_timer['user']) && count($this->_timer['user'])) {
			L8M_Library::dataShow($this->_timer['user']);
		}

		if (!Zend_Session::isStarted()) {
			Zend_Session::start();
		}

		$request = Zend_Controller_Front::getInstance()->getRequest();
		$currentModule = $request->getModuleName();
		$currentController = $request->getControllerName();
		$currentAction = $request->getActionName();

		$timerNamespace = new Zend_Session_Namespace('ZFDebug_Time',false);
		$timerNamespace->data[$currentModule . '.' . $currentController . '.' . $currentAction][] = $this->_timer['postDispatch'] . ' ms';

?>
<h5>Overall Timers</h5>
<?php
		$timerData = $timerNamespace->data;

		/**
		 * Natcasesort expects a single dimension array,
		 * so if your value is an array then it will report a
		 * "Array to string conversion" notice per key/value set.
		 */
		// natcasesort($timerData);
		L8M_Library::dataShow($timerNamespace->data);

?>
</div>
<script type="text/javascript">

$(document).ready(function() {

	////////////////////////////////////////////////////////////
	// clear ZFDebug timer
	////////////////////////////////////////////////////////////

	$("a.timers-clear").click(function() {
		jQuery.ajax({
			url: "/?ZFDEBUG_RESET",
			type: "GET",
			cache: false,
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("div#ZFDebug_timers").html("");
			},
			error: function (request, textStatus, errorThrown) {
				$("div#ZFDebug_timers").html(textStatus);
			}
 		});
		return false;
	});

});
</script>
<?php

		return ob_get_clean();
	}

}