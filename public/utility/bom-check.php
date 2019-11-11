<?php

/**
 * L8M
 *
 *
 * This script performs a BOM check on all files contained in the application
 * path and one level above, i.e., it checks whether an unneccesary Byte Order
 * Mark has been placed at the beginning of the file (as done by Microsoft
 * products).
 *
 * @filesource /public/utility/bom-check.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: bom-check.php 431 2015-09-28 13:15:12Z nm $
 */

/**
 * applicationStart
 */
if (!defined('APPLICATION_START')) {
	define('APPLICATION_START', microtime());
}

/**
 * basePath
 */
if (!defined('BASE_PATH')) {
	$basePath = dirname(__FILE__)
			  . DIRECTORY_SEPARATOR
			  . '..'
			  . DIRECTORY_SEPARATOR
			  . '..'
	;
	$basePath = realpath($basePath);
	define('BASE_PATH', $basePath);
}

/**
 * applicationPath
 */
if (!defined('APPLICATION_PATH')) {
	$applicationPath = BASE_PATH
					 . DIRECTORY_SEPARATOR
					 . 'application'
	;
	define('APPLICATION_PATH', $applicationPath);
}

/**
 * libraryPath
 */
$libraryPath = BASE_PATH
			 . DIRECTORY_SEPARATOR
			 . 'library'
;

/**
 * includePaths
 */
set_include_path(implode(PATH_SEPARATOR, array(
	$libraryPath,
	get_include_path(),
)));

/**
 * applicationEnvironment
 */
if (!defined('APPLICATION_ENV')) {
	$applicationEnvironment = getenv('APPLICATION_ENV');
	if (!$applicationEnvironment) {
		$environmentConfiguration = APPLICATION_PATH
								  . DIRECTORY_SEPARATOR
								  . 'configs'
								  . DIRECTORY_SEPARATOR
								  . 'environment.ini'
		;
		require_once('L8M' . DIRECTORY_SEPARATOR . 'Environment.php');
		$applicationEnvironment = L8M_Environment::getInstance($environmentConfiguration)->getEnvironment();
		if ($applicationEnvironment == L8M_Environment::ENVIRONMENT_UNKNOWN) {
			die('Could, but will not run in unknown environment.');
		}
	}
	define('APPLICATION_ENV', $applicationEnvironment);
}

if (APPLICATION_ENV !== L8M_Environment::ENVIRONMENT_DEVELOPMENT) {
	die('Could, but will not run in environment.');
}

/**
 * classes
 *
 * @todo optimize by requiring classes from where they reside rather than
 *	   scanning through include paths
 */
require_once('Zend' . DIRECTORY_SEPARATOR . 'Application.php');

/**
 * applicationConfiguration
 */
$applicationConfiguration = APPLICATION_PATH
						  . DIRECTORY_SEPARATOR
						  . 'configs'
						  . DIRECTORY_SEPARATOR
						  . 'application.ini'
;

/**
 * application
 */
$application = new Zend_Application(APPLICATION_ENV, $applicationConfiguration);
$application->getBootstrap()
	->bootstrap('log')
;

/**
 * extend time limit
 */
set_time_limit(0);
error_reporting(E_ALL);

/**
 * testFile
 */
$testFile = '.' . DIRECTORY_SEPARATOR . 'bom-check' . DIRECTORY_SEPARATOR . 'file.with.bom.php';
$contents = "\xEF\xBB\xBF" . '<?php echo "Hello World!"; ?>';
file_put_contents($testFile, $contents);

/**
 * directory
 */
$directory = APPLICATION_PATH
		   . DIRECTORY_SEPARATOR
		   . '..'
		   . DIRECTORY_SEPARATOR
;

/**
 * remove
 */
$remove = FALSE;

/**
 *
 */
$foundFiles = L8M_Utility_BomCheck::getFilesWithBom($directory, $remove);

?>
<h1>BOM</h1>
<p>Found <code><?php echo count($foundFiles); ?></code> files with a <code>BOM</code> in <code><?php echo $directory; ?></code></p>
<?php

if ($remove) {

?>
<p>Comments have been added to make the calls non-functional.</p>
<?php

}

?>
<h2>List of files</h2>
<ol>
	<li><?php echo implode('</li><li>', $foundFiles); ?></li>
</ol>