<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/ElementContainer.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ElementContainer.php 512 2016-09-05 09:57:31Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_ElementContainer
 *
 *
 */
class L8M_Form_Decorator_ElementContainer extends Zend_Form_Decorator_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	/**
	 * @var String $_cssClass
	 */
	private $_cssClass = NULL;

	/**
	 * Constructor
	 *
	 * @param  String $cssClass
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($cssClass = NULL, $options = null)
	{
		$this->_cssClass = (String) $cssClass;

		parent::__construct($options = null);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	/**
	 * Render
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{
		$element = $this->getElement();
		$elementName = $element->getName();

		$cssStyle = NULL;
		if ($element instanceof Zend_Form_Element_Hidden) {
			$cssStyle = ' style="display:none;"';
		}

		$cssClass = NULL;
		$cssClassControlGroup = NULL;
		if ($this->_cssClass) {
			$cssClass .= ' ' . $this->_cssClass;
		}
		if ($element instanceof Zend_Form_Element_Button) {
			$cssClass .= ' form-element-button';
		} else
		if ($element instanceof Zend_Form_Element_Captcha) {
			$cssClass .= ' form-element-captcha';
		} else
		if ($element instanceof Zend_Form_Element_Checkbox) {
			$cssClass .= ' form-element-checkbox';
		} else
		if ($element instanceof L8M_Form_Element_Email) {
			$cssClass .= ' form-element-input form-element-email';
		} else
		if ($element instanceof L8M_Form_Element_Phone) {
			$cssClass .= ' form-element-input form-element-phone';
		} else
		if ($element instanceof Zend_Form_Element_Select) {
			$cssClass .= ' form-element-select';
		} else
		if ($element instanceof L8M_Form_Element_Search) {
			$cssClass .= ' form-element-input form-element-search';
		} else
		if ($element instanceof Zend_Form_Element_Text) {
			$cssClass .= ' form-element-input';
		} else
		if ($element instanceof Zend_Form_Element_Textarea) {
			$cssClass .= ' form-element-textarea';
		} else
		if ($element instanceof L8M_Form_Element_Url) {
			$cssClass .= ' form-element-input form-element-url';
		}

		if ($element instanceof Zend_Form_Element) {
			if ($element->hasErrors()) {
				$cssClass .= ' form-element-container-has-errors';
				$cssClassControlGroup .= ' validation error';
			}

			/**
			 * retrieve decorator for label-tag
			 */
			$labelDecorator = $element->getDecorator('Label');
			if (!$labelDecorator) {
				$cssClass .= ' form-element-container-has-no-label';
			}
		}

		return '<div id="' . $elementName . '-container" class="form-element-container' . $cssClass . ' control-group' . $cssClassControlGroup . '"' . $cssStyle . '>' . $content . '</div>';
	}
}
