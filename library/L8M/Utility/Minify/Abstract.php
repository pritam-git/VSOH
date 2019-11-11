<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/Minify/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Utility_Minify_Abstract
 *
 *
 */
abstract class L8M_Utility_Minify_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * The separator to use when concatenating file paths.
	 */
	const MINIFY_URL_FILE_SEPARATOR = ';';

	/**
	 * A regular expression that matches a minifiable resource.
	 */
	const PATTERN_FILE_MINIFIABLE = '/^(?P<resource>\/(css|js)\/([a-z0-9]+)((\.|_|-|\/)[a-z0-9]+)*\.(?P<type>(css|js)))$/';

	/**
	 * A regular expression that matches an URL as created by
	 * self::getMinifyUrl().
	 */
	// const PATTERN_URL_FILES_MINIFIED = '/^((?P<prefix>[a-z0-9]+)\.)?\.(?P<base64UrlEncoded>(([A-Za-z0-9+/]{4})*(|[A-Za-z0-9+/]{2}(%3D%3D|[A-Za-z0-9+/]%3D)))(?P<type>(js|css))$/';

	const PATTERN_URL_FILES_MINIFIED = '/^(?P<staticCacheDir>\/min\/)((?P<prefix>[a-z]+)\.)?(?P<md5Encoded>[a-f0-9]{32})\.(?P<type>css|js)$/';

	// '/^(?P<staticCacheDir>(\/min\/))((?P<prefix>[a-z0-9]+)\.)?\.(?P<base64UrlEncoded>(.*))(?P<type>(js|css))$/';

	const DIRECTORY_SEPARATOR = ',';

	const PATH_SEPARATOR = ';';

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
	protected $_options = NULL;

	/**
	 * An array of minifiable types.
	 *
	 * @var array
	 */
	protected $_minifyTypes = array(
		'css',
		'js',
	);

	/**
	 * A Zend_Cache_Core instance
	 *
	 * @var Zend_Cache_Core
	 */
	protected static $_cache = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs instance.
	 *
	 * @param array|Zend_Config $options
	 */
	public function __construct($options = NULL)
	{
		if ($options) {
			$this->setOptions($options);
		}
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Sets options.
	 *
	 * @param array|Zend_Config $options
	 * @return L8M_Utility_Minify_Abstract
	 */
	public function setOptions($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Utility_Minify_Exception('Options need to be specified as array or a Zend_Config instance.');
		}
		$this->_options = $options;
		return $this;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE if the provided string references a CSS file that we are
	 * going to minify.
	 *
	 * @param  string $file
	 * @return bool
	 */
	public static function isMinifiable($file = NULL)
	{
		if (preg_match(self::PATTERN_FILE_MINIFIABLE, $file, $match)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns TRUE if the specified Url is a url to resources that will be
	 * minified.
	 *
	 * @param  string $url
	 * @return bool
	 */
	public static function isMinifyUrl($url = NULL)
	{
		if (preg_match(self::PATTERN_URL_FILES_MINIFIED, $url, $match)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns a URL that directos either to the minify script, if the file does
	 * not exist yet, which then creates the file.
	 *
	 * @param  string|array  $filePath
	 * @param  string		$prefix
	 * @return string
	 */
	public static function getMinifyUrl($files = NULL, $prefix = NULL, $extension = 'css')
	{
		if (is_string($files)) {
			$files = array($files);
		}
		if (!is_array($files)) {
			throw new L8M_Utility_Minify_Css_Exception('File path needs to be specified as a string or an array.');
		}
		if ($prefix &&
			!is_string($prefix)) {
			throw new L8M_Utility_Minify_Css_Exception('Prefix needs to be specified as a string.');
						}
		if (count($files)==0) {
			return NULL;
		}
		foreach($files as $file) {
			if (!self::isMinifiable($file)) {
				return NULL;
			}
		}
		$key = md5(implode(self::MINIFY_URL_FILE_SEPARATOR, $files));
		$cache = self::_getCache();
		if ($cache) {
			$id = L8M_Cache::getCacheId('L8M_Utility_Minify', $key);
			$cache->save($files, $id);
		}
		$url = '/min/'
			 . ($prefix ? $prefix . '.' : '')
			 . $key
			 . '.'
			 . $extension
		;
		return $url;

	}

	/**
	 * Minifies passed content code with specified options.
	 *
	 * @param  string|array	  $js
	 * @param  array|Zend_Config $options
	 * @return string
	 */
	public function minify($content = array(), $options = array())
	{
		if (is_array($content)) {
			$content = implode(PHP_EOL, $content);
		}
		if (!is_string($content)) {
			throw new L8M_Utility_Minify_Js_Exception('Content needs to be specified as string or an array of strings.');
		}
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!$options ||
			count($options) == 0) {
			$options = $this->_options;
		}

		$minified = $this->_minify($content, $options);
		if ($minified) {
			$minified = $this->_prepare($minified, $options);
		}
		return $minified;
	}

	/**
	 * Minifies contents of specified files with specified options.
	 *
	 * @return string
	 */
	public function minifyFiles($files = array(), $options = array())
	{
		if (!is_array($files)) {
			throw new L8M_Utility_Minify_Abstract_Exception('No files have been specified.');
		}

		$content = array();
		foreach($files as $file) {

			if (!array_key_exists($file, $content) &&
				preg_match(self::PATTERN_FILE_MINIFIABLE, $file)) {

				$filePath = PUBLIC_PATH
						  . DIRECTORY_SEPARATOR
						  . $file
				;
				if (file_exists($filePath)) {
					$content[$file] = file_get_contents($filePath);
				}
			}
		}
		if (count($content)) {
			return $this->minify($content, $options);
		}
		return '';
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Prepends a copyright notice to the minified stuff.
	 *
	 * @param  string $minified
	 * @return string
	 */
	protected function _prepare($minified = NULL, $options = NULL)
	{
		if ($minified &&
			isset($options['copyright']) &&
			is_string($options['copyright'])) {
			$minified = '/* © 2008-'
					  . date('Y')
					  . ' '
					  . $options['copyright']
					  . ' */'
					  . PHP_EOL
					  . $minified
			;
		}
		return $minified;
	}

	protected static function _getCache()
	{
		if (self::$_cache === NULL &&
			Zend_Registry::isRegistered('Zend_Cache_Manager') &&
			(NULL != $cacheManager = Zend_Registry::get('Zend_Cache_Manager')) &&
			($cacheManager instanceof Zend_Cache_Manager) &&
			$cacheManager->hasCacheTemplate('L8M_Utility_Minify')) {

			self::$_cache = $cacheManager->getCache('L8M_Utility_Minify');
		}
		return self::$_cache;
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Minifies contents of specified files with specified options.
	 *
	 * @param string $content
	 * @param array  $options
	 */
	abstract protected function _minify($content = NULL, $options = array());

}