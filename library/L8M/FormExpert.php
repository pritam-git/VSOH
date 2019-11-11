<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/FormExpert.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FormExpert.php 513 2016-09-05 10:13:44Z nm $
 */

/**
 *
 *
 * L8M_FormExpert
 *
 *
 */
class L8M_FormExpert extends L8M_JQuery_Form
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_setLabelAsPlaceholder = FALSE;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Form instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->_view = Zend_Layout::getMvcInstance()->getView();

		/**
		 * set submit method and default decorators
		 */
		$this
			->setMethod(Zend_Form::METHOD_POST)
			->setDecorators(array(
				new Zend_Form_Decorator_FormElements(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'div',
				)),
				new Zend_Form_Decorator_Form(),
			))
		;
	}

	/**
	 * Adds a hidden form element to the form which will be used to check
	 * whether the form has been submitted.
	 *
	 * @return L8M_Form
	 */
	protected function _addFormSubmittedElement()
	{
		$formSubmitted = new Zend_Form_Element_Hidden($this->_getFormSubmittedIdentifier());
		$formSubmitted
			->setValue($this->_getFormSubmittedValue())
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'style'=>'display:none;',
				)),
			))
		;
		$this->addElement($formSubmitted);
		return $this;
	}

	/**
	 * Add a new element
	 *
	 * $element may be either a string element type, or an object of type
	 * Zend_Form_Element. If a string element type is provided, $name must be
	 * provided, and $options may be optionally provided for configuring the
	 * element.
	 *
	 * If a Zend_Form_Element is provided, $name may be optionally provided,
	 * and any provided $options will be ignored.
	 *
	 * @param  string|Zend_Form_Element $element
	 * @param  string $name
	 * @param  array|Zend_Config $options
	 * @return Zend_Form
	 */
	public function addElement($element, $cssClass = NULL, $name = NULL, $options = NULL)
	{
		/**
		 * retrieve decorator for html-tag
		 * change tag to "div"
		 */
		$htmlTagDecorator = $element->getDecorator('HtmlTag');

		if ($htmlTagDecorator) {
			$htmlTagOptions = $htmlTagDecorator->getOptions();
			if (isset($htmlTagOptions['tag'])) {
				$htmlTagOptions['tag'] = 'div';
			}
			if (isset($htmlTagOptions['class'])) {
				$htmlTagOptions['class'] .= ' form-element-element control';
			} else {
				$htmlTagOptions['class'] = 'form-element-element control';
			}
			$htmlTagDecorator->setOptions($htmlTagOptions);
		}

		/**
		 * handle Label-Decorator
		 */
		if ($this->_setLabelAsPlaceholder &
			(
				$element instanceof Zend_Form_Element_Text ||
				$element instanceof Zend_Form_Element_Textarea ||
				$element instanceof Zend_Form_Element_Password ||
				$element instanceof L8M_Form_Element_Email ||
				$element instanceof L8M_Form_Element_Number ||
				$element instanceof L8M_Form_Element_Phone ||
				$element instanceof L8M_Form_Element_Search ||
				$element instanceof L8M_Form_Element_Url
			)) {

			$element->setAttrib('placeholder', $this->getView()->translate($element->getLabel()));
			$element->setLabel('');
			$element->removeDecorator('Label');
		} else {

			/**
			 * retrieve decorator for label-tag
			 * change tag to "div"
			 */
			$labelDecorator = $element->getDecorator('Label');
			if ($labelDecorator) {
				$labelOptions = $labelDecorator->getOptions();
				if (isset($labelOptions['tag'])) {
					$labelOptions['tag'] = 'div';
				}
				if (isset($labelOptions['tagClass'])) {
					$labelOptions['tagClass'] .= ' form-element-label';
				} else {
					$labelOptions['tagClass'] = 'form-element-label';
				}
				$labelDecorator->setOptions($labelOptions);
			}
		}

		$element->addDecorator(new L8M_Form_Decorator_ElementContainer($cssClass));

		return parent::addElement($element, $name, $options);
	}

	/**
	 * Add a display group
	 *
	 * Groups named elements for display purposes.
	 *
	 * If a referenced element does not yet exist in the form, it is omitted.
	 *
	 * @param  array $elements
	 * @param  string $name
	 * @param  array|Zend_Config $options
	 * @return Zend_Form
	 * @throws Zend_Form_Exception if no valid elements provided
	 */
	public function addDisplayGroup(array $elements, $name, $options = NULL)
	{
		$form = parent::addDisplayGroup($elements, $name, $options);
		$displayGroup = $this->getDisplayGroup($name);

		/**
		 * retrieve decorator for html-tag
		 * change tag to "div"
		 */
		$htmlTagDecorator = $displayGroup->getDecorator('HtmlTag');

		if ($htmlTagDecorator) {
			$htmlTagOptions = $htmlTagDecorator->getOptions();
			if (isset($htmlTagOptions['tag'])) {
				$htmlTagOptions['tag'] = 'div';
			}
			$htmlTagDecorator->setOptions($htmlTagOptions);
		}

		/**
		 * retrieve decorator for label-tag
		 * change tag to "div"
		 */
		$labelDecorator = $displayGroup->getDecorator('Label');
		if ($labelDecorator) {
			$labelOptions = $labelDecorator->getOptions();
			if (isset($labelOptions['tag'])) {
				$labelOptions['tag'] = 'div';
			}
			$labelDecorator->setOptions($labelOptions);
		}

		$displayGroup->removeDecorator('DtDdWrapper');
		$displayGroup->addDecorator(new L8M_Form_Decorator_DtDdWrapper());

		$displayGroup->addDecorator(new L8M_Form_Decorator_ElementGroup());

		return $form;
	}

	/**
	 * Set Handler for Labels and Placeholder.
	 *
	 * @param boolean $value
	 * @return Zend_Form
	 */
	public function setLabelAsPlaceholder($value = TRUE) {
		$this->_setLabelAsPlaceholder = (boolean) $value;
		return $this;
	}
}