<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/XDOM/Element.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Element.php 181 2014-09-11 08:26:23Z nm $
 */

/**
 *
 *
 * L8M_XDOM_Element
 *
 *
 */
class L8M_XDOM_Element extends DOMElement
{

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */
	function __construct($name, $value = null, $namespaceURI = null) {
		parent::__construct($name, null, $namespaceURI);
	}
}