<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Request/Http.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Http.php 336 2015-04-22 08:06:09Z nm $
 */

/**
 *
 *
 * L8M_Controller_Request_Http
 *
 *
 */
class L8M_Controller_Request_Http extends Zend_Controller_Request_Http
{

	/**
	 * Retrieve a parameter
	 *
	 * Retrieves a parameter from the instance. Priority is in the order of
	 * userland parameters (see {@link setParam()}), $_GET, $_POST. If a
	 * parameter matching the $key is not found, null is returned.
	 *
	 * If the $key is an alias, the actual key aliased will be used.
	 *
	 * @param mixed $key
	 * @param mixed $default Default value to use if key not found
	 * @param boolean $useAlias Decides wheather to use alias or not
	 * @return mixed
	 */
	public function getParam($key, $default = NULL, $useAlias = TRUE)
	{
		$returnValue = $default;

		if ($useAlias) {
			$alias = $this->getAlias($key);
			if ($alias) {
				$keyName = $alias;
			} else {

				/**
				 * default
				 */
				$keyName = $key;

				/**
				 * check
				 */
				if (class_exists('Default_Model_ParamTranslator', TRUE)) {
					try {
						$paramTranslatorSqlCollection = L8M_Translate_Param::getUparamByParamWithLang($key, L8M_Locale::getLang());

						if ($paramTranslatorSqlCollection->count() == 1) {
							$keyName = $paramTranslatorSqlCollection->getFirst()->uparam;
						}
					} catch (Doctrine_Connection_Exception $exception) {
						/**
						 * @todo maybe do something
						 */
					}
				}
				$this->setAlias($key, $keyName);
			}
		} else {
			$keyName = $key;
		}

		$paramSources = $this->getParamSources();
		if (isset($this->_params[$keyName])) {
			$returnValue = $this->_params[$keyName];
		} else
		if (in_array('_GET', $paramSources) &&
			(isset($_GET[$keyName]))) {

			$returnValue = $_GET[$keyName];
		} else
		if (in_array('_POST', $paramSources) &&
			(isset($_POST[$keyName]))) {

			$returnValue = $_POST[$keyName];
		}

		if (is_string($returnValue) &&
			strlen($returnValue) === 0) {

			$returnValue = $default;
		}

		return $returnValue;
	}

}
