<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/List.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: List.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_List
 *
 *
 */
class Mandala_HTML5_List extends Mandala_HTML5_Element
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
	 * @var array
	 */
	private $_listElements = array();


	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * constructor of a html5 list
	 * @param boolean $unordered
	 * @param string $class
	 * @param string $id
	 * @return Mandala_HTML5_List
	 */
	public function __construct($unordered = TRUE, $class = NULL, $id = NULL) {

		$tagName = 'ul';

		if ($unordered == FALSE) {
			$tagName = 'ol';
		}

		parent::__construct($tagName, $class, $id);

		return $this;

	}

	/**
	 * {@inheritDoc}
	 * @see Mandala_HTML5_Element::__toString()
	 */
	public function __toString() {

		foreach ($this->_listElements as $listElement) {

			$this->append($listElement);
		}

		return parent::__toString();

	}

	/**
	 * add element to list
	 * @param Mandala_HTML5_List_Element
	 *
	 * @return Mandala_HTML5_List
	 */
	public function addChild($listElement) {

		$this->_listElements[] = $listElement;

		return $this;

	}


}