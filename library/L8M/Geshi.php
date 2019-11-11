<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Geshi.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Geshi.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Geshi
 *
 *
 */
class L8M_Geshi
{
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Parses a string with GeSHI.
	 *
	 * @param string $source
	 * @param string $language
	 */
	public static function parse($source = NULL, $language = 'PHP')
	{
		require_once('Geshi' . DIRECTORY_SEPARATOR . 'geshi.php');
		$geshi = new GeSHi($source, $language);
		$geshi->keyword_links = FALSE;
		$geshi->header_type = GESHI_HEADER_DIV;
		return $geshi->parse_code();
	}

}