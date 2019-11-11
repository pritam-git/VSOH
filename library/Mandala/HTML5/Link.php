<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/Link.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Link.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_Link
 *
 *
 */
class Mandala_HTML5_Link extends Mandala_HTML5_Element
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
	 * constructor of a html5 link
	 * @param string $href
	 * @param string $id
	 * @param string $class
	 * @return Mandala_HTML5_Link
	 */
	public function __construct($href, $class = NULL, $id = NULL) {

		parent::__construct('a', $class, $id);
		$this->setAttr('href', $href);

		return $this;

	}


}