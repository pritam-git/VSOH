<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/HeadLinkPrefetch.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HeadLinkPrefetch.php 88 2014-05-18 13:15:00Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_HeadLinkPrefetch
 *
 *
 */
class L8M_View_Helper_HeadLinkPrefetch extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array with options.
	 *
	 * @var array
	 */
	protected static $_options = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns Headlink-Meta for prefetch-list.
	 *
	 * @return string
	 */
	public function headLinkPrefetch()
	{
		$returnValue = NULL;

		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT != L8M_Environment::getInstance()->getEnvironment() &&
			count(self::$_options) > 0) {

			foreach (self::$_options as $url) {
				$addToPrefetch = TRUE;
				if (substr($url, 0, 1) == '/' &&
					$url == $_SERVER['REQUEST_URI']) {

					$addToPrefetch = FALSE;
				}
				if ($addToPrefetch) {
					$returnValue .= '<link rel="prefetch" href="' . $url . '">' . PHP_EOL;
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Add Url to prefetch-list.
	 *
	 * @param $url
	 * @return void
	 */
	public static function addUrl($url = NULL)
	{
		if (is_string($url) &&
			strlen($url) > 0) {

			$addToList = TRUE;
			foreach (self::$_options as $key => $value) {
				if ($value == $url) {
					$addToList = FALSE;
				}
			}
			if ($addToList) {
				self::$_options[] = $url;
			}
		}
	}

	/**
	 * Remove Url from prefetch-list.
	 *
	 * @param $url
	 * @return void
	 */
	public static function removeUrl($url = NULL)
	{
		if (is_string($url)) {
			foreach (self::$_options as $key => $value) {
				if ($value == $url) {
					unset(self::$_options[$key]);
				}
			}
		}
	}
}