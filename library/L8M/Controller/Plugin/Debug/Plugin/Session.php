<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Session.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Session.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Session
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Session implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Contains plugin identifier name
	 *
	 * @var string
	 */
	protected $_identifier = 'session';

	/**
	 *
	 *
	 * Interface Methods
	 *
	 *
	 */

	/**
	 * Has to return html code for the menu tab
	 *
	 * @return string
	 */
	public function getTab()
	{
		return 'Session (<span class="session-tab">' . L8M_Session::getSessionCount() . '</span>)';
	}

   /**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		$body = Zend_Controller_Front::getInstance()->getResponse()->getBody();

		ob_start();

?>
<h4>Session</h4>
<p><a class="iconized key session-clear" href="/system/session/clear" title="Clear all Sessions">Clear all Sessions</a></p>
<div class="session-data">
<?php

		foreach($_SESSION as $nameSpace=>$nameSpaceData) {

?>
<h5><?php echo $nameSpace; ?></h5>
<?php

			L8M_Library::dataShow($nameSpaceData);
		}

?>
</div>
<script type="text/javascript">

$(document).ready(function() {

	////////////////////////////////////////////////////////////
	// clear session link
	////////////////////////////////////////////////////////////

	$("a.session-clear").click(function() {
		jQuery.ajax({
			url: "/system/session/clear/format/html",
			type: "GET",
			cache: false,
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("#ZFDebug_debug span.session-tab").html(data);
				$("#ZFDebug_debug div.session-data").html("");
			},
			error: function (request, textStatus, errorThrown) {
				$("#ZFDebug_debug div.session-data").html(textStatus);
			}
 		});
		return false;
	});

});
</script>
<?php

		return ob_get_clean();
	}

	/**
	 * Has to return a unique identifier for the specific plugin
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

}