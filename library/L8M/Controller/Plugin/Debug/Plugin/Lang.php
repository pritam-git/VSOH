<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Lang.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Lang.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Lang
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Lang implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
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
	protected $_identifier = 'lang';

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
		return 'Locale (' . strtoupper(L8M_Locale::getLang()). ')';
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
<h4>Locale</h4>
<h5>Current</h5>
<?php echo L8M_Library::dataShow(strtoupper(L8M_Locale::getLang())); ?>
<h5>Default</h5>
<?php echo L8M_Library::dataShow(strtoupper(L8M_Locale::getDefault())); ?>
<h5>Available</h5>
<ul>
<?php

		foreach (L8M_Locale::getSupported() as $lang) {
?>
	<li>
<?php

		echo strtoupper($lang);

		if (L8M_Locale::getLang() != $lang) {
?>
		<a href="?lang=<?php echo $lang; ?>" class="iconized key change-lang">change to</a>
<?php

		}

?>
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