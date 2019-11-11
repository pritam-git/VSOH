<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/DirectorySeparator.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: DirectorySeparator.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Utility_DirectorySeparator
 *
 *
 */
class L8M_Utility_DirectorySeparator
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * A string that is searched for in order to get right DIRECTORY_SEPARATOR.
	 */
	const PATTERN_REQUIRE_ONCE = '/^\s*require_once\s+\'Zend(.*)\.php/m';
	const PATTERN_REQUIRE_ONCE2 = '/^\s*require_once\s+\"Zend(.*)\.php\"/m';
	const PATTERN_REQUIRE_ONCE3 = '/^\s*require_once\(\'Zend(.*)\.php/m';
	const PATTERN_INCLUDE_ONCE = '/^\s*include_once\s+\'Zend(.*)\.php/m';
	const PATTERN_INCLUDE_ONCE2 = '/^\s*include_once\s+\"Zend(.*)\.php\"/m';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */


	/**
	 * An array of files in which the DIRECORY_SEPARATOR should not be the right constant.
	 *
	 * @var array
	 */
	protected static $_filesToExcludeInRequireCheck = array(
//		'Zend/Application.php',
//		'Zend/Config/Ini.php',
//		'Zend/Config/Xml.php',
//		'Zend/Loader/Autoloader.php',
	);

	/**
	 * Returns an array of paths to files within the specified directory, that
	 * contain classes with wrong DIRECORY_SEPARATOR constants.
	 *
	 * If the second parameter is set to TRUE, the wrong directory separators will be replaced by DIRECORY_SEPARATOR..
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
					if (count(self::$_filesToExcludeInRequireCheck)) {
						if (preg_match('/\.php$/i',$fileName) &&
							!preg_match($regularExpression, $file->getPathname()) &&
							self::_hasRequireOnce($file->getPathname(), $remove)) {

							$filesWithRequireOnce[] = $file;
						}
					} else {
						if (preg_match('/\.php$/i',$fileName) &&
							self::_hasRequireOnce($file->getPathname(), $remove)) {

							$filesWithRequireOnce[] = $file;
						}
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
	 * Returns TRUE if the specified file contains one or wrong directory separators
	 * that can be removed. If the second parameter is set to TRUE, it
	 * will replace the wronge one with DIRECORY_SEPARATOR.
	 *
	 * @param  string|SplFileInfo $file
	 * @param  bool               $remove
	 * @return bool
	 */
	protected static function _hasRequireOnce($file = NULL, $remove = FALSE)
	{
		$returnValue = FALSE;

		if ($file instanceof SplFileInfo) {
			$file = $file->getPathname();
		}
		if (is_string($file) &&
			is_file($file) &&
			is_readable($file)) {

			$contents = file_get_contents($file);

			$matches = array();
			$result = preg_match_all(self::PATTERN_REQUIRE_ONCE, $contents, $matches);
			if ($result) {
				if ($remove === TRUE) {
					foreach ($matches[0] as $match) {
						if (strpos($match, '/') !== FALSE) {
							$returnValue = TRUE;
							$replaceMatch = str_replace('/', '\' . DIRECTORY_SEPARATOR . \'', $match);
							$contents = str_replace($match, $replaceMatch, $contents);
						}
					}
					if ($returnValue) {
						file_put_contents($file, $contents);
					}
				}
			}

			$matches = array();
			$result = preg_match_all(self::PATTERN_REQUIRE_ONCE2, $contents, $matches);
			if ($result) {
				if ($remove === TRUE) {
					foreach ($matches[0] as $match) {
						if (strpos($match, '/') !== FALSE) {
							$returnValue = TRUE;
							$replaceMatch = str_replace('/', '\' . DIRECTORY_SEPARATOR . \'', $match);
							$replaceMatch = str_replace('"Zend', '\'Zend', $replaceMatch);
							$replaceMatch = str_replace('.php"', '.php\'', $replaceMatch);
							$contents = str_replace($match, $replaceMatch, $contents);
						}
					}
					if ($returnValue) {
						file_put_contents($file, $contents);
					}
				}
			}

			$matches = array();
			$result = preg_match_all(self::PATTERN_REQUIRE_ONCE3, $contents, $matches);
			if ($result) {
				if ($remove === TRUE) {
					foreach ($matches[0] as $match) {
						if (strpos($match, '/') !== FALSE) {
							$returnValue = TRUE;
							$replaceMatch = str_replace('/', '\' . DIRECTORY_SEPARATOR . \'', $match);
							$contents = str_replace($match, $replaceMatch, $contents);
						}
					}
					if ($returnValue) {
						file_put_contents($file, $contents);
					}
				}
			}

			$matches = array();
			$result = preg_match_all(self::PATTERN_INCLUDE_ONCE, $contents, $matches);
			if ($result) {
				if ($remove === TRUE) {
					foreach ($matches[0] as $match) {
						if (strpos($match, '/') !== FALSE) {
							$returnValue = TRUE;
							$replaceMatch = str_replace('/', '\' . DIRECTORY_SEPARATOR . \'', $match);
							$contents = str_replace($match, $replaceMatch, $contents);
						}
					}
					if ($returnValue) {
						file_put_contents($file, $contents);
					}
				}
			}

			$matches = array();
			$result = preg_match_all(self::PATTERN_INCLUDE_ONCE2, $contents, $matches);
			if ($result) {
				if ($remove === TRUE) {
					foreach ($matches[0] as $match) {
						if (strpos($match, '/') !== FALSE) {
							$returnValue = TRUE;
							$replaceMatch = str_replace('/', '\' . DIRECTORY_SEPARATOR . \'', $match);
							$replaceMatch = str_replace('"Zend', '\'Zend', $replaceMatch);
							$replaceMatch = str_replace('.php"', '.php\'', $replaceMatch);
							$contents = str_replace($match, $replaceMatch, $contents);
						}
					}
					if ($returnValue) {
						file_put_contents($file, $contents);
					}
				}
			}
		}
		return $returnValue;
	}

}