<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Css.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Css.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Css
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Css implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
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
	protected $_identifier = 'css';

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
		return ' CSS (<span class="value ZFDebug_Html_Stylecount">0</span>)';
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
<h4>CSS</h4>
<ul class="array-show">
	<li><span class="key">External CSS Files</span> <span class="value ZFDebug_Html_Stylecount">0</span></li>
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