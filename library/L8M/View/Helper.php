<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Helper.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper
 *
 *
 */
class L8M_View_Helper extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * retrieve language
	 *
	 * @return string
	 */
	public function getLanguage()
	{
		/**
		 * language
		 */
		$language = Zend_Registry::isRegistered('Zend_Locale')
				  ? Zend_Registry::get('Zend_Locale')->getLanguage()
				  : NULL
		;
		return $language;
	}
}