<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Log.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Log.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Log
 *
 *
 */
class L8M_Log
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A Zend_Log instance
	 *
	 * @var Zend_Log
	 */
	protected static $_log = NULL;

	/**
	 * Needed for FireBug
	 *
	 * @var Zend_Wildfire_Channel_HttpHeaders
	 */
	protected static $_logChannel = NULL;

	/**
	 *
	 *
	 * Class Getters
	 *
	 *
	 */

	/**
	 * Returns Zend_Log instance
	 *
	 * @return Zend_Log
	 */

	/**
	 * Returns Zend_Log instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return Zend_Log
	 */
	public static function getInstance($options = NULL)
	{
		if (self::$_log == NULL) {
			self::init($options);
		}
		return self::$_log;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes Zend_Log instance.
	 *
	 * @param  array|Zend_Config $options
	 * @return bool
	 */
	protected static function init($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Log_Exception('Config needs to be passed as an array or a Zend_Config instance');
		}
		/**
		 * log
		 */
		self::$_log = new Zend_Log();
		/**
		 * log priorities (these are added as they are needed for firebug
		 *
		 * @todo consider moving adding elsewhere
		 */
		self::$_log->addPriority('TABLE', 8);
		self::$_log->addPriority('DUMP', 9);
		self::$_log->addPriority('TRACE', 10);
		self::$_log->addPriority('EXCEPTION', 11);

		/**
		 * do we have a log writer? (by default no)
		 */
		$hasWriter = FALSE;

		/**
		 * file logging enabled and file logging options present
		 *
		 * @todo add fallback options if none are present
		 */
		if (isset($options['file']) &&
			isset($options['file']['enabled']) &&
			$options['file']['enabled'] &&
			isset($options['file']['dir']) &&
			$options['file']['dir'] &&
			isset($options['file']['datePrefix']) &&
			$options['file']['datePrefix'] &&
			isset($options['file']['extension']) &&
			$options['file']['extension']) {

			/**
			 * we have a log writer
			 */
			$hasWriter = TRUE;

			/**
			 * logFilePath
			 */
			$logFilePath = $options['file']['dir'] . DIRECTORY_SEPARATOR . date($options['file']['datePrefix']) . '.' .
																		   $_SERVER['SERVER_NAME'] . '.' .
																		   $options['file']['extension'];
			/**
			 * stream writer
			 */
			$zendLogWriterStream = new Zend_Log_Writer_Stream($logFilePath);
			/**
			 * formatter
			 */
			$zendLogFormatterStream = new Zend_Log_Formatter_Simple('%timestamp% ' . L8M_Library::expandIP($_SERVER['REMOTE_ADDR']) . ' %priorityName% (%priority%): %message%' . PHP_EOL);
			$zendLogWriterStream->setFormatter($zendLogFormatterStream);
			/**
			 * filter (do not show anything below DEBUG level)
			 */
			$zendLogWriterStream->addFilter(new Zend_Log_Filter_Priority(Zend_Log::DEBUG, '<'));
			self::$_log->addWriter($zendLogWriterStream);
		}
		/**
		 * we are in an development environment and firebug logging is enabled
		 * and firebug logging options are present?
		 */
		if ((Zend_Registry::get('environment') === L8M_Environment::ENVIRONMENT_DEVELOPMENT) &&
			isset($options['firebug']) &&
			isset($options['firebug']['enabled']) &&
			$options['firebug']['enabled'] &&
			isset($options['firebug']['writer']) &&
			$options['firebug']['writer'] &&
			isset($options['firebug']['writer']['class']) &&
			$options['firebug']['writer']['class']) {

			/**
			 * we have a log writer
			 */
			$hasWriter = TRUE;

			/**
			 * firebug writer
			 */
			$zendLogWriterFirebug = new $options['firebug']['writer']['class'];

			/**
			 * priority styles
			 */
			$zendLogWriterFirebug->setPriorityStyle(8, 'TABLE');
			$zendLogWriterFirebug->setPriorityStyle(9, 'DUMP');
			$zendLogWriterFirebug->setPriorityStyle(10, 'TRACE');
			$zendLogWriterFirebug->setPriorityStyle(11, 'EXCEPTION');

			/**
			 * add filter
			 *
			 * @todo solve issue with nested exceptions (Doctrine)
			 */
			$zendLogWriterFirebug->addFilter(new Zend_Log_Filter_Priority(11, '<'));
			/**
			 * add L8M_Log_Writer_Firebug instance to Zend_Log instance
			 */
			self::$_log->addWriter($zendLogWriterFirebug);

			self::$_logChannel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
			self::$_logChannel->setRequest(new Zend_Controller_Request_Http());
			self::$_logChannel->setResponse(new Zend_Controller_Response_Http());
			ob_start();
		}

		/**
		 * check log writer
		 */
		if ($hasWriter == FALSE) {

			$disabledLogWriter = new L8M_Log_Writer_Disabled();
			self::$_log->addWriter($disabledLogWriter);
		}

		/**
		 * @todo
		 */
		self::info('Log: Logging started.');
		return TRUE;
	}

	/**
	 * Logs table
	 *
	 * @param  string $tableLabel
	 * @param  mixed  $tableData
	 * @return void
	 */
	public static function table ($tableLabel = NULL, $tableData = NULL)
	{
		self::getInstance()->table(array($tableLabel , $tableData));
	}

	/**
	 * Dumps object
	 *
	 * @param  string $dumpLabel
	 * @param  mixed  $dumpObject
	 * @return void
	 */
	public static function dump ($dumpLabel = NULL, $dumpObject = NULL)
	{
		if (func_num_args()==1) {
			$dumpObject = $dumpLabel;
			$dumpLabel = 'n/a';
		}
		self::getInstance()->table(array($dumpLabel , $dumpObject));
	}

	/**
	 * Logs emergency message
	 *
	 * @param  mixed $emergMessage
	 * @return void
	 */
	public static function emerg ($emergMessage = NULL)
	{
		self::getInstance()->emerg($emergMessage);
	}

	/**
	 * Logs info message
	 *
	 * @param  mixed $infoMessage
	 * @return void
	 */
	public static function info ($infoMessage = NULL)
	{
		self::getInstance()->info($infoMessage);
	}

	/**
	 * Logs alert message
	 *
	 * @param  mixed $alertMessage
	 * @return void
	 */
	public static function alert ($alertMessage = NULL)
	{
		self::getInstance()->alert($alertMessage);
	}

	/**
	 * Logs critical message
	 *
	 * @param  mixed $critMessage
	 * @return void
	 */
	public static function crit ($critMessage = NULL)
	{
		self::getInstance()->crit($critMessage);
	}

	/**
	 * Logs error message
	 *
	 * @param  mixed $errMessage
	 * @return void
	 */
	public static function err ($errMessage = NULL)
	{
		self::getInstance()->err($errMessage);
	}

	/**
	 * Logs warning message
	 *
	 * @param  mixed $warnMessage
	 * @return void
	 */
	public static function warn ($warnMessage = NULL)
	{
		self::getInstance()->warn($warnMessage);
	}

	/**
	 * Logs notice message
	 *
	 * @param  mixed $noticeMessage
	 * @return void
	 */
	public static function notice ($noticeMessage = NULL)
	{
		self::getInstance()->notice($noticeMessage);
	}

	/**
	 * Logs debug message
	 *
	 * @param  mixed $debugMessage
	 * @return void
	 */
	public static function debug ($debugMessage = NULL)
	{
		self::getInstance()->info($debugMessage);
	}

	/**
	 * Logs trace message
	 *
	 * @param  mixed $traceLabel
	 * @return void
	 */
	public static function trace ($traceLabel = NULL)
	{
		self::getInstance()->trace($traceLabel);
	}

	/**
	 * Logs exception
	 *
	 * @param  Exception $exception
	 * @return void
	 */
	public static function exception ($exception = NULL)
	{
		self::getInstance()->exception($exception);
	}

}