<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Element/GoogleReCaptcha.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleReCaptcha.php 73 2014-05-14 17:21:43Z nm $
 */

/**
 *
 *
 * L8M_Form_Element_GoogleReCaptcha
 *
 *
 */
class L8M_Form_Element_GoogleReCaptcha extends Zend_Form_Element_Xhtml
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
	 *
	 * Setter Methods
	 *
	 *
	 */
	/**
	 * Set element name
	 *
	 * @param  string $name
	 * @return L8M_JQuery_Form_Element_Date
	 */
	public function setName($name)
	{
		parent::setName($name);
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
	 * Class Methods
	 *
	 *
	 */
	public function __construct($spec, $options = NULL)
	{

		/**
		 * set decorator path
		 */
		$this->addPrefixPath(
			'L8M_Form_Decorator',
			'L8M'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator',
			'decorator'
		);

		$this
			->setRequired(TRUE)
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new L8M_Form_Validator_GoogleReCaptcha(),
			))
		;

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
				->addDecorator('GoogleReCaptcha')
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
		return parent::render($view);
	}
}