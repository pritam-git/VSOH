<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/Bootstrap/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_Bootstrap_Abstract
 *
 *
 */
abstract class Mandala_Bootstrap_Abstract extends Mandala_HTML5_Element
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * prepare content for output
	 *
	 * @return void
	 */
	abstract protected function _prepareContent();

	/**
	 * magic method toString
	 * forces bootstrap elements to prepare their content for output
	 *
	 * @return string
	 */
	public function __toString() {

		$this->_prepareContent();
		return parent::__toString();

	}


}