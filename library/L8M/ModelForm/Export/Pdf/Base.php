<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ModelForm/Export/Pdf/Base.php
 * @author	   Matthias Rogowski <mr@l8m.de>
 * @version    $Id: Base.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 * set external tcpdf config
 * if you want to use the original tcpdf-config dont set this define!
 */
define ('K_TCPDF_EXTERNAL_CONFIG', true);
/**
 * main configuration file
 */
#Zend_Debug::dump(dirname(__FILE__).'/tcpdf_config.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tcpdf_config.php');
#die("nach config");

/**
 * tcpdf config
 */
require_once(BASE_PATH . DIRECTORY_SEPARATOR . 'library'.DIRECTORY_SEPARATOR . 'TCPDF' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'eng.php');

/**
 * tcpdf library
 */
require_once(BASE_PATH . DIRECTORY_SEPARATOR . 'library'.DIRECTORY_SEPARATOR . 'TCPDF' . DIRECTORY_SEPARATOR . 'tcpdf.php');

class L8M_ModelForm_Export_Pdf_Base extends TCPDF
{
	public function Header() {}

	public function Footer() {}

}