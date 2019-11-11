<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Xhtml.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Xhtml.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Xhtml
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Xhtml extends ZFDebug_Controller_Plugin_Debug_Plugin_Html
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
	protected $_identifier = 'xhtml';

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
		return ' XHTML (<span class="value ZFDebug_Html_Tagcount">0</span>)';
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
<h4>XHTML</h4>
<script type="text/javascript" charset="utf-8">
var ZFHtmlLoad = window.onload;
window.onload = function(){
	if (ZFHtmlLoad) {
		ZFHtmlLoad();
	}
	$(".ZFDebug_Html_Tagcount").html(document.getElementsByTagName("*").length);
	$(".ZFDebug_Html_Stylecount").html($("link[rel*=stylesheet]").length);
	$(".ZFDebug_Html_Scriptcount").html($("script[src]").length);
	$(".ZFDebug_Html_Imgcount").html($("img[src]").length);
};
</script>
<ul class="array-show">
	<li><span class="key">Tags</span> <span class="value ZFDebug_Html_Tagcount">0</span></li>
</ul>
<h5>Validation</h5>
<form method="<?php echo Zend_Form::METHOD_POST; ?>" action="http://validator.w3.org/check">
	<fieldset>
		<input type="hidden" name="charset" value="utf-8" />
		<input type="hidden" name="fragment" value="<?php echo htmlentities($body); ?>" />
		<input type="submit" value="Validate With W3C" />
	</fieldset>
</form>
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