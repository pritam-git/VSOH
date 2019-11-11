<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceSystemVersion.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceSystemVersion.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceSystemVersion
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceSystemVersion extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a TmceSystemVersion.
	 *
	 * @return string
	 */
	public function tmceSystemVersion($parameter = NULL)
	{
		return 'L8M ' . L8M_Config::getOption('l8m.system.type') . ' ' . L8M_Config::getOption('l8m.system.version');
	}

}