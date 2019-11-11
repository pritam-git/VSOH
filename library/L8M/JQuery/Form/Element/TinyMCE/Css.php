<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/TinyMCE/Css.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Css.php 221 2014-11-13 19:40:59Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_TinyMCE_Css
 *
 *
 */
class L8M_JQuery_Form_Element_TinyMCE_Css extends L8M_JQuery_Form_Element_TinyMCE_Css_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * get css filename and path
	 *
	 * @param string $elementId
	 * @return string
	 */
	public static function getCssFile($elementId = NULL)
	{
		$cssFilename = NULL;

		if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'JQuery' . DIRECTORY_SEPARATOR . 'Form' . DIRECTORY_SEPARATOR . 'Element' . DIRECTORY_SEPARATOR . 'TinyMCE' . DIRECTORY_SEPARATOR . 'Css.php') &&
			class_exists('PRJ_JQuery_Form_Element_TinyMCE_Css')) {

			$cssFilename = PRJ_JQuery_Form_Element_TinyMCE_Css::getCssFile($elementId);
		}
		if (!$cssFilename) {
			$httpHostScheme = 'http';
			if (L8M_Library::isHttpHostSecure()) {
				$httpHostScheme = 'https';
			}
			$cssFilename = $httpHostScheme . '://' . $_SERVER['SERVER_NAME'] . '/css/screen/js/tinymce_base.css';
		}

		return $cssFilename;
	}
}