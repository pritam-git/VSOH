<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/Minify.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Minify.php 7 2014-03-11 16:18:40Z nm $
 * @todo       we need to re-enable caching in this class
 */

/**
 *
 *
 * L8M_Utility_Minify
 *
 *
 */
class L8M_Utility_Minify
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * A directory in the public directory in which minified resources are
	 * cached statically.
	 */
	const STATIC_CACHE_DIR = 'min/';

	// const PATTERN_FILES_UNMINIFIED = '/^((\/(css|js)\/)([a-z0-9]+)((\.|_|-|\/)([a-z0-9]+))*(\.(css|js))(,(\/(css|js)\/)([a-z0-9]+)((\.|_|-|\/)([a-z0-9]+))*(\.(css|js)))*)$/';

	// const PATTERN_FILES_MINIFIED = '/^(\/min\/)(([a-z]+)?\.([a-zA-Z0-9\/+]*={0,2})\.(css|js))$/';


	/**
	 * An array of minifiers.
	 *
	 * @var array
	 */
	protected static $_minifiers = array();
	/**
	 *
	 *
	 * Factory Method
	 *
	 *
	 */

	/**
	 * Constructs L8M_Utility_Minify instance.
	 *
	 * @param string $type
	 * @param array|Zend_Config $options
	 */
	public static function factory($type = NULL, $options = array())
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		$minifierClass = 'L8M_Utility_Minify_'
					  . str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($type))));

		/*
		 * Load the adapter class.  This throws an exception
		 * if the specified class cannot be loaded.
		 */
		if (!class_exists($minifierClass)) {
			Zend_Loader::loadClass($minifierClass);
		}
		if (!class_exists($minifierClass)) {
			return NULL;
		}
		/*
		 * Create an instance of the adapter class.
		 * Pass the config to the adapter class constructor.
		 */
		$minifier = new $minifierClass($options);

		if (!($minifier instanceof L8M_Utility_Minify_Abstract)) {
			throw new L8M_Utility_Minify_Exception('Minifier needs to extend L8M_Utility_Minify_Abstract');
		}

		return $minifier;
	}

	/**
	 * Minifies content if a minifier could be created.
	 *
	 * @param string			$type
	 * @param string|array	  $content
	 * @param array|Zend_Config $options
	 */
	public static function minify($type = NULL, $content = NULL, $options = NULL)
	{
		if (!isset(self::$_minifiers[$type])) {
			self::$_minifiers[$type] = self::factory($type, $options);
		}
		$minifier = self::$_minifiers[$type];
		if ($minifier instanceof L8M_Utility_Minify_Abstract) {
			return $minifier->minify($content);
		}
		return NULL;
	}

}