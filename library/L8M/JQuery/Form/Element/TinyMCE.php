<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/TinyMCE.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TinyMCE.php 357 2015-06-17 11:00:14Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_TinyMCE
 *
 *
 */
 class L8M_JQuery_Form_Element_TinyMCE extends Zend_Form_Element
 {

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Use formTextarea view helper by default
	 * @var string
	 */
	public $helper = 'formTinyMCE';

	/**
	 * contains IDs
	 *
	 * @var array
	 */
	protected static $_tinyMceEditorIds = array();

	/**
	 * Contains Style Options
	 */
	protected $_styleOptions = array(
		'width'=>'815',
		'relative_urls'=>'false',
		'remove_script_host'=>'true',
		'convert_urls'=>'true',
	);

	/**
	 * Contains TRUE when head script has been added to initialize all tiny mce
	 * editors on page.
	 *
	 * @var bool
	 */
	protected static $_tinyMceInitialized = FALSE;


	public function __construct($spec, $styleOptions = NULL, $options = NULL)
	{
		if (is_array($styleOptions)) {
			if (isset($styleOptions['width']) &&
				is_numeric($styleOptions['width'])) {

				$this->_styleOptions['width'] = $styleOptions['width'];
			}
			if (isset($styleOptions['relative_urls']) &&
				is_bool($styleOptions['relative_urls'])) {

				if ($styleOptions['relative_urls']) {
					$this->_styleOptions['relative_urls'] = 'true';
				} else {
					$this->_styleOptions['relative_urls'] = 'false';
				}
			}
			if (isset($styleOptions['remove_script_host']) &&
				is_bool($styleOptions['remove_script_host'])) {

				if ($styleOptions['remove_script_host']) {
					$this->_styleOptions['remove_script_host'] = 'true';
				} else {
					$this->_styleOptions['remove_script_host'] = 'false';
				}
			}
			if (isset($styleOptions['convert_urls']) &&
				is_bool($styleOptions['convert_urls'])) {

				if ($styleOptions['convert_urls']) {
					$this->_styleOptions['convert_urls'] = 'true';
				} else {
					$this->_styleOptions['convert_urls'] = 'false';
				}
			}
		}

		parent::__construct($spec, $options);
	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Set element name
	 *
	 * @param  string $name
	 * @return L8M_JQuery_Form_Element_TinyMCE
	 */
	public function setName($name)
	{
		unset(self::$_tinyMceEditorIds[$this->getName()]);
		parent::setName($name);
		self::$_tinyMceEditorIds[$name] = $name;
		return $this;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	 /**
	 * Initialize object; used by extending classes
	 *
	 * @return void
	 */
	public function init()
	{
		self::$_tinyMceEditorIds[$this->getName()] = $this->getName();
	}

	/**
	 * Render form element
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null)
	{

		/**
		 * html for form element
		 */
		ob_start();

		/**
		 * render head script
		 */
		$layout = Zend_Layout::getMvcInstance();
		if ($layout->isEnabled()) {
			$this->_renderHeadScript();
		} else {
			if (!self::$_tinyMceInitialized) {

?>
<style type="text/css">
	@import url(/css/screen/js/tinymce.css);
</style>
<script type="text/javascript" src="/js/jquery/plugins/tinymce/jquery.tinymce.min.js"></script>

<script type="text/javascript">
	var l8mEditorLoaded = false;

	$(document).ready(
		function() {
			if (typeof(jQuery.fn.colorbox) == "undefined") {
				l8mEditorLoaded = true;
				<?php echo $this->_renderInlineScript(); ?>
			} else {
				//alert('colorbox defined');
				window.setTimeout("localheinzTinyMCEinit()", 1500);
			}
		}
	);

	function localheinzTinyMCEinit() {
		if (l8mEditorLoaded == false) {
			l8mEditorLoaded = true;
			<?php echo $this->_renderInlineScript(); ?>
		}
	}
</script>
<?php

				self::$_tinyMceInitialized = TRUE;
			}
		}

		$content = ob_get_clean()
				 . parent::render($view)
		;

		return $content;
	}

	/**
	 * Retrieve filtered element value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Renders head script.
	 *
	 * @return L8M_Form_Element_TinyMCE
	 */
	protected function _renderHeadScript()
	{
		if (!self::$_tinyMceInitialized) {
			$view = $this->getView();
			if ($view) {
				$view->jQuery()
					->addJavascriptFile('/js/jquery/plugins/tinymce/jquery.tinymce.min.js')
				;
				$view->headLink()
					->appendStylesheet('/css/screen/js/tinymce.css', 'screen')
				;
				$view->headScript()->captureStart();
				$this->_renderInlineScript(TRUE);
				$view->headScript()->captureEnd();

			}
			self::$_tinyMceInitialized = TRUE;
		}
		return $this;
	}

	/**
	 * Returns inline script.
	 *
	 * @return void
	 */
	protected function _renderInlineScript($documentReady = false)
	{

		$roleShort = 'guest';
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;
		}

		if (L8M_Config::getOption('L8M_JQuery_Form_Element_TinyMCE.role.' . $roleShort . '.showFontConfig')) {
			$themeAdvancedButtons1FontConfig = '| fontselect fontsizeselect ';
		} else {
			$themeAdvancedButtons1FontConfig = '';
		}

		if ($documentReady) {

?>
$(document).ready(function() {
<?php

		}

?>
	$('div.tinymce').tinymce({
		// Location of TinyMCE script
		script_url : '/js/jquery/plugins/tinymce/tinymce.min.js',

		// General options
		theme : "modern",
		language : "<?php echo L8M_Locale::getLang(); ?>",
		plugins : [
			"advlist autolink lists link image charmap print preview hr anchor",
			"searchreplace visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table contextmenu directionality",
			"emoticons template paste textcolor sysviewhelper"
		],

		// Some advanced options
		image_advtab: true,

		// Toolbars
		toolbar1: "styleselect <?php echo $themeAdvancedButtons1FontConfig; ?>| forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
		toolbar2: "undo redo | print preview | bullist numlist | link image media | emoticons | sysviewhelper | code",

		// Content CSS (should be your site CSS or just for that special element)
		setup : function(ed) {
			// do things with editor ed
<?php

			foreach (self::$_tinyMceEditorIds as $tinyMceEditorId) {

?>
			if (ed.id == '<?php echo $tinyMceEditorId; ?>') {
				ed.settings.content_css = "<?php echo L8M_JQuery_Form_Element_TinyMCE_Css::getCssFile($tinyMceEditorId); ?>";
			}
<?php

			}

?>
		},

		// System-ViewHelper
		sysviewhelper_list: [
<?php

		$sysViewHelpers = array();
		$sysViewHelperDirectory = BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Helper' . DIRECTORY_SEPARATOR . 'TinyMCE';
		if (file_exists($sysViewHelperDirectory) &&
			is_dir($sysViewHelperDirectory)) {

			$directoryIterator = new DirectoryIterator($sysViewHelperDirectory);
			foreach($directoryIterator as $file) {
				/* @var $file DirectoryIterator */
				if ($file->isFile() &&
					preg_match('/^Tmce(.+)\.php$/', $file->getFilename(), $match)) {

					$sysViewHelpers[] = '{title: \'' . $match[1] . '\', value: \'PRJ_View_Helper_TinyMCE_Tmce' . $match[1] . '\'}';
				}
			}

			echo implode(',', $sysViewHelpers);
		}

?>
		],

<?php

		$templateCollection = Doctrine_Query::create()
			->from('Default_Model_TinymceTemplate m')
			->orderBy('m.name ASC')
			->execute()
		;

		if ($templateCollection->count() > 0) {

?>
		// Templates
		templates: [
<?php

			$countTemplate = 0;
			foreach ($templateCollection as $templateModel) {

				echo '{title: \'' . $templateModel->name . '\', content: \'' . str_replace('\'', '\\\'', str_replace("\t", '', str_replace("\n", '', str_replace("\r", '', str_replace(PHP_EOL, '', $templateModel->content))))) . '\'}';
				if ($countTemplate < $templateCollection->count() - 1) {
					echo ',';
				}
				$countTemplate++;
			}

?>
		],
<?php

		}

?>
		// Config
		forced_root_block : '',
		valid_children : "+a[div],+a[p]",
		extended_valid_elements : "q[cite|class|title],article,section,hgroup,figure,figcaption,i[class],em[class]",
		entity_encoding : "numeric",

		// options
		width: '<?php echo $this->_styleOptions['width']; ?>',
		height: '640',
		relative_urls: <?php echo $this->_styleOptions['relative_urls']; ?>,
		remove_script_host: <?php echo $this->_styleOptions['remove_script_host']; ?>,
		convert_urls: <?php echo $this->_styleOptions['convert_urls']; ?>,
	});
<?php

		if ($documentReady) {

?>
});
<?php

		}

	}
}