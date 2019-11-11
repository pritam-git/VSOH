<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/MultiTab.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MultiTab.php 568 2018-06-21 08:02:25Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Multi
 *
 *
 */
class L8M_JQuery_Form_Element_MultiTab extends Zend_Form_Element_Xhtml
{

 	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * contains element values
	 *
	 * @var array
	 */
	protected $_elementValues = array();

	/**
	 * contains possible type of element:
	 * -textInput
	 * -textarea
	 * -tinyMCE
	 *
	 * @var array
	 */
	protected $_elementTypes = array(
		"textInput",
		"textarea",
		"tinyMCE",
	);

	/**
	 *
	 *
	 * @var string
	 */
	protected $_elementType = "";

	/**
	 * atributes for textarea
	 *
	 * @var array
	 */
	protected $_attribs = array();

	/**
	 * atributes for required multiTab
	 *
	 * @var boolean
	 */
	protected $_isRequiredMultiTab = FALSE;

	/**
	 * Contains TRUE when head script has been added to initialize all multitabs.
	 *
	 * @var bool
	 */
	protected static $_multiTabsInitialized = FALSE;

	/**
	 * Contains Style Options
	 */
	protected $_styleOptions = array();

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Set element-type
	 *
	 * @param  string $type
	 * @return L8M_JQuery_Form_Element_MultiTab
	 */
	public function setElementType($type)
	{
		if (in_array($type, $this->_elementTypes)) {
			$this->_elementType = $type;
		} else {
			/**
			 * @todo throw exception
			 */
		}
		return $this;
	}

	/**
	 * Set elements
	 *
	 * @param  string $values
	 * @return L8M_JQuery_Form_Element_MultiTab
	 */
	public function setElements($values)
	{
		$this->_elementValues = $values;
		return $this;
	}

	/**
	 * Set attributes
	 *
	 * @param  string $attributes
	 * @return L8M_JQuery_Form_Element_MultiTab
	 */
	public function setAttribs($attributes)
	{
		$this->_attribs = $attributes;
		return $this;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
	 * Get elements
	 *
	 * @return Zend_Form_Element
	 */
	public function getElements()
	{
		$formElements = array();
		foreach($this->_elementValues as $key => $value) {
			if (is_array($value) &&
				array_key_exists('elementName', $value) &&
				array_key_exists('elementValue', $value)) {

				$formElementName = $value['elementName'];
				$formElementValue = $value['elementValue'];
			} else {
				$formElementName = $this->getName() . '_' . $key;
				$formElementValue = $value;
			}

			switch ($this->_elementType) {
				case 'textarea':
					$formElement = new Zend_Form_Element_Textarea($formElementName);
					$formElement->setAttribs($this->_attribs);
					if (!isset($this->_attribs['cols'])) {
						$formElement->setAttrib('cols', 20);
					}
					if (!isset($this->_attribs['rows'])) {
						$formElement->setAttrib('rows', 3);
					}
					break;
				case 'tinyMCE':
					$formElement = new L8M_JQuery_Form_Element_TinyMCE($formElementName, array_merge(array('width'=>'800'), $this->_styleOptions));
					break;
				case 'textInput':
				default:
					$formElement = new Zend_Form_Element_Text($formElementName);
					$formElement->setAttribs($this->_attribs);
			}
			$formElement
				->setValue($formElementValue)
				->addDecorator('HtmlTag', array())
				->addDecorator('Label', array())
			;
			$formElements[$key] = $formElement;
		}
		return $formElements;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	public function __construct($spec, $setAsRequired = FALSE, $styleOptions = array(), $options = null)
	{
		/**
		 * set possble type
		 */
		$this->_elementType = $this->_elementTypes[0];

		/**
		 * set decorator path
		 */
		$this->addPrefixPath(
			'L8M_JQuery_Form_Decorator',
			'L8M'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator',
			'decorator'
		);

		$this->_isRequiredMultiTab = $setAsRequired;

		if (is_array($styleOptions)) {
			$this->_styleOptions = $styleOptions;
		}

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	/**
	 * Validate element value
	 *
	 * If a translation adapter is registered, any error messages will be
	 * translated according to the current locale, using the given error code;
	 * if no matching translation is found, the original message will be
	 * utilized.
	 *
	 * Note: The *filtered* value is validated.
	 *
	 * @param  mixed $value
	 * @param  mixed $context
	 * @return boolean
	 */
	public function isValid($value, $context = null)
	{
		$returnValue = FALSE;

		if ($this->_isRequiredMultiTab) {
			$missingValues = FALSE;

			/**
			 * @var Zend_Controller_Request_Abstract
			 */
			$requestObject = Zend_Controller_Front::getInstance()->getRequest();

			/**
			 * elements check
			 */
			$formElements = $this->getElements();
			foreach ($formElements as $key => $formElement) {
				if (!$requestObject->getParam($formElement->getId())) {
					$missingValues = TRUE;
				}
			}

			if ($missingValues) {
				$this->isRequired();
				$this->setAllowEmpty(FALSE);
				$this->addErrorMessage(L8M_Translate::string('Value is required and can\'t be empty'));
				$this->markAsError();
			}
		}

		if (!parent::isValid($value, $context)) {

			/**
			 * this have to be called twice :(
			 */
			$this->markAsError();
		} else {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('MultiTab')
				->addDecorator('Errors')
				->addDecorator('Description', array(
					'tag'   => 'p',
					'class' => 'description'
					))
				->addDecorator('HtmlTag', array(
					'tag' => 'dd',
					'id'  => $this->getName() . '-element'
					))
			;

			if ($this->_isRequiredMultiTab) {
				$this->addDecorator('Label', array('tag'=>'dt', 'class'=>'required'));
			} else {
				$this->addDecorator('Label', array('tag'=>'dt'));
			}
		}
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
			if (!self::$_multiTabsInitialized) {

?>
<script type="text/javascript">
	var l8mMultiTabsLoaded = false;

	$(document).ready(
		function() {
			if (typeof(jQuery.fn.colorbox) == "undefined") {
				l8mMultiTabsLoaded = true;
				<?php echo $this->_renderInlineScript(); ?>
			} else {
				//alert('colorbox defined');
				window.setTimeout("localheinzMultiTABinit()", 1500);
			}
		}
	);

	function localheinzMultiTABinit() {
		if (l8mMultiTabsLoaded == false) {
			l8mMultiTabsLoaded = true;
			<?php echo $this->_renderInlineScript(); ?>
		}
	}
</script>
<?php

				self::$_multiTabsInitialized = TRUE;
			}
		}

		$content = ob_get_clean();
		$content.= parent::render($view);

		return $content;
	}

	/**
	 * Renders head script.
	 *
	 * @return L8M_Form_Element_TinyMCE
	 */
	protected function _renderHeadScript()
	{
		if (!self::$_multiTabsInitialized) {
			$view = $this->getView();
			if ($view) {
				$view->headScript()->captureStart();
				$this->_renderInlineScript(true);
				$view->headScript()->captureEnd();

			}
			self::$_multiTabsInitialized = TRUE;
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

		/**
		 * document ready start
		 */
		if ($documentReady) {
?>

$(document).ready(function() {

<?php
		}

		/**
		 * do we use jQueryUi or jQueryTools
		 */
		if (Zend_Registry::isRegistered('jQueryUI') &&
			Zend_Registry::get('jQueryUI') !== FALSE) {
?>

	$("div.multitabs").tabs();

<?php
		} else
		if (Zend_Registry::isRegistered('jQueryTools') &&
			Zend_Registry::get('jQueryTools') !== FALSE) {
?>

	// select #flowplanes and make it scrollable. use circular and navigator plugins
	$(".multitabflowpanes").scrollable({ circular: false, mousewheel: true }).navigator({

		// select multitabflowtabs to be used as navigator
		navi: ".multitabflowtabs",

		// select A tags inside the navigator to work as items (not direct children)
		naviItem: 'a',

		// assign "current" class name for the active A tag inside navigator
		activeClass: 'current',

		// make browser's back button work
		history: true

	});

<?php
		}

		/**
		 * document ready end
		 */
		if ($documentReady) {
?>
});
<?php
		}

	}
}