<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/View/Helper/EscapeLinked.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EscapeLinked.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_EscapeLinked
 * 
 *
 */
class L8M_View_Helper_EscapeLinked extends Zend_View_Helper_Abstract
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
	
    /**
     * Escapes the content and wraps it in a link if it is non empty, otherwise
     * returns result of escapeNonEmpty(NULL)
     *
     * @param  string $linkText
     * @param  string $linkUrl
     * @param  string $linkTitle
     * @param  bool   $linkExternal
     * @return string
     */
    public function escapeLinked($linkText = NULL, $linkUrl = NULL, $linkTitle = NULL, $linkExternal = FALSE)
    {
    	$linkText = trim($linkText);
    	if ($linkText && 
    		$linkUrl) {
			return '<a href="' . $linkUrl . '"' . ($linkTitle!=NULL ? ' title="' . $this->view->escape($linkTitle) . '"' : '') . ($linkExternal ? ' class="external"' : '') . '>' . $this->view->escape($linkText) . '</a>';
    	} else {
    		return $this->view->escapeNonEmpty();
    	}
    }
    
}