<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Builder.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Application_Builder
 *
 *
 */
class L8M_Application_Builder extends L8M_Application_Builder_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of required directories.
	 *
	 * @var array
	 */
	protected $_requiredDirectories = array(
		'application',
		'application/api',
		'application/configs',
		'application/controllers',
		'application/doctrine',
		'application/doctrine/data',
		'application/doctrine/data/fixtures',
		'application/doctrine/data/sql',
		'application/doctrine/migrations',
		'application/doctrine/schema',
		'application/forms',
		'application/layouts',
		'application/layouts/filters',
		'application/layouts/helpers',
		'application/layouts/scripts',
		'application/models',
		'application/modules',
		'application/plugins',
		'application/services',
		'application/translations',
		'application/views',
		'application/views/filters',
		'application/views/helpers',
		'application/views/scripts',
		'data',
		'data/dump',
		'data/locale',
		'data/log',
		'data/media',
		'data/temp',
		'data/temp/cache',
		'data/temp/cache/L8M_Google_Maps_Api',
		'data/temp/cache/L8M_Mobile_Detector_Mobi',
		'data/temp/cache/L8M_Utility_Minify',
		'data/temp/cache/default',
		'data/temp/cache/Doctrine',
		'data/temp/cache/Zend_Cache_Frontend_Page',
		'data/temp/cache/Zend_Date',
		'data/temp/cache/Zend_Db_Table',
		'data/temp/cache/Zend_Locale',
		'data/temp/cache/Zend_Translate',
		'data/temp/index',
		'data/temp/session',
		'data/temp/upload',
		'data/temp/wurfl/FILE_CACHE_PROVIDER',
		'data/temp/wurfl/FILE_PERSISTENCE_PROVIDER',
		'externals',
		'library',
		'public/css',
		'public/css/default/all/',
		'public/css/default/diagnostic',
		'public/css/default/mobile',
		'public/css/default/print',
		'public/css/default/screen',
		'public/img',
		'public/img/default',
		'public/img/default/icon',
		'public/js/',
		'public/js/default/',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Application_Builder_Abstract instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		if (!isset($this->_options['modules']) ||
			count($this->_options['modules']) == 0) {
			throw new L8M_Application_Builder_Exception('Key "modules" needs to be present in options');
		}
	}

	/**
	 * Prepares build.
	 *
	 * @return void
	 */
	protected function _prepare()
	{
		$applicationPath = BASE_PATH;
		$this->_createRequiredDirectories($applicationPath);
	}

	/**
	 * Builds components.
	 *
	 * @return void
	 */
	protected function _buildComponents()
	{

		if (isset($this->_options['modules']) &&
			count($this->_options['modules'])>0) {
			foreach($this->_options['modules'] as $moduleOptions) {

				if (isset($this->_options['doctrine'])) {
					$moduleOptions = array_merge(
						$moduleOptions,
						array(
							'doctrine'=>$this->_options['doctrine'],
						)
					);
				}

				$moduleBuilder = new L8M_Application_Module_Builder();
				$moduleBuilder
					->setOptions($moduleOptions)
					->build()
				;
				$this->addMessages($moduleBuilder->getMessages());
				unset($moduleBuilder);
			}
		}

		$this->addMessage('finished building application', 'accept');
	}

}