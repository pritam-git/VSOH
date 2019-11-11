<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Config.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Config.php 378 2015-07-08 10:16:41Z nm $
 */

/**
 *
 *
 * L8M_Config
 *
 *
 */
class L8M_Config
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
	private static $_emulateCompatibleKeys = array(
		'locale.backend.default'=>'de',
		'locale.backend.supported'=>array(
			'de',
			'en',
		),
		'locale.backend.modules'=>array(
			'author',
			'admin',
			'system',
			'system-model-list',
		),
		'locale.backend.allowMultiTabResource'=>array(
			'author.translator.create',
			'author.translator.edit',
			'admin.translator.create',
			'admin.translator.edit',
			'admin.translator-resource.create',
			'admin.translator-resource.edit',
			'admin.translator-param.create',
			'admin.translator-param.edit',
			'system.translator.create',
			'system.translator.edit',
			'system.translator-model-list.create',
			'system.translator-model-list.edit',
			'system.translator-model-column.create',
			'system.translator-model-column.edit',
			'system.translator-resource.create',
			'system.translator-resource.edit',
			'system.translator-param.create',
			'system.translator-param.edit',
			'system.admin-boxes-action.create',
			'system.admin-boxes-action.edit',
			'system.admin-boxes.create',
			'system.admin-boxes.edit',
		),
	);

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */


	/**
	 * get option
	 *
	 * @return  string
	 */
	public static function getOption($key = NULL)
	{
		$booleanKeys = array(
			'check',
			'disable',
			'enable',
			'enabled',
			'register',
		);

		if (!is_string($key)) {
			throw new L8M_Controller_Action_Exception('Key needs to be specified as a string.');
		}

		$generalKey = $key;
		$keys = explode('.', $key);

		if (is_array($keys) &&
			count($keys) >= 1) {

			$key = $keys[0];
		}

		$option = NULL;
		$zendConfig = Zend_Registry::get('Zend_Config');

		$defaultOption = $zendConfig->get($key);
		if (is_array($defaultOption)) {
			$option = $defaultOption;
		} else
		if ($defaultOption) {
			$option = $defaultOption->toArray();
		}

		if ($option &&
			is_array($option)) {

			if (is_array($keys) &&
				count($keys) >= 2) {

				for ($i = 1; $i < count($keys); $i++) {
					if (is_array($option) &&
						isset($option[$keys[$i]])) {

						$option = $option[$keys[$i]];

						/**
						 * boolean values
						 */
						if (!is_array($option) &&
							in_array($keys[$i], $booleanKeys)) {

							$option = (boolean) $option;
						}
					} else {
						$option = NULL;
					}
				}
			}
		}

		/**
		 * check and emulate new keys to old application.ini
		 */
		$option = self::_checkAndEmulateCompatibleKeys($option, $generalKey);

		/**
		 * return option
		 */
		return $option;
	}

	/**
	 * Fix missing parts of older application.ini.
	 *
	 * @param string $value
	 * @param string $key
	 * @return multitype:string multitype:string
	 */
	private static function _checkAndEmulateCompatibleKeys($value, $key) {
		if ($value == NULL &&
			array_key_exists($key, self::$_emulateCompatibleKeys)) {

			$value = self::$_emulateCompatibleKeys[$key];
		}

		return $value;
	}
}