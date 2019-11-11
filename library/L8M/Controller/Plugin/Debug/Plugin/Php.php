<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Php.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Php.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Php
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Php implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
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
	protected $_identifier = 'php';

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
		return phpversion();
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
<h4>PHP</h4>
<?php
		L8M_Library::dataShow(array(
			'Version'=>phpversion(),
		));

		$phpExtensions = get_loaded_extensions();
		natcasesort($phpExtensions);

		if (is_array($phpExtensions)) {

	?>
<h5>PHP Extensions</h5>
<ul>
	<li><?php echo implode('</li><li>', $phpExtensions); ?></li>
</ul>
<?php

		}

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