<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Html.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Html.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Html
 *
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Html extends ZFDebug_Controller_Plugin_Debug_Plugin_Html
{

	/**
	 *
	 *
	 *
	 * Class Methods
	 *
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
		$body = Zend_Controller_Front::getInstance()->getResponse()->getBody();

		ob_start();

?>
<h4>HTML</h4>
<script type="text/javascript" charset="utf-8">
	var ZFHtmlLoad = window.onload;
	window.onload = function(){
		if (ZFHtmlLoad) {
			ZFHtmlLoad();
		}
		$("#ZFDebug_Html_Tagcount").html(document.getElementsByTagName("*").length);
		$("#ZFDebug_Html_Stylecount").html($("link[rel*=stylesheet]").length);
		$("#ZFDebug_Html_Scriptcount").html($("script[src]").length);
		$("#ZFDebug_Html_Imgcount").html($("img[src]").length);
	};
</script>
<ul class="array-show">
	<li><span class="key">Tags</span> <span class="value" id="ZFDebug_Html_Tagcount"></span></li>
	<li><span class="key">HTML Size</span> <span class="value"><?php echo L8M_Library::getBytes(strlen($body)); ?></span></li>
	<li><span class="key">CSS Files</span> <span class="value" id="ZFDebug_Html_Stylecount"></span></li>
	<li><span class="key">JavaScript Files</span> <span class="value" id="ZFDebug_Html_Scriptcount"></span></li>
	<li><span class="key">Image Files</span> <span class="value" id="ZFDebug_Html_Imgcount"></span></li>
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

}