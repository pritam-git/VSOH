<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceEntityEmail.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceEntityEmail.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceEntityEmail
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceEntityEmail extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a TmceEntityEmail.
	 *
	 * @return string
	 */
	public function tmceEntityEmail($parameter = NULL)
	{
		$returnValue = NULL;

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$returnValue = Zend_Auth::getInstance()->getIdentity()->email;
		}
		return $returnValue;
	}

}