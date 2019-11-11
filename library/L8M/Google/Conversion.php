<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Conversion.php
 * @author     Norbert Marks <nm@l8m.com>
 * @since      09.08.2009 07:40:13
 * @version    $Id: Conversion.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Google_Conversion
 *
 *
 */
class L8M_Google_Conversion
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * URI of google conversion script
	 */
	const URI_CONVERSION_SCRIPT_SOURCE = 'http://www.googleadservices.com/pagead/conversion.js';

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected static $_options = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Google_Conversion instance.
	 *
	 * @return void
	 */
	protected function __construct()
	{

	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets options.
	 *
	 * @param  array|Zend_Config $options
	 * @return L8M_Google_Maps_Api
	 */
	public static function setOptions($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Google_Conversion_Exception('Options need to be specified as an array or a Zend_Config instance.');
		}
		self::$_options = $options;

	}

	/**
	 * Returns options.
	 *
	 * @return array
	 */
	public static function getOptions()
	{
		return self::$_options;
	}

}