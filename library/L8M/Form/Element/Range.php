<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Element/Range.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Range.php 87 2014-05-14 17:23:58Z nm $
 */

/**
 *
 *
 * L8M_Form_Element_Range
 *
 *
 */
class L8M_Form_Element_Range extends Zend_Form_Element_Text
{
	/**
	 * Default form view helper to use for rendering
	 * @var string
	 */
	public $helper = 'formRange';

	/**
	 * Constructor
	 *
	 * $spec may be:
	 * - string: name of element
	 * - array: options with which to configure element
	 * - Zend_Config: Zend_Config with options for configuring element
	 *
	 * @param  string|array|Zend_Config $spec
	 * @param  array|Zend_Config $options
	 * @return void
	 * @throws Zend_Form_Exception if no element name after initialization
	 */
	public function __construct($spec, $min = NULL, $max = NULL, $step = NULL, $dataBuffer = NULL, $options = null)
	{
		parent::__construct($spec, $options);

		if ($min !== NULL) {
			$this->setAttrib('min', $min);
		}
		if ($max !== NULL) {
			$this->setAttrib('max', $max);
		}
		if ($step !== NULL) {
			$this->setAttrib('step', $step);
		}
		if ($dataBuffer !== NULL) {
			$this->setAttrib('data-buffer', $dataBuffer);
		}
	}
}
