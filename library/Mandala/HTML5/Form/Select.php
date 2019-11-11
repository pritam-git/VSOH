<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/Form/Select.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Select.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_Form_Select
 *
 *
 */
class Mandala_HTML5_Form_Select extends Mandala_HTML5_Element
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * @var string
	 */
	private $_value = NULL;

	/**
	 * @var options
	 */
	private $_options = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * constructor of a html5 list element
	 * @param string $href
	 * @param string $id
	 * @param string $class
	 * @return Mandala_HTML5_Form_Select
	 */
	public function __construct($name, $class = NULL, $id = NULL) {

		parent::__construct('select', $class, $id);
		$this->setAttr('name', $name);

		return $this;

	}

	/**
	 * add option for select element
	 * @param string $value
	 * @param string $label
	 *
	 * @return Mandala_HTML5_Form Select
	 */
	public function addOption($value, $label) {

		$this->_options[$value] = $label;

		return $this;
	}

	/**
	 * {@inheritDoc}
	 * @see Mandala_HTML5_Element::__toString()
	 */
	public function __toString() {

		foreach ($this->_options as $value=>$label) {

			$selected = NULL;

			if ($value == $this->_value) {
				$selected = ' selected';
			}

			$this->append('<option value="' . $value . '"' . $selected . '>' . $label . '</option>');
		}

		return parent::__toString();

	}

	/**
	 * set active value
	 * @param string $value
	 *
	 * @return void
	 */
	public function setValue($value) {
		$this->_value = $value;
	}


}