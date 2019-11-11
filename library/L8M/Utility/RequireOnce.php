<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/RequireOnce.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: RequireOnce.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Utility
 *
 *
 */
class L8M_Utility_RequireOnce
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * A string that is searched for in order to be commented as to inhibit
	 * require_once'ing the specified file.
	 */
	const PATTERN_REQUIRE_ONCE = '/^\s*require_once\s+\'Zend/m';

	/**
	 * A string with which it will be replaced.
	 */
	const STRING_NOT_REQUIRED_ONCE = '// require_once \'Zend';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */


	/**
	 * An array of files in which the require_once calls should not be
	 * commented, as they are definitely needed.
	 *
	 * @var array
	 */
	protected static $_filesToExcludeInRequireCheck = array(
		'Zend/Application.php',
		'Zend/Config/Ini.php',
		'Zend/Config/Xml.php',
		'Zend/Loader/Autoloader.php',
	);

	/**
	 * Returns an array of paths to files within the specified directory, that
	 * contain classes with require_once calls. This is especially intended for
	 * removal of these statements within the Zend Framework, as it will speed
	 * up processing of the files and is not needed, since we are using the
	 * Autoloader.
	 *
	 * If the second parameter is set to TRUE, the calls are instantly commented.
	 *
	 * @param  string|array $directories
	 * @param  bool   $remove
	 * @return array
	 */
	public static function getFilesWithRequireOnce($directories = NULL, $remove = FALSE)
	{
		if (!$directories) {
			$directories = array(
				'Zend'=>APPLICATION_PATH . DIRECTORY_SEPARATOR
										 . '..'
										 . DIRECTORY_SEPARATOR
										 . 'library'
										 . DIRECTORY_SEPARATOR
										 . 'Zend'
										 . DIRECTORY_SEPARATOR,
				'ZendX'=>APPLICATION_PATH . DIRECTORY_SEPARATOR
										  . '..'
										  . DIRECTORY_SEPARATOR
										  . 'library'
										  . DIRECTORY_SEPARATOR
										  . 'ZendX'
										  . DIRECTORY_SEPARATOR,
			);
		}

		if (is_string($directories)) {
			$directories = array($directories);
		}

		if (!is_array($directories)) {
			throw new L8M_Utility_Exception('Directories need to be specified as a string (a single directory) or an array of strings or NULL. ');
		}

		/**
		 * filesWithRequireOnce
		 */
		$filesWithRequireOnce = array();

		/**
		 * regularExpression
		 */
		$excludedFiles = '('
					   . implode(')|(', self::$_filesToExcludeInRequireCheck)
					   . ')'
		;

		$excludedFiles = str_replace('/', '\\' . DIRECTORY_SEPARATOR, $excludedFiles);

		$regularExpression = '/('
						   . $excludedFiles
						   . ')$/'
		;

		/**
		 * iterate over directories
		 */
		foreach($directories as $directory) {

			/**
			 * files
			 */
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);
			if (count($files)>0) {
				foreach($files as $file) {
					/* @var $file SplFileInfo */
					$fileName = $file->getFilename();
					if (preg_match('/\.php$/i',$fileName) &&
						!preg_match($regularExpression, $file->getPathname()) &&
						self::_hasRequireOnce($file->getPathname(), $remove)) {
						$filesWithRequireOnce[] = $file;
					}
				}
			}
		}

		return $filesWithRequireOnce;

	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */


	/**
	 * Returns TRUE if the specified file contains one or more require_once
	 * calls that can be removed. If the second parameter is set to TRUE, it
	 * will add a comment before the call to make it non-functional.
	 *
	 * @param  string|SplFileInfo $file
	 * @param  bool               $remove
	 * @return bool
	 */
	protected static function _hasRequireOnce($file = NULL, $remove = FALSE)
	{
		if ($file instanceof SplFileInfo) {
			$file = $file->getPathname();
		}
		if (is_string($file) &&
			is_file($file) &&
			is_readable($file)) {

			$contents = file_get_contents($file);
			if (preg_match(self::PATTERN_REQUIRE_ONCE, $contents)) {
				if ($remove === TRUE) {
					$contents = preg_replace(self::PATTERN_REQUIRE_ONCE, self::STRING_NOT_REQUIRED_ONCE, $contents);
					file_put_contents($file, $contents);
				}
				return TRUE;
			}
		}
		return FALSE;
	}

}