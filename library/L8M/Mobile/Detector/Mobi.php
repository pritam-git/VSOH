<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mobile/Detector/Mobi.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Mobi.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mobile_Detector_Mobi
 *
 *
 */
class L8M_Mobile_Detector_Mobi extends L8M_Mobile_Detector_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

    /**
     * A Zend_Cache instance used for caching the DeviceAtlas tree.
     *
     * @var Zend_Cache
     */
    protected static $_cache = NULL;

	/**
	 * An array with DeviceAtlas data.
	 *
	 * @var array
	 */
	protected static $_deviceAtlasTree = NULL;

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_Mobile_Detector_Mobi instance.
	 *
	 * @return void
	 */
	public function init()
	{
		$tree = $this->_getTree();

		/**
		 * prevent error notice
		 */
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
		} else {
			$userAgent = NULL;
		}

		$this->_properties = Mobi_Mtld_DA_Api::getProperties(
			$tree,
			$userAgent
		);
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
     * @param  array|Zend_Config $options
     * @return L8M_Mobile_Detector_Mobi
     */
    public function setOptions($options = NULL)
    {
    	/**
    	 * options
    	 */
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            throw new L8M_Mobile_Detector_Mobi_Exception('Options need to be specified as an array or a Zend_Config instance.');
        }

        /**
         * path to DeviceAtlas resources
         */
        if (!isset($options['resource']['path'])) {
        	throw new L8M_Mobile_Detector_Mobi_Exception('Path to DeviceAtlas resource needs to be specified.');
        }

    	/**
         * name of DeviceAtlas resource file
         */
        if (!isset($options['resource']['file'])) {
        	throw new L8M_Mobile_Detector_Mobi_Exception('Name of DeviceAtlas resource file needs to be specified.');
        }

        $this->_options = $options;

        return $this;
    }

	/**
	 * Returns TRUE if it is a mobile device.
	 *
	 * @return bool
	 */
	public function isMobileDevice()
	{

		if (isset($this->_properties['mobileDevice']) &&
			$this->_properties['mobileDevice'] == TRUE) {
			return TRUE;
		}

		return FALSE;

	}

	/**
	 * Returns device name.
	 *
	 * @return string
	 */
	public function getDeviceName()
	{

		$deviceName = $this->_properties['_matched']
					. $this->_properties['_unmatched']
		;

		return $deviceName;

	}

 /**
     *
     *
     * Helper Methods
     *
     *
     */

	/**
	 * Returns Zend_Cache instance if there is one present.
	 *
	 * @return Zend_Cache
	 */
	protected function _getCache()
	{
		/**
		 * @todo needs to be adjusted for use with Zend_Cache_Manager
		 */
		return self::$_cache;

	}

	/**
	 * Returns array with DeviceAtlas data.
	 *
	 * @return array
	 */
	protected function _getTree()
	{
		if (self::$_deviceAtlasTree === NULL) {

			/**
			 * caching . . . ?
			 */
			$cache = $this->_getCache();
			if ($cache) {
			    $cacheId = L8M_Cache::getCacheId('L8M_Mobile_Detector_Mobi', array($this->_options['resource']['file']));
			    if (!$tree = $cache->load($cacheId)) {
		            $tree = Mobi_Mtld_DA_Api::getTreeFromFile($this->_options['resource']['path'] . $this->_options['resource']['file']);
		            $cache->save($tree);
		        }
		    } else {
		        $tree = Mobi_Mtld_DA_Api::getTreeFromFile($this->_options['resource']['path'] . $this->_options['resource']['file']);
		    }

		    self::$_deviceAtlasTree = $tree;
		}

		return self::$_deviceAtlasTree;
	}

}
