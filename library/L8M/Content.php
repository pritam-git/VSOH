<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Content.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Content.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Content
 *
 *
 */
class L8M_Content
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Contains action-resource that should be saved or loaded to cache.
	 */
	protected static $_activeForActionResource = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Clean up L8M_Content - Cache
	 */
	public static function cleanCache()
	{
		$cache = L8M_Cache::getCache('L8M_Content');
		if ($cache) {
			$cache->clean();
		}
	}

	/**
	 * Save Content to Cache
	 *
	 * @param string $content
	 */
	public static function setContentToCache($content = NULL, $isXmlHttpRequest = FALSE)
	{
		if (self::$_activeForActionResource) {
			$cache = L8M_Cache::getCache('L8M_Content');
			$layout = Zend_Layout::getMvcInstance();

			if ($cache &&
				$layout &&
				self::$_activeForActionResource == $layout->getView()->layout()->calledForResource) {

				$prefix = NULL;
				if ($isXmlHttpRequest) {
					$prefix = 'xmlhttprequest_';
				}
				$cache->save($content, L8M_Cache::getCacheId($prefix . $_SERVER['REQUEST_URI']));
			}
		}
	}

	/**
	 * Return Content from Cache
	 *
	 * @return string
	 */
	public static function getContentFromCache($isXmlHttpRequest = FALSE)
	{
		$returnValue = NULL;

		$cache = L8M_Cache::getCache('L8M_Content');
		if ($cache) {

			$prefix = NULL;
			if ($isXmlHttpRequest) {
				$prefix = 'xmlhttprequest_';
			}
			$cacheValue = $cache->load(L8M_Cache::getCacheId($prefix . $_SERVER['REQUEST_URI']));
			if ($returnValue !== FALSE) {
				$returnValue = $cacheValue;
			}
		}

		return $returnValue;
	}

	/**
	 * Activate save to Cache for action-resource
	 */
	public static function activateCache()
	{
		$layout = Zend_Layout::getMvcInstance();
		if ($layout) {
			self::$_activeForActionResource = $layout->getView()->layout()->calledForResource;
		}
	}
}