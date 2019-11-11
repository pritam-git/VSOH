<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Password.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Password.php 436 2015-09-29 09:02:07Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Password
 *
 *
 */
class L8M_JQuery_Form_Element_Password extends Zend_Form_Element_Xhtml
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	/**
	 *
	 * @var array
	 */
	protected static $_passwordEditorIds = array();

	/**
	 * Contains TRUE when head script has been added to initialize all tiny mce
	 * editors on page.
	 *
	 * @var bool
	 */
	protected static $_passwordInitialized = FALSE;

	/**
	 * passwordPickerOptions
	 */
	private $_passwordPickerOptions = array(
	);

	/**
	 * password default
	 */
	private $_passwordDefault = NULL;

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
	 * @return L8M_JQuery_Form_Element_Password
	 */
	public function setName($name)
	{
		unset(self::$_passwordEditorIds[$this->getName()]);
		parent::setName($name);
		self::$_passwordEditorIds[$name] = $name;
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
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	public function __construct($spec, $passwordOptions = NULL, $options = NULL)
	{

		/**
		 * set decorator path
		 */
		$this->addPrefixPath(
			'L8M_JQuery_Form_Decorator',
			'L8M'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator',
			'decorator'
		);

		/**
		 * set validator
		 */
		$zendValidateStringLength = new Zend_Validate_StringLength(array(
				'min'=>8,
				'max'=>34,
		));

		$zendValidateStringLength->setMessage('String is less than %min% characters long', 'stringLengthTooShort');
		$zendValidateStringLength->setMessage('String is more than %max% characters long', 'stringLengthTooLong');

		$this->setValidators(array(
			$zendValidateStringLength,
			new Zend_Validate_NotEmpty(),
		));

		$this->setRequired();

		if (is_array($passwordOptions)) {
			$this->_passwordOptions = array_merge($this->_passwordOptions, $passwordOptions);
		}

		self::$_passwordEditorIds[$spec] = $spec;

		/**
		 * parent constructor
		 */
		parent::__construct($spec, $options);
	}

	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}

		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this
				->addDecorator('Password')
				->addDecorator('Errors')
				->addDecorator('Description', array(
					'tag'   => 'p',
					'class' => 'description'
					))
				->addDecorator('HtmlTag', array(
					'tag' => 'dd',
					'id'  => $this->getName() . '-element'
					))
				->addDecorator('Label', array('tag' => 'dt'))
			;
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
		if (!self::$_passwordInitialized) {

?>
<script src="/js/jquery/plugins/pwdstr/jquery.pwdstr-1.0.source.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {

	});
//]]>
</script>
<?php

				self::$_passwordInitialized = TRUE;
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
		/**
		 * retrieve value from parent
		 */
		$returnValue = parent::getValue();

		/**
		 * get default
		 */
		$defaultValue = $this->getDefaultValue();

		/**
		 * generate hash if is new
		 */
		if ($defaultValue != $returnValue &&
			strlen($returnValue) >= 8 &&
			strlen($returnValue) <= 34) {

			$returnValue = L8M_Library::generateDBPasswordHash($returnValue);
		}

		return $returnValue;
	}

	public function getDefaultValue()
	{
		return $this->_passwordDefault;
	}

	public function setDefaultValue($password)
	{
		$this->_passwordDefault = $password;
	}
}