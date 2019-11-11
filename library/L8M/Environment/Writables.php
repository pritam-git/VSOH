<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Environment/Writables.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Writables.php 529 2017-04-04 07:40:11Z nm $
 */

/**
 *
 *
 * L8M_Environment_Writables
 *
 *
 */
class L8M_Environment_Writables
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
	 * An L8M_Environment_Writables instance.
	 *
	 * @var L8M_Environment_Writables
	 */
	protected static $_instance = NULL;

	/**
	 * An array of writables.
	 *
	 * @var array
	 */
	protected $_writables = array();

	/**
	 * An array of errors.
	 *
	 * @var array
	 */
	protected $_errors = array();

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Environment_Writables instance.
	 *
	 * @param  string|Zend_Config $config
	 * @return void
	 */
	protected function __construct($config = NULL)
	{
		$this->_init($config);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes L8M_Environment_Writables instance.
	 *
	 * @param  array|Zend_Config $config
	 * @return void
	 */
	protected function _init($config = NULL)
	{

		if ($config instanceof Zend_Config) {
			$config = $config->toArray();
		}
		if (is_array($config) &&
			count($config) > 0) {

			$this->_writables = $config;
			foreach ($this->_writables as $writableFile) {
				$writableFile = $this->_parseFileString($writableFile);
				if (!is_writable($writableFile)) {
					$dirs = explode(DIRECTORY_SEPARATOR, $writableFile);
					if (count($dirs) &&
						strpos($dirs[count($dirs) - 1], '.') == FALSE) {

						if (!mkdir($writableFile)) {
							$this->_errors[] = $writableFile;
						}
					} else {
						$this->_errors[] = $writableFile;
					}
				}
			}
		}
	}

	/**
	 * Parse the string
	 *
	 * @param string $fileName
	 * @return string
	 */
	private function _parseFileString($fileName)
	{
		$fileName = str_replace('BASE_PATH DIRECTORY_SEPARATOR', BASE_PATH . DIRECTORY_SEPARATOR, $fileName);
		$fileName = str_replace('APPLICATION_PATH DIRECTORY_SEPARATOR', APPLICATION_PATH . DIRECTORY_SEPARATOR, $fileName);
		$fileName = str_replace('PUBLIC_PATH DIRECTORY_SEPARATOR', PUBLIC_PATH . DIRECTORY_SEPARATOR, $fileName);
		$fileName = str_replace(' ' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $fileName);
		$fileName = str_replace(DIRECTORY_SEPARATOR . ' ', DIRECTORY_SEPARATOR, $fileName);
		return $fileName;
	}

	/**
	 * Returns L8M_Environment_Writables instance.
	 *
	 * @param string|Zend_Config $config
	 * @return L8M_Environment_Writables
	 */
	public static function getInstance($config = NULL)
	{
		if (self::$_instance === NULL) {
			if (is_string($config) &&
				file_exists($config) &&
				is_file($config)) {

				require_once('Zend' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'Ini.php');
				$config = new Zend_Config_Ini($config, 'writable');
			} else

			if ($config &&
				(!is_array($config) ||
				(!$config instanceof Zend_Config))) {

				require_once('L8M' . DIRECTORY_SEPARATOR . 'Environment' . DIRECTORY_SEPARATOR . 'Exception.php');
				throw new L8M_Environment_Exception('Config needs to be passed as NULL, a string representing the path to a config file or a Zend_Config instance.');
			}
			self::$_instance = new self($config);
		}
		return self::$_instance;
	}

	/**
	 * Returns an array of all files, that have to be writable.
	 *
	 * @return array
	 */
	public function getWritables()
	{
		return $this->_writables;
	}

	/**
	 * Returns an array of all errors.
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * Returns TRUE on error or FALSE if there are no problems
	 *
	 * @return boolean
	 */
	public function hasErrors()
	{
		$returnValue = FALSE;
		if (count($this->_errors) > 0) {
			$returnValue = TRUE;
		}

		return $returnValue;
	}

	/**
	 * Returns an string of all errors as html list.
	 *
	 * @return array
	 */
	public function getErrorsHtml()
	{
		$returnValue = NULL;

		foreach ($this->_errors as $error) {
			$returnValue .= '<li class="exclamation">' . $error . '</li>';
		}

		if ($returnValue) {
			$returnValue = '<p>These files should be writable:</p><ul class="iconized">' . $returnValue . '</ul>';
		}
		return $returnValue;
	}
}