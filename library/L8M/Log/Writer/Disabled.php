<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Log/Writer/Disabled.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Disabled.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Log_Writer_Disabled
 *
 *
 */
class L8M_Log_Writer_Disabled extends Zend_Log_Writer_Abstract
{

	/**
	 * @var array of Zend_Log_Filter_Interface
	 */
	protected $_filters = array();

	/**
	 * Formats the log message before writing.
	 * @var Zend_Log_Formatter_Interface
	 */
	protected $_formatter;

	/**
	 * Create a new instance of Zend_Log_Writer_Firebug
	 *
	 * @param  array|Zend_Config $config
	 * @return Zend_Log_Writer_Firebug
	 * @throws Zend_Log_Exception
	 */
	static public function factory($config)
	{
		return new self();
	}

	/**
	 * Enable or disable the log writer.
	 *
	 * @param boolean $enabled Set to TRUE to enable the log writer
	 * @return boolean The previous value.
	 */
	public function setEnabled($enabled)
	{
		$previous = $this->_enabled;
		$this->_enabled = $enabled;
		return $previous;
	}

	/**
	 * Determine if the log writer is enabled.
	 *
	 * @return boolean Returns TRUE if the log writer is enabled.
	 */
	public function getEnabled()
	{
		return $this->_enabled;
	}

	/**
	 * Add a filter specific to this writer.
	 *
	 * @param  Zend_Log_Filter_Interface  $filter
	 * @return void
	 */
	public function addFilter($filter)
	{
		if (is_integer($filter)) {
			$filter = new Zend_Log_Filter_Priority($filter);
		}

		$this->_filters[] = $filter;
	}

	/**
	 * Log a message to this writer.
	 *
	 * @param  array	 $event  log data event
	 * @return void
	 */
	public function write($event)
	{
		foreach ($this->_filters as $filter) {
			if (! $filter->accept($event)) {
				return;
			}
		}

		// exception occurs on error
		$this->_write($event);
	}

	/**
	 * Set a new formatter for this writer
	 *
	 * @param  Zend_Log_Formatter_Interface $formatter
	 * @return Zend_Log_Writer_Abstract
	 */
	public function setFormatter(Zend_Log_Formatter_Interface $formatter)
	{
		$this->_formatter = $formatter;
		return $this;
	}

	/**
	 * Perform shutdown activites such as closing open resources
	 *
	 * @return void
	 */
	public function shutdown()
	{}

	/**
	 * Write a message to the log.
	 *
	 * @param  array  $event  log data event
	 * @return void
	 */
	protected function _write($event)
	{
	}

	/**
	 * Validate and optionally convert the config to array
	 *
	 * @param  array|Zend_Config $config Zend_Config or Array
	 * @return array
	 * @throws Zend_Log_Exception
	 */
	static protected function _parseConfig($config)
	{
		if ($config instanceof Zend_Config) {
			$config = $config->toArray();
		}

		if (!is_array($config)) {
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'Log' . DIRECTORY_SEPARATOR . 'Exception.php';
			throw new Zend_Log_Exception(
				'Configuration must be an array or instance of Zend_Config'
			);
		}

		return $config;
	}
}