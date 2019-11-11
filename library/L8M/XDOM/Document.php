<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/XDOM/Document.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Document.php 181 2014-09-11 08:26:23Z nm $
 */

/**
 *
 *
 * L8M_XDOM_Document
 *
 *
 */
class L8M_XDOM_Document extends DOMDocument
{

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */
	function __construct($version = null, $encoding = null) {
		parent::__construct($version, $encoding);
		$this->registerNodeClass('DOMElement', 'L8M_XDOM_Element');
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	function createElement($name, $value = null, $namespaceURI = null) {
		$element = new L8M_XDOM_Element($name, $value, $namespaceURI);
		$element = $this->importNode($element);
		if (!empty($value)) {
			$element->appendChild(new DOMText($value));
		}
		return $element;
	}
}