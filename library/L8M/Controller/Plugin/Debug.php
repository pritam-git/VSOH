<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Debug.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug
 *
 *
 */
class L8M_Controller_Plugin_Debug extends ZFDebug_Controller_Plugin_Debug
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * The conventional display mode with full tabs.
	 */
	const MODE_NORMAL = 'normal';

	/**
	 * The small display mode with icons only.
	 */
	const MODE_SMALL = 'small';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Contains options to change Debug Bar behavior
	 *
	 * @var array
	 */
	protected $_options = array(
		'plugins'=> array(
		'Variables'=>NULL,
		'Time'=>NULL,
		'Memory'=>NULL),
		'z-index'=>255,
		'jquery_path'=>'http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js',
		'image_path'=>NULL,
		'mode'=>self::MODE_NORMAL,
	);

	/**
	 * Standard plugins
	 *
	 * @var array
	 */
	public static $standardPlugins = array(
		'Auth',
		'Lang',
		'Cache',
		'Css',
		'Database',
		'Doctrine',
		'File',
		'Html',
		'Image',
		'Javascript',
		'Memory',
		'Mobile',
		'Php',
		'Registry',
		'Session',
		'Time',
		'Variables',
		'Xhtml',
	);


	/**
	 * An array of plugin identifiers and associated icon file names.
	 *
	 * @var array
	 */
	protected $_iconMapping = array(
		'auth'=>'user.png',
		'cache'=>'lightning.png',
		'copyright'=>'copyright.gif',
		'css'=>'css.png',
		'database'=>'database.png',
		'doctrine'=>'doctrine.png',
		'file'=>'folder_page.png',
		'html'=>'html.png',
		'image'=>'images.png',
		'javascript'=>'script.png',
		'lang'=>'world.png',
		'memory'=>'chart_bar.png',
		'mobile'=>'phone.png',
		'php'=>'php.png',
		'registry'=>'briefcase.png',
		'session'=>'key.png',
		'text'=>'text_align_left.png',
		'time'=>'time.png',
		'variables'=>'box.png',
		'xhtml'=>'xhtml.png',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Sets options of the Debug Bar
	 *
	 * @param array $options
	 * @return ZFDebug_Controller_Plugin_Debug
	 */
	public function setOptions(array $options = array())
	{
		parent::setOptions($options);

		if (isset($options['mode']) &&
			in_array($options['mode'], array(self::MODE_NORMAL, self::MODE_SMALL))) {
			$this->_options['mode'] = $options['mode'];
		}

		return $this;
	}

	/**
	 * Called after an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * This callback allows for proxy or filter behavior. By altering the
	 * request and resetting its dispatched flag (via
	 * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
	 * a new action may be specified for dispatching.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			return;
		}

		$layout = Zend_Layout::getMvcInstance();
		if (!$layout) {
			return;
		}

		$headers = $this->_response->getHeaders();
		$contentTypeExceptions = array(
			'text/html',
		);
		foreach ($headers as $key => $header) {
			if ($header['name'] == 'Content-Type' &&
				$header['value'] != 'text/html') {

				return;
			}
		}

		$calledForModuleName = $layout->calledForModuleName;
		$calledForControllerName = $layout->calledForControllerName;
		if ($calledForModuleName == 'admin' ||
			$calledForModuleName == 'system' ||
			$calledForModuleName == 'system-model-list' ||
			($calledForModuleName == 'default' && $calledForControllerName == 'error')) {

			return;
		}

		$layout->getView()->headLink()
			->appendStylesheet('/css/system/screen/debug/base.css', 'screen')
			->appendStylesheet('/css/system/screen/debug/color.css', 'screen')
			->appendStylesheet('/css/system/screen/debug/rhythm.css', 'screen')
			->appendStylesheet('/css/system/screen/debug/typography.css', 'screen')
		;
	}

	/**
	 * Called before Zend_Controller_Front exits its dispatch loop.
	 *
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			return;
		}

		$layout = Zend_Layout::getMvcInstance();
		if (!$layout) {
			return;
		}

		$headers = $this->_response->getHeaders();
		$contentTypeExceptions = array(
			'text/html',
		);
		foreach ($headers as $key => $header) {
			if ($header['name'] == 'Content-Type' &&
				$header['value'] != 'text/html') {

				return;
			}
		}

		$calledForModuleName = $layout->calledForModuleName;
		$calledForControllerName = $layout->calledForControllerName;
		if ($calledForModuleName == 'admin' ||
			$calledForModuleName == 'system' ||
			$calledForModuleName == 'system-model-list' ||
			($calledForModuleName == 'default' && $calledForControllerName == 'error')) {

			return;
		}

		/**
		 * prevent caching
		 */
		$this->getResponse()->setHeader('Expires', 0, TRUE);
		$this->getResponse()->setHeader('Cache-Control', 'no-cache', TRUE);

		ob_start();

		/**
		 * Creating menu tab for all registered plugins
		 */
		foreach ($this->_plugins as $plugin) {
			$panel = $plugin->getPanel();
			if ($panel == '') {
				continue;
			}

	/* @var $plugin ZFDebug_Controller_Plugin_Debug_Plugin_Interface */
?>
<div id="ZFDebug_<?php echo $plugin->getIdentifier(); ?>" class="ZFDebug_panel">
<?php
			echo $panel;

?>
</div>
<?php

		}

?>
<div id="ZFDebug_info">
<?php

		/**
		 * Creating panel content for all registered plugins
		 */
		foreach ($this->_plugins as $plugin) {

			$tab = $plugin->getTab();
			if ($tab == '') {
				continue;
			}

			if (isset($this->_options['mode']) &&
				$this->_options['mode'] == self::MODE_SMALL) {
				$tab = NULL;
			}

	/* @var $plugin ZFDebug_Controller_Plugin_Debug_Plugin_Interface */
?>
		<span class="ZFDebug_span clickable <?php echo $plugin->getIdentifier(); ?>" onclick="ZFDebugPanel('ZFDebug_<?php echo $plugin->getIdentifier(); ?>');">
			<img src="<?php echo $this->_icon($plugin->getIdentifier()); ?>" style="vertical-align:middle;" alt="<?php echo $plugin->getIdentifier(); ?>" title="<?php echo $plugin->getIdentifier(); ?>" />
			<?php echo $tab; ?>
		</span>

<?php

		}

?>
		<span class="ZFDebug_span ZFDebug_last clickable" id="ZFDebug_toggler" onclick="ZFDebugSlideBar()">&#171;</span>
</div>
<?php

		$this->_output(ob_get_clean());
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Return version tab
	 *
	 * @return string
	 */
	protected function _getVersionTab()
	{
		return ' ' . Zend_Version::VERSION;
	}

	/**
	 * Returns version panel
	 *
	 * @return string
	 */
	protected function _getVersionPanel()
	{

		ob_start();

?>
<h4>Zend Framework</h4>
<?php

		L8M_Library::dataShow(array('Version'=>Zend_Version::VERSION));

?>
<h5>ZFDebug_Controller_Plugin_Debug (v<?php echo $this->_version; ?>)</h5>
<p>©2008-2009 <a href="http://jokke.dk">Joakim Nygård</a> &amp; <a href="http://www.bangal.de">Andreas Pankratz</a></p>
<p>The project is hosted at <a href="http://code.google.com/p/zfdebug/">http://zfdebug.googlecode.com</a> and released under the BSD License. Includes images from the <a href="http://www.famfamfam.com/lab/icons/silk/">Silk Icon set</a> by Mark James</p>
<h5>L8M_Debug_Controller_Plugin_Debug</h5>
<p>©<?php echo date('Y'); ?> <a href="mailto:nm@l8m.com" title="nm@l8m.com">Norbert Marks</a></p>
<?php

		return ob_get_clean();
	}

		/**
	 * Returns path to the specific icon
	 *
	 * @return string
	 */
	protected function _icon($kind)
	{
		if (isset($this->_iconMapping[$kind])) {
			$iconFile = $this->_iconMapping[$kind];
		} else {
			$iconFile = 'unknown.png';
		}
		return $this->_options['image_path'] . '/' . $iconFile;
	}

	/**
	 * Returns html header for the Debug Bar
	 *
	 * @return string
	 */
	protected function _headerOutput()
	{
		$collapsed = isset($_COOKIE['ZFDebugCollapsed'])
				   ? $_COOKIE['ZFDebugCollapsed']
				   : 0
				;

		ob_start();

?>
<script type="text/javascript" charset="utf-8">

	if (typeof jQuery == "undefined") {
		var scriptObj = document.createElement("script");
		scriptObj.src = "<?php echo $this->_options['jquery_path']; ?>";
		scriptObj.type = "text/javascript";
		var head=document.getElementsByTagName("head")[0];
		head.insertBefore(scriptObj,head.firstChild);
	}

	var ZFDebugLoad = window.onload;
	window.onload = function(){
		if ($(window).width() < 1920) {
			$("div#ZFDebug_info span.database").hide();
			$("div#ZFDebug_info span.cache").hide();
			$("div#ZFDebug_info span.session").hide();
		}

		if (ZFDebugLoad) {
			ZFDebugLoad();
		}
		ZFDebugCollapsed();
	};

	function ZFDebugCollapsed() {
		if (<?php echo $collapsed; ?> == 1) {
			ZFDebugPanel();
			$("#ZFDebug_toggler").html("&#187;");
			return $("#ZFDebug_debug").css("left", "-"+parseInt($("#ZFDebug_debug").outerWidth()-jQuery("#ZFDebug_toggler").outerWidth()+1)+"px");
		}
	}

	function ZFDebugPanel(name) {
		$(".ZFDebug_panel").each(function(i){
			if($(this).css("display") == "block") {
				$(this).slideUp();
			} else {
				if ($(this).attr("id") == name) {
					$(this).slideDown();
				} else {
					$(this).slideUp();
				}
			}
		});
	}

	function ZFDebugSlideBar() {
		if ($("#ZFDebug_debug").position().left > 0) {
			document.cookie = "ZFDebugCollapsed=1;expires=;path=/";
			ZFDebugPanel();
			$("#ZFDebug_toggler").html("&#187;");
			return $("#ZFDebug_debug").animate({left:"-"+parseInt($("#ZFDebug_debug").outerWidth()-jQuery("#ZFDebug_toggler").outerWidth()+1)+"px"}, "normal", "swing");
		} else {
			document.cookie = "ZFDebugCollapsed=0;expires=;path=/";
			$("#ZFDebug_toggler").html("&#171;");
			return $("#ZFDebug_debug").animate({left:"5px"}, "normal", "swing");
		}
	}

	function ZFDebugToggleElement(name, whenHidden, whenVisible){
		if($(name).css("display")=="none"){
			$(whenVisible).show();
			$(whenHidden).hide();
		} else {
			$(whenVisible).hide();
			$(whenHidden).show();
		}
		$(name).slideToggle();
	}
</script>
<?php

		return ob_get_clean();
	}

	/**
	 * Appends Debug Bar html output to the original page
	 *
	 * @param string $html
	 * @return void
	 */
	protected function _output($html)
	{
		$response = $this->getResponse();
		$response->setBody(str_ireplace('</body>', '<div id="ZFDebug_debug">'.$html.'</div></body>', $response->getBody()));

		$match = array();
		preg_match('/(<\/head>)/i', $response->getBody(), $match);
		if (count($match) > 0) {
			$response->setBody(preg_replace('/(<\/head>)/i', $this->_headerOutput() . '$1', $response->getBody()));
		} else {
			$response->setBody(preg_replace('/(<\/body>)/i', $this->_headerOutput() . '$1', $response->getBody()));
		}
	}

}