<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Cache/Zend.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Zend.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 *
 *
 */
class L8M_Doctrine_Cache_Zend extends Doctrine_Cache_Driver
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The key used to store the index of cache keys in this cache driver instance
	 *
	 * @var string
	 */
	protected $_cacheKeyIndexKey = 'CacheKeys';


	/**
	 * An array of options that can be set.
	 *
	 * @var array $_options
	 */
	protected $_options = array('cache'=>NULL,
								'prefix'=>'L8M_Doctrine_Cache_Zend_',
								'frontend'=>NULL,
								'backend'=>NULL);

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Doctrine_Cache_Zend instance. In passed options, frontend
	 * and backend options can be passed, and if cache is not specified, a cache
	 * instance will be created.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (isset($options['cache']) &&
			!($options['cache'] instanceof Zend_Cache_Core)) {
			throw new L8M_Doctrine_Cache_Zend_Exception('If cache is specified in options, it needs to subclass Zend_Cache_Core.');
		}
		if (!isset($options['cache'])) {
			if (!isset($options['frontend']) ||
			 	!isset($options['backend'])) {
				throw new L8M_Doctrine_Cache_Zend_Exception('If cache is not, frontend and backend options need to be specified in options.');
			}
			if (isset($options['lifetime'])) {
				$options['frontend']['options']['lifetime'] = $options['lifetime'];
			}
			try {
				$options['cache'] = Zend_Cache::factory($options['frontend']['name'],
														$options['backend']['name'],
														$options['frontend']['options'],
														$options['backend']['options']);
			} catch (Zend_Cache_Exception $exception) {
				throw new L8M_Doctrine_Cache_Zend_Exception('Zend_Cache could not create cache instance from specified options.');
			}
		}
		parent::__construct($options);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Save a cache key in the index of cache keys.
	 *
	 * We override this method as we need to unserialize and serialize after
	 * fetching and before saving keys.
	 *
	 * @param  string $key
	 * @return bool
	 */
	protected function _saveKey($key)
	{
		$keys = unserialize($this->fetch($this->_cacheKeyIndexKey));
		$keys[] = $key;
		$keys = serialize($keys);

		return $this->save($this->_cacheKeyIndexKey, $keys, NULL, FALSE);
	}

	/**
	 * Delete a cache key from the index of cache keys.
	 *
	 * We override this method as we need to unserialize and serialize after
	 * fetching and before saving keys.
	 *
	 * @param string $key
	 * @return boolean True if successful and false if something went wrong.
	 */
	public function _deleteKey($key)
	{
		$keys = unserialize($this->fetch($this->_cacheKeyIndexKey));
		$key = array_search($key, $keys);
		if ($key !== FALSE) {
			unset($keys[$key]);
			$keys = serialize($keys);
			return $this->save($this->_cacheKeyIndexKey, $keys, NULL, FALSE);
		}

		return FALSE;
	}

	/**
	 * Fetches a cache record with the specified id from the cache.
	 *
	 * As observed, there is an issued with fetching and saving of keys, as
	 * within the parent class' methods _deleteKey() and _saveKey() the methods
	 * fetch() and save() are called and fromn the first it is expected that an
	 * array is returned and from the latter it is expected to take in an array,
	 * too. Thus, serialization needs to occur depending on the passed id. As in
	 * fetch() and save() the method _getKey() is called, the prefix is
	 * prepended.
	 *
	 * @param  string  $id
	 * @param  boolean $testCacheValidity
	 * @return mixed
	 */
	protected function _doFetch($id, $testCacheValidity = true)
	{
		$cache = $this->_getCache();
		if ($cache) {
			return $cache->load($this->_getKey($id), $testCacheValidity);
		}
		return FALSE;
	}

	/**
	 * Test if a cache record exists for the passed id
	 *
	 * @param string $id cache id
	 * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
	 */
	protected function _doContains($id)
	{
		$cache = $this->_getCache();
		if ($cache) {
			return $cache->test($this->_getKey($id));
		}
		return FALSE;
	}

	/**
	 * Save a cache record directly. This method is implemented by the cache
	 * drivers and used in Doctrine_Cache_Driver::save()
	 *
	 * @param string $id		cache id
	 * @param string $data	  data to cache
	 * @param int $lifeTime	 if != false, set a specific lifetime for this cache record (null => infinite lifeTime)
	 * @return boolean true if no problem
	 */
	protected function _doSave($id, $data, $lifeTime = false)
	{
		$cache = $this->_getCache();
		if ($cache) {
			return $cache->save($data, $this->_getKey($id), array(), $lifeTime);
		}
		return FALSE;
	}

	/**
	 * Remove a cache record directly. This method is implemented by the cache
	 * drivers and used in Doctrine_Cache_Driver::delete()
	 *
	 * @param string $id cache id
	 * @return boolean true if no problem
	 */
	protected function _doDelete($id)
	{
		$cache = $this->_getCache();
		if ($cache) {
			return $cache->remove($this->_getKey($id));
		}
		return FALSE;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns cache instance, it is has been specified in options, or NULL.
	 *
	 * @return Zend_Cache_Core
	 */
	protected function _getCache()
	{
		if (isset($this->_options['cache'])) {
			return $this->_options['cache'];
		}
		return NULL;
	}
	/**
	 *
	 */
	protected function _getCacheKeys() {
		return unserialize($this->fetch($this->_cacheKeyIndexKey));
	}
}