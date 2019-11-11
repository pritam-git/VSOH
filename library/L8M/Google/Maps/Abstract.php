<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Maps/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Google_Maps_Abstract
 *
 *
 */
abstract class L8M_Google_Maps_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A Zend_Cache instance
	 *
	 * @var Zend_Cache
	 */
	protected static $_cache = NULL;

	/**
	 * The base name of instances of this type.
	 *
	 * @var string
	 */
	protected $_instanceBaseName = 'unspecified';

	/**
	 * An integer representing the number of instances created.
	 *
	 * @var int
	 */
	protected static $_instanceCount = array();

	/**
	 * A name for the instance.
	 *
	 * @var string
	 */
	protected $_instanceName = NULL;

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected $_options = array();

	/**
	 * An array of default options.
	 *
	 * @var array
	 */
	protected $_defaultOptions = array();

	/**
	 * An array of unsettable options.
	 *
	 * @var array
	 */
	protected $_unsettableOptions = array('options',
										  'defaultOptions',
										  'config');

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

 	/**
	 * Sets option. Filters out unsettable options.
	 *
	 * @param  array $options
	 * @return L8M_Google_Maps_Abstract
	 */
	public function setOption($name = NULL, $value = NULL)
	{
		if (is_string($name) &&
			count(array_diff_key(array($name=>$value), $this->getUnsettableOptions()))>0) {
			$setterFunction = 'set' . ucfirst($name);
			if (method_exists($this, $setterFunction)) {
				$this->{$setterFunction}($value);
			}
		}
		return $this;
	}

	/**
	 * Sets options. Filters out unsettable options.
	 *
	 * @param  array $options
	 * @return L8M_Google_Maps_Abstract
	 */
	public function setOptions($options = array())
	{
		$options = (array) $options;
		if (is_array($options)) {
			$options = array_diff_key($this->getUnsettableOptions(), $options);
			if (count($options)>0) {
				foreach($options as $name=>$value) {
					$this->setOption($name, $value);
				}
			}
		}
		return $this;
	}

	/**
	 * Sets the name of the Javascript variable.
	 *
	 * @param string $name
	 * @return L8M_Google_Maps_Abstract
	 */
	public function setInstanceName($name = NULL)
	{
		$this->_instanceName = (string) $name;
		return $this;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
	 * Returns default L8M_Google_Maps_Abstract options as an array.
	 *
	 * @return array
	 */
	public function getDefaultOptions()
	{
		return $this->_defaultOptions;
	}

	 /**
	 * Returns L8M_Google_Maps_Abstract options as an array.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		if (count($this->_options)>0) {
			$options = array();
			foreach($this->_options as $name) {
				$getterFunction = 'get' . ucfirst($name);
				if (method_exists($this, $getterFunction)) {
					$options[$name] = $this->{$getterFunction}();
				}
			}
			return array_merge($this->getDefaultOptions(), $options);
		}
		return $this->getDefaultOptions();
	}

	/**
	 * Returns L8M_Google_Maps_Abstract options as JavaScript array.
	 *
	 * @return string
	 */
	public function getOptionsAsJavascript()
	{
		$options = $this->getDefaultOptions();
		if (count($this->getOptions())>0) {
			$options = array_merge($options, $this->getOptions());
		}
		$jsOptions = array();
		foreach($options as $name=>$value) {
			if (is_object($value)) {
			   $value = $value->getInstanceName();
			} else
			if (is_string($value)) {
				$value = '"' . $value . '"';
			} else
			if ($value===FALSE) {
				$value = 'false';
			} else
			if ($value===TRUE) {
				$value = 'true';
			}
			if ($value!==NULL) {
				$jsOptions[] = $name . ': ' . $value;
			}
		}
		return implode(', ', $jsOptions);
	}

	/**
	 * Returns unsettable options.
	 *
	 * @return array
	 */
	public function getUnsettableOptions()
	{
		return $this->_unsettableOptions;
	}

	/**
	 * Returns the name of the Javascript variable that has been constructed and
	 * initialized.
	 *
	 * @return string
	 */
	public function getInstanceName()
	{
		return $this->_instanceName;
	}

	/**
	 * Returns Zend_Cache instance if caching is enabled and a Zend_Cache
	 * instance could be retrieved from Zend_Registry.
	 *
	 * @return Zend_Cache
	 */
	public static function getCache()
	{

		if (self::$_cache === NULL) {
			if ((FALSE != $config = Zend_Registry::get('Zend_Config')) &&
				$config->get('cache') &&
				$config->cache->get('enabled') &&
				$config->get('google') &&
				$config->google->get('maps') &&
				$config->google->maps->get('cache') &&
				$config->google->maps->cache->get('enabled')) {
				self::$_cache = Zend_Cache::factory($config->google->maps->cache->frontend->name,
													$config->cache->backend->name,
													$config->google->maps->cache->frontend->options->toArray(),
													$config->cache->backend->options->toArray());
			} else {
				self::$_cache = FALSE;
			}
		}
		return self::$_cache;
	}

	/**
	 * Returns the count of generated instances for the specified instance base
	 * name or, if NULL is passed, an array of instance counts
	 *
	 * @param  string $instanceBaseName
	 * @return int|array|NULL
	 */
	public function getInstanceCount($instanceBaseName = NULL)
	{
		if ($instanceBaseName==NULL) {
			return self::$_instanceCount;
		}
		if (is_string($instanceBaseName) &&
			array_key_exists($instanceBaseName, self::$_instanceCount)) {
			return self::$_instanceCount[$instanceBaseName];
		}
		return NULL;
	}

	/**
	 *
	 *
	 * Abstract Methods
	 *
	 *
	 */

	/**
	 * Creates and initializes a Javascript variable reflected by this class.
	 * This function should be overridden by descendants, but be called using
	 * parent::createAndInitialize(), as we increase the count of generated
	 * instances of a certain type here, from which the instance name will be
	 * generated.
	 *
	 * @return string
	 */
	public function createAndInitialize()
	{
		if (!array_key_exists($this->_instanceBaseName, self::$_instanceCount)) {
			self::$_instanceCount[$this->_instanceBaseName] = 1;
		} else {
			self::$_instanceCount[$this->_instanceBaseName] = self::$_instanceCount[$this->_instanceBaseName] + 1;
		}
		$this->_instanceName = $this->_instanceBaseName . self::$_instanceCount[$this->_instanceBaseName];
		return NULL;
	}

	/**
	 *
	 *
	 * Magic Methods
	 *
	 *
	 */

	/**
	 * Converts instance to string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->createAndInitialize();
	}

}