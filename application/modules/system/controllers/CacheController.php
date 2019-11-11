<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/CacheController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: CacheController.php 201 2014-10-14 14:19:03Z nm $
 */

/**
 *
 *
 * System_CacheController
 *
 *
 */
class System_CacheController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Clear action. Clears cache if caching has been enabled.
	 *
	 * @return void
	 */
	public function clearAction()
	{
		/**
		 * cacheNames
		 */
		$cacheNames = array();

		/**
		 * cacheData
		 */
		$cacheData = array(
			'backends'=>0,
			'entries'=>0,
			'deleted'=>0,
		);

		/**
		 * id
		 */
		if ($this->getRequest()->getParam('id', NULL, FALSE) &&
			is_string($this->getRequest()->getParam('id', NULL, FALSE))) {
			/**
			 * cacheNames
			 */
			$cacheNames = array(
				$this->getRequest()->getParam('id', NULL, FALSE)=>TRUE,
			);
		}

		/**
		 * resources options
		 */
		$resourcesOptions = $this->getOption('resources');

		/**
		 * cache manager
		 */
		if (isset($resourcesOptions['cachemanager']) &&
			is_array($resourcesOptions['cachemanager']) &&
			count($resourcesOptions['cachemanager'])>0) {

			/**
			 * cache manager
			 */
			if (Zend_Registry::isRegistered('Zend_Cache_Manager')) {

				$cacheManager = Zend_Registry::get('Zend_Cache_Manager');

				if ($cacheManager instanceof Zend_Cache_Manager) {

					/**
					 * retrieve all cache names,
					 */
					$cacheNames = array_merge(array_fill_keys(array_keys($resourcesOptions['cachemanager']), count($cacheNames) == 0), $cacheNames);

					/**
					 * iterate over caches, clear cachesclear specified cache or collected caches
					 */
					if (count($cacheNames) > 0) {
						foreach($cacheNames as $cacheName=>$clearCache) {
							if ($cacheManager->hasCache($cacheName)) {

								$cache = $cacheManager->getCache($cacheName);

								$cacheEntries = count($cache->getIds());

								if ($clearCache == TRUE) {
									$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
									$cacheData['deleted'] = $cacheEntries - count($cache->getIds());
									$cacheEntries = 0;
								}

								$cacheData['backends']++;
								$cacheData['entries'] = $cacheData['entries'] + $cacheEntries;
							}
						}
					}

				}
			}

		}

		/**
		 * always clear Zend_Loader_PluginLoader cache file if it exists
		 */
		if (Zend_Loader_PluginLoader::getIncludeFileCache() &&
			file_exists(Zend_Loader_PluginLoader::getIncludeFileCache()) &&
			is_writable(Zend_Loader_PluginLoader::getIncludeFileCache())) {
			@unlink(Zend_Loader_PluginLoader::getIncludeFileCache());
		}

		/**
		 * minifyPath
		 */
		$minifyPath = PUBLIC_PATH
					. DIRECTORY_SEPARATOR
					. L8M_Utility_Minify::STATIC_CACHE_DIR
		;

		/**
		 * always clear minified resources
		 */
		$directoryIterator = new DirectoryIterator($minifyPath);
		foreach($directoryIterator as $file) {
			/* @var $file DirectoryIterator */

			$fileName = $file->getFilename();
			if ($file->isFile() &&
				preg_match('/([a-f0-9]{32})\.(js|css)$/', $file->getFilename(), $match)) {
				$deleted = unlink($file->getRealPath());
			}
		}

		/**
		 * no ajax
		 */
		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->view->cacheClearResult = $cacheData['deleted'];

		} else

		/**
		 * ajax
		 */
		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->view->cacheClearResult = $cacheData['entries']
										  . '/'
										  . $cacheData['backends']
			;
		}
	}
}