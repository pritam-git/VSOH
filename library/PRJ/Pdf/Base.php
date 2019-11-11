<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Pdf/Bill/Base.php
 * @author	   Matthias Rogowski <mr@l8m.com>
 * @version    $Id: Base.php 6 2014-06-25 11:15:10Z nm $
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

class PRJ_Pdf_Base extends TCPDF
{
	public function Header() {
		$shopLogoModel = PRJ_SiteConfig::getMediaImage('bill_logo');
		$shopLogoResizedModel = $shopLogoModel->maxBox(160, 80);
		$x = 210 - 26 - ($shopLogoResizedModel->width / 4);
		$this->Image($shopLogoResizedModel->getStoredFilePath(), $x , 16, ($shopLogoResizedModel->width / 4), ($shopLogoResizedModel->height / 4), '', '', TRUE, 72);
	}

	public function Footer() {}


}