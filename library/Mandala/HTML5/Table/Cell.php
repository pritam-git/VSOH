<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/Table/Cell.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Cell.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_Table_Cell
 *
 *
 */
class Mandala_HTML5_Table_Cell extends Mandala_HTML5_Element
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
	 * @param boolean $isHeadCell
	 * @param string $id
	 * @param string $class
	 * @return Mandala_HTML5_Table
	 */
	public function __construct($isHeadCell = FALSE, $class = NULL, $id = NULL) {

		$tag = 'td';

		if ($isHeadCell) {
			$tag = 'th';
		}

		parent::__construct($tag, $class, $id);

		return $this;

	}

}