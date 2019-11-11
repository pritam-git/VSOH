<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/Table/Row.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Row.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_Table_Row
 *
 *
 */
class Mandala_HTML5_Table_Row extends Mandala_HTML5_Element
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
	 * multiselectable flag
	 * @var boolean
	 */
	private $_multiSelectabe = FALSE;


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

		parent::__construct('tr', $class, $id);

		return $this;

	}

}