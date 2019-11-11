<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Mobile.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Mobile.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Mobile
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Mobile implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
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
	protected $_identifier = 'mobile';

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
		$returnValue = 'Mobile (';
		if (L8M_Mobile_Detector::isMobileDevice()) {
			$returnValueEnabled = 'enabled';

			if (L8M_Mobile_Detector::getDeviceName() != 'n/a') {
				$returnValueEnabled = L8M_Mobile_Detector::getDeviceName();
			}
			$returnValue .= $returnValueEnabled;
		} else {
			$returnValue .= 'disabled';
		}
		$returnValue .= ')';
		return $returnValue;
	}

	/**
	 * Has to return html code for the content panel
	 *
	 * @return string
	 */
	public function getPanel()
	{
		ob_start();

?>
<h4>Mobile</h4>
<h5>Mobile Detector Enabled</h5>
<?php

		$mobileDetectorEnabled = FALSE;
		if (L8M_Config::getOption('mobile.enabled')) {
			$mobileDetectorEnabled = TRUE;
		}
		echo L8M_Library::dataShow($mobileDetectorEnabled);

?>
<h5>Mobile View Enabled</h5>
<?php echo L8M_Library::dataShow(L8M_Mobile_Detector::isMobileDevice()); ?>
<h5>Device Name</h5>
<?php echo L8M_Library::dataShow(L8M_Mobile_Detector::getDeviceName()); ?>
<h5>Device Short</h5>
<?php echo L8M_Library::dataShow(L8M_Mobile_Detector::getDeviceShort()); ?>
<h5>Switch</h5>
<ul>
<?php

		if (L8M_Mobile_Detector::isMobileDevice()) {
			$linkString = 'false';
			$linkName = 'disable';
		} else {
			$linkString = 'true';
			$linkName = 'enable';
		}
?>
	<li>
		<a href="?dev-mobile-view=<?php echo $linkString; ?>&dev-mobile-short=&dev-mobile-name=" class="iconized key change-lang"><?php echo $linkName; ?></a>
	</li>
<?php

		if (L8M_Mobile_Detector::getDeviceShort() != 'iphone') {

?>
	<li>
		<a href="?dev-mobile-view=true&dev-mobile-short=iphone&dev-mobile-name=iPhone" class="iconized key change-lang">enable iPhone</a>
	</li>
<?php

		}
		if (L8M_Mobile_Detector::getDeviceShort() != 'ipad') {

?>
	<li>
		<a href="?dev-mobile-view=true&dev-mobile-short=ipad&dev-mobile-name=iPad" class="iconized key change-lang">enable iPad</a>
	</li>
<?php

		}
		if (L8M_Mobile_Detector::isMobileDevice() &&
			L8M_Mobile_Detector::getDeviceShort() != 'n/a') {

?>
	<li>
		<a href="?dev-mobile-view=true&dev-mobile-short=&dev-mobile-name=" class="iconized key change-lang">enable unnamed</a>
	</li>
<?php

		}

?>
</ul>
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