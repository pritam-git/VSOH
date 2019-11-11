<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Cache.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Cache.php 253 2015-02-03 17:18:32Z nm $
 */

/**
 *
 *
 * L8M_Cache
 *
 *
 */
class L8M_Cache
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Some Caches
	 *
	 * @var array of Zend_Cache
	 */
	protected static $_caches = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Creates cache id.
	 *
	 * @param  string       $name
	 * @param  string|array $params
	 * @return string
	 */
	public static function getCacheId($name = NULL, $params = array())
	{
		return preg_replace('/[^a-zA-Z0-9_]/', '_', $name . '_' . implode(',', (array) $params));
	}

	/**
	 * Returns Zend_Cache instance.
	 *
	 * @return Zend_Cache
	 */
	public static function getCache($cacheName = NULL)
	{
		$returnValue = FALSE;

		if ($cacheName !== NULL) {
			if (!array_key_exists($cacheName, self::$_caches)) {
				if (L8M_Config::getOption('l8m.cache') &&
					Zend_Registry::isRegistered('Zend_Cache_Manager') &&
					(NULL != $cacheManager = Zend_Registry::get('Zend_Cache_Manager')) &&
					($cacheManager instanceof Zend_Cache_Manager) &&
					$cacheManager->hasCacheTemplate($cacheName)) {

					self::$_caches[$cacheName] = $cacheManager->getCache($cacheName);

				} else {
					self::$_caches[$cacheName] = FALSE;
				}
			}
			$returnValue = self::$_caches[$cacheName];
		}
		return $returnValue;
	}

	public static function cleanAll()
	{
		if (L8M_Config::getOption('l8m.cache') &&
			Zend_Registry::isRegistered('Zend_Cache_Manager') &&
			(NULL != $cacheManager = Zend_Registry::get('Zend_Cache_Manager')) &&
			$cacheManager instanceof Zend_Cache_Manager) {

			$caches = $cacheManager->getCaches();
			foreach ($caches as $cache) {
				$cache->clean();
			}
		}
	}
}
