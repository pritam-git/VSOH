<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Dom/Document.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Document.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Dom_Document
 *
 *
 */
class L8M_Dom_Document extends DOMDocument
{

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs an L8M_Dom_Document instance
	 *
	 * @param  string $version
	 * @param  string $encoding
	 * @return void
	 */
	public function __construct($version = '1.0', $encoding = 'UTF-8')
	{
		$this->preserveWhiteSpace = FALSE;
		$this->formatOutput = FALSE;
	}

	/**
	 *
	 *
	 * Magic Methods
	 *
	 *
	 */

	/**
	 * Returns a string representation of an L8M_Dom_Document instance.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->saveXML();
	}

}