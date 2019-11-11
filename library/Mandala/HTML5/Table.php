<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/Table.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Table.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_Table
 *
 *
 */
class Mandala_HTML5_Table extends Mandala_HTML5_Element
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
	 * table head
	 * @var Mandala_HTML5_Element
	 */
	private $_tableHeader = NULL;

	/**
	 * table head
	 * @var Mandala_HTML5_Element
	 */
	private $_tableBody = NULL;

	/**
	 * table footer
	 * @var Mandala_HTML5_Element
	 */
	private $_tableFooter = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * constructor of a html5 table
	 * @param string $href
	 * @param string $id
	 * @param string $class
	 * @return Mandala_HTML5_Table
	 */
	public function __construct($class = NULL, $id = NULL) {

		parent::__construct('table', $class, $id);

		$this->_tableBody = new Mandala_HTML5_Element('tbody');

		return $this;

	}

	/**
	 * prepend content to table body
	 * {@inheritDoc}
	 * @see Mandala_HTML5_Element::prepend($content)
	 */
	public function prepend($content) {

		$this->_tableBody->prepend($content);

	}

	/**
	 * append content to table body
	 * {@inheritDoc}
	 * @see Mandala_HTML5_Element::append($content)
	 */
	public function append($content) {

		$this->_tableBody->append($content);

	}

	/**
	 * returns the table header (thead)
	 * @return Mandala_HTML5_Element
	 */
	public function getTableHead() {

		if ($this->_tableHeader == NULL) {
			$this->_tableHeader = new Mandala_HTML5_Element('thead');
		}

		return $this->_tableHeader;

	}

	/**
	 * returns the table footer (tfoot)
	 * @return Mandala_HTML5_Element
	 */
	public function getTableFoot() {

		if ($this->_tableFooter == NULL) {
			$this->_tableFooter = new Mandala_HTML5_Element('tfoot');
		}

		return $this->_tableFooter;

	}

	/**
	 * combine table header (if it exists), body and footer (if it exists)
	 * {@inheritDoc}
	 * @see Mandala_HTML5_Element::__toString()
	 */
	public function __toString() {

		$this->setContent($this->_tableHeader . $this->_tableBody . $this->_tableFooter);

		return parent::__toString();

	}

}