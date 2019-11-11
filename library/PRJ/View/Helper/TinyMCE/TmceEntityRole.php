<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceEntityRole.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceEntityRole.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceEntityRole
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceEntityRole extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a TmceEntityRole.
	 *
	 * @return string
	 */
	public function tmceEntityRole($parameter = NULL)
	{
		$returnValue = NULL;

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$returnValue = Zend_Auth::getInstance()->getIdentity()->Role->name;
		}
		return $returnValue;
	}

}