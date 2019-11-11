<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Javascript.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Javascript.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Javascript
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Javascript implements ZFDebug_Controller_Plugin_Debug_Plugin_Interface
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
	protected $_identifier = 'javascript';

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
		return ' Javascript (<span class="value ZFDebug_Html_Scriptcount">0</span>)';
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
<h4>Javascript</h4>
<ul class="array-show">
		<li><span class="key">External Javascript Files</span> <span class="value ZFDebug_Html_Scriptcount">0</span></li>
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