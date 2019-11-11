<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/EscapeBooleanLinked.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EscapeBooleanLinked.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_EscapeBooleanLinked
 *
 *
 */
class L8M_View_Helper_EscapeBooleanLinked extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps a link around value if it is not NULL
	 *
	 * @param  bool   $value
	 * @param  string $linkUrl
	 * @param  string $linkTitle
	 * @return string
	 */
	public function escapeBooleanLinked($value = NULL, $linkUrl = NULL, $linkTitle = NULL)
	{
		if ($value === NULL) {
			return $this->view->escapeNonEmpty();
		} else {
			$value = $this->view->escapeBoolean($value);
			return $this->view->escapeLinked($value, $linkUrl, $linkTitle,FALSE);
		}
	}

}