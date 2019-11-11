<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Application/Check.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Check.php 556 2018-01-18 19:43:01Z nm $
 */

/**
 *
 *
 * Includes
 *
 *
 */
require_once('L8M' . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Check' . DIRECTORY_SEPARATOR . 'PhpVersion.php');
require_once('L8M' . DIRECTORY_SEPARATOR . 'Environment' . DIRECTORY_SEPARATOR . 'Writables.php');

/**
 *
 *
 * L8M_Application_Check
 *
 *
 */
class L8M_Application_Check
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * System version Number
	 * @var String
	 */
	private static $_version = '2.5';

	/**
	 * EnvironmentWritables Instance
	 * @var L8M_Environment_Writables
	 */
	private $_environmentWritables = NULL;

	/**
	 * PhpVersion Instance
	 * @var L8M_Application_Check_PhpVersion
	 */
	private $_phpVersion = NULL;

	/**
	 * Server-Name
	 * @var String
	 */
	private static $_serverName = 'unknown';


	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Constructs L8M_Application_Check instance.
	 *
	 * @return L8M_Application_Check
	 */
	protected function __construct()
	{

		/**
		 * php version
		 */
		$this->_phpVersion = L8M_Application_Check_PhpVersion::factory();

		/**
		 * applicationWritables
		 */
		$writableConfiguration = APPLICATION_PATH
							   . DIRECTORY_SEPARATOR
							   . 'configs'
							   . DIRECTORY_SEPARATOR
							   . 'writables.ini'
		;
		$this->_environmentWritables = L8M_Environment_Writables::getInstance($writableConfiguration);

		/**
		 * server name
		 */
		if (array_key_exists('SERVER_NAME', $_SERVER)) {
			self::$_serverName = $_SERVER['SERVER_NAME'];
		}
	}

	/**
	 * Returns HTTP-Scheme.
	 *
	 * @return String
	 */
	private static function _getScheme()
	{
		$returnValue = 'http://';

		if (is_array($_SERVER) &&
			isset($_SERVER['HTTPS']) &&
			strtolower($_SERVER['HTTPS']) == 'on') {

			$returnValue = 'https://';
		}

		return $returnValue;
	}

	/**
	 * Returns a L8M_Application_Check Instance.
	 *
	 * @return L8M_Application_Check
	 */
	public static function factory()
	{
		return new L8M_Application_Check();
	}

	/**
	 * Checks System writables and PHP Version and Environment
	 *
	 * @return boolean
	 */
	public function checkSystem()
	{
		$returnValue = TRUE;

		/**
		 * check php version
		 */
		if ($this->_phpVersion->hasErrors()) {
			$returnValue = FALSE;
		}

		/**
		 * check writables
		 */
		if ($this->_environmentWritables->hasErrors()) {
			$returnValue = FALSE;
		}

		return $returnValue;
	}

	/**
	 * Generates HTML-Output
	 *
	 * @return String
	 */
	public function generateOutput()
	{
		$returnValue = NULL;

		$phpVersionError = $this->_phpVersion->getErrorsHtml();
		if ($phpVersionError) {
			$returnValue .= self::getBox($phpVersionError, 'PHP');
		}

		$writablesError = $this->_environmentWritables->getErrorsHtml();
		if ($writablesError) {
			$returnValue .= self::getBox($writablesError, 'Environment Writables');
		}

		if ($returnValue) {
			$returnValue = self::getLayout($returnValue);
		}
		return $returnValue;
	}

	/**
	 * Creates an HTML page layout.
	 * Needs to be public static, 'cause it's called from Staging Plugin.
	 *
	 * @param String $content
	 * @return string
	 */
	public static function getLayout($content = NULL, $menuFirst = 'System', $menuSecond = 'Error')
	{
		ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"
	version="XHTML+RDFa 1.1"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.w3.org/1999/xhtml http://www.w3.org/MarkUp/SCHEMA/xhtml-rdfa-2.xsd"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
	xmlns:og="http://ogp.me/ns#"
	xml:lang="de"
	lang="de"
	dir="ltr">
	<head>
		<meta http-equiv="expires" content="<?php echo date('c', strtotime('+1 week'))?>" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta http-equiv="content-language" content="en" />
		<meta name="abstract" content="Hahn media group ag. - <?php echo $menuFirst; ?> / <?php echo $menuSecond; ?>" />
		<meta name="date" content="<?php echo date('c'); ?>" />
		<meta name="copyright" content="Copyright (c) 1997 - <?php echo date('Y'); ?> HAHN media group ag." />
		<meta name="description" content="Website - Staging" />
		<meta name="keywords" content="software-development, website, webdesign, smartphone-app, cms, homepage, design agentur, facebook fanpage, content managment system, fullservice agentur, hompage design, professionelle homepage, profi homepage, software homepage, suchmaschinen marketing, suchmaschinen optimierung, seo, sem, web design, web design agentur, webdesign, webdesign agentur, webdesign anbieter, webdesign angebot, webdesign cms, webdesign erstellen, webdesign erstellung, webdesign firma" />
		<meta name="generator" content="HMG <?php echo self::$_version; ?> using Zend Framework 1.11.5 visit  www.hahn-media.ch for more information." />
		<meta name="geo.region" content="ZH" />
		<meta name="geo.placename" content="Zurich" />
		<meta name="geo.position" content="47.4132;8.53723" />
		<meta name="ICBM" content="47.4132, 8.53723" />
		<meta name="DC.creator" content="HAHN media group ag. visit us at www.hahn-media.ch" />
		<meta name="DC.title" content="Hahn media group ag. - <?php echo $menuFirst; ?> / <?php echo $menuSecond; ?>" />
		<meta name="DC.description" content="Website - Staging" />
		<meta name="author" content="HAHN media group ag." />
		<meta name="publisher" content="HAHN media group ag." />
		<meta name="robots" content="noindex, nofollow" />
		<meta name="viewport" content="width = 1024, initial-scale = 0.75,  maximum-scale = 2.0, user-scalable = yes" />
		<meta property="og:title" content="Hahn media group ag. - <?php echo $menuFirst; ?> / <?php echo $menuSecond; ?>" />
		<meta property="og:site_name" content="Hahn media group ag. - Staging" />
		<meta property="og:description" content="Website - Staging" />
		<meta property="og:url" content="<?php echo self::_getScheme() . self::$_serverName; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:locality" content="Zurich" />
		<meta property="og:region" content="ZH" />
		<meta property="og:country-name" content="Switzerland" />
		<meta property="og:email" content="info@hahn-media.ch" />
		<meta property="og:image" content="<?php echo self::_getScheme() . self::$_serverName; ?>/img/system/logo_og_tag.png" />
		<title>Hahn media group ag. - <?php echo $menuFirst; ?> / <?php echo $menuSecond; ?></title>
		<link href="/css/default/all/reset.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/js/multitab.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/grid.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/rhythm.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/typography.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/base.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/color.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/sprites.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/form.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/box/base.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/box/color.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/iconized/base.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/iconized/sprites.css" media="all" rel="stylesheet" type="text/css" />
		<!--[if lte IE 6]> <link href="/css/system/screen/ie6.css" media="screen" rel="stylesheet" type="text/css" /><![endif]-->
		<link href="/css/system/screen/array-show/base.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/system/screen/array-show/color.css" media="screen" rel="stylesheet" type="text/css" />
		<link href="/css/screen/js/jquery.ui.css" media="all" rel="stylesheet" type="text/css" />
		<link href="/img/system/favicon.png" rel="icon" type="image/png" />
		<script type="text/javascript" src="<?php echo self::_getScheme(); ?>ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo self::_getScheme(); ?>ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/js/jquery/base.js"></script>
		<script type="text/javascript" src="/js/jquery/system/base.js"></script>
	</head>

	<body class="admin-index-index">
		<!-- header begin -->
		<div id="header">
			<div id="header-content">
				<div class="logo-version">
					<a href="<?php echo self::_getScheme(); ?>www.hahn-media.ch" class="external blank">Hahn media group ag.</a>
				</div>
				<!-- menu begin -->
				<div id="menu">
					<ul class="menu">
						<li>
							<a href="/" class="default" title="Homepage">Homepage</a>
						</li>
					</ul>
				</div>
				<!-- menu end -->
				<!-- second menu begin -->
				<div id="second-menu">
					<ul class="second-menu">
						<li class="main active">
							<a href="" class="second-menu"><?php echo $menuFirst; ?></a>
						</li>
					</ul>
				</div>
				<!-- second menu end -->
				<!-- third menu beginn -->
				<!-- third menu end -->
				<!-- headlines begin -->
				<div class="headlines">
					<h1 class="headline"><?php echo $menuSecond; ?></h1>
					<br class="clear" />
				</div>
				<!-- headlines end -->
				<br class="clear" />
			</div>
		</div>
		<!-- header end -->
		<!--  page begin -->
		<div class="bg">
			<div id="page">
				<!-- content begin -->
				<div id="content">
					<?php echo $content; ?>
					<hr class="clear" />
				</div>
				<!-- content end -->
				<hr class="clear" />
			</div>
		</div>
		<!-- page end -->
		<!-- footer begin -->
		<div id="footer">
			<div id="footer-content">
				<p class="last">
					<a href="<?php echo self::_getScheme(); ?>www.hahn-media.ch/imprint/website/name/<?php echo rawurldecode(self::$_serverName); ?>" title="Impressum">Impressum</a><br />
					&copy; <?php echo date('Y'); ?> Hahn media group ag. | <a href="<?php echo self::_getScheme(); ?>www.hahn-media.ch" class="external">www.hahn-media.ch</a>
				</p>
			</div>
		</div>
		<!-- footer end -->
	</body>
</html>
<?php

		return ob_get_clean();
	}

	/**
	 * Creates an HTML box.
	 * Needs to be public static, 'cause it's called from Staging Plugin.
	 *
	 * @param String $content
	 * @param String $headline
	 */
	public static function getBox($content = NULL, $headline = NULL, $cssClass = NULL)
	{
		if ($headline) {
			$headline = '<h3>' . $headline . '</h3>' . PHP_EOL;
		}

		if ($cssClass) {
			$cssClass = ' ' . $cssClass;
		}
		ob_start();

?>
<!-- box begin -->
<div class="box system wide<?php echo $cssClass; ?>">
	<?php echo $headline . $content; ?>
</div>
<!-- box end -->
<?php

		return ob_get_clean();
	}

	/**
	 * Returns an string of all errors as html list.
	 *
	 * @param Exception | String | array $exception
	 * @return String
	 */
	public static function getErrorsHtml($exceptions)
	{
		$returnValue = NULL;

		if (!is_array($exceptions)) {
			$exceptions = array($exceptions);
		}

		foreach ($exceptions as $exception) {
			if ($exceptions instanceof Exception) {
				$errorMsg = $exceptions->getMessage();
			} else {
				$errorMsg = $exception;
			}
			$returnValue .= '<li class="exclamation">' . $errorMsg . '</li>';
		}

		if ($returnValue) {
			$returnValue = '<ul class="iconized">' . $returnValue . '</ul>';
		}
		return $returnValue;
	}
}