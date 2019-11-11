<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/List/Element.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Link.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_List_Element
 *
 *
 */
class Mandala_HTML5_List_Element extends Mandala_HTML5_Element
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
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * constructor of a html5 list element
	 * @param string $class
	 * @param string $id
	 * @return Mandala_HTML5_List_Element
	 */
	public function __construct($class = NULL, $id = NULL) {

		parent::__construct('li', $class, $id);

		return $this;

	}


}