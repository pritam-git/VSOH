<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Dom/Element.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Element.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Dom_Element
 *
 *
 */
class L8M_Dom_Element extends DOMElement
{

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Dom_Element instance.
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  string $namespaceURI
	 * @return void
	 */
	public function __construct  ($name = NULL, $value = NULL, $namespaceURI = NULL)
	{

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Appends a DOMNode instance as a child to the L8M_Dom_Element instance.
	 *
	 * @param  DOMNode $child
	 * @return L8M_Dom_Element
	 */
	public function appendChild($child = NULL)
	{
		if ($child instanceof DOMNode) {
			parent::appendChild($child);
		}
		return $this;
	}

	/**
	 * Sets an attribute of the L8M_Dom_Element.
	 *
	 * @return L8M_Dom_Element
	 */
	public function setAttribute($name = NULL, $value = NULL)
	{
		parent::setAttribute($name, $value);
		return $this;
	}

}