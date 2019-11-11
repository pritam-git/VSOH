<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Debug/Plugin/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Debug_Plugin_Abstract
 *
 *
 */
class L8M_Controller_Plugin_Debug_Plugin_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An identifier.
	 *
	 * @var string
	 */
	protected $_identifier = 'unknown';

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Gets identifier for this plugin.
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

}