<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/WrapInBox.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: WrapInBox.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_WrapInBox
 *
 *
 */
class L8M_View_Helper_WrapInBox extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Contains TRUE when required CSS has been added.
	 *
	 * @var bool
	 */
	protected static $_stylesheetsAdded = FALSE;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Wraps content in box, but does not escape it.
     *
     * @param  string $content
     * @param  string $cssClasses
     * @param  string $cssStyles
     * @param  bool   $escape
     * @return string|L8M_View_Helper_WrapInBox
     */
    public function wrapInBox($content = NULL, $cssClasses = NULL, $cssStyles = NULL, $escape = FALSE)
    {
    	if ($content !== NULL) {

    		$wrappedInBox = $this->view->wrapInBoxTop($cssClasses, $cssStyles)
    					  . $this->view->wrapInBoxContent($content, $escape)
    					  . $this->view->wrapInBoxBottom()
			;

            return $wrappedInBox;
    	}
    	return NULL;
    }

}