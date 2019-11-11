<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Dom/Factory.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Factory.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Dom_Factory
 *
 *
 */
class L8M_Dom_Factory
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A DOMDocument instance which is needed to have a base to create
	 * changeable DOMElement instances from
	 *
	 * @var DOMDocument
	 */
	protected static $_domDocumentInstance = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Dom_Factory instance.
	 *
	 * @return void
	 */
	protected function __construct()
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
	 * Returns DOMDocument instance (Singleton pattern).
	 *
	 * @return DOMDocument
	 */
	protected static function _getDomDocumentInstance()
	{
		if (self::$_domDocumentInstance===NULL) {
			self::$_domDocumentInstance = new DOMDocument('1.0', 'UTF-8');
		}
		return self::$_domDocumentInstance;
	}

	/**
	 * Creates a DOMElement that is writeable.
	 *
	 * @return DOMElement
	 */
	public static function createElement($name = NULL)
	{
		$name = (string) $name;
		if ($name) {
			return self::_getDomDocumentInstance()->createElement($name);
		}
		return NULL;
	}

}