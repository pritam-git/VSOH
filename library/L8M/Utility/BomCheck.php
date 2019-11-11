<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/BomCheck.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BomCheck.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Utility
 *
 *
 */
class L8M_Utility_BomCheck
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * An array of file extensions which is used to check found files against
     * before checking them whether they contain a BOM.
     *
     * @var array
     */
    protected static $_extensionsToIncludeInBomCheck = array(
    	'php',
        'phtml',
        'css',
        'htaccess',
        'js',
        'txt',
	);

    /**
     * Returns an array of paths to files within the specified directory, that
     * contain a BOM marker, as it is left by Microsoft applications in files
     * that are saved with UTF-8 charset.
     *
     * If the second parameter is set to TRUE, the BOM marker will be removed, if
     * the write permission suffice.
     *
     * @param  string $directory
	 * @param  bool   $remove
     * @return array
     */
    public static function getFilesWithBom($directory = NULL, $remove = FALSE)
    {
        /**
         * filesWithBom
         */
        $filesWithBom = array();

        /**
         * regularExpression
         */
        $regularExpression = '/\.('
        				   . implode('|', self::$_extensionsToIncludeInBomCheck)
        				   . ')$/i'
		;

        /**
         * files
         */
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);
        if (count($files)>0) {
            foreach($files as $file) {
            	/* @var $file SplFileInfo */
                if (preg_match($regularExpression, $file->getFilename()) &&
                    self::_hasBom($file->getPathname(), $remove)) {
                    $filesWithBom[] = $file;
                }
            }
        }

        return $filesWithBom;
    }

    /**
     *
     *
     * Helper Methods
     *
     *
     */

    /**
     * Returns TRUE if the specified file contains a BOM and FALSE if it does
     * not. If the second parameter is set to TRUE, it will remove the BOM from
     * the file's content.
     *
     * @param  string|SplFileInfo $file
     * @param  bool               $remove
     * @return bool
     */
    protected function _hasBom($file = NULL, $remove = FALSE)
    {
    	if ($file instanceof SplFileInfo) {
    		$file = $file->getPathname();
    	}
    	if (is_string($file) &&
    		is_file($file) &&
    		is_readable($file)) {
    		$contents = file_get_contents($file);
    		if (substr($contents, 0, 3) == "\xEF\xBB\xBF") {
    		    if ($remove === TRUE) {
    		    	$contents = substr($contents, 3);
    		    	file_put_contents($file, $contents);
    		    }
    		    return TRUE;
    		}
    	}
    	return FALSE;
    }

}