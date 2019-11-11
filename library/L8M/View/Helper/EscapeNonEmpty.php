<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/View/Helper/EscapeNonEmpty.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EscapeNonEmpty.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_EscapeNonEmpty
 * 
 *
 */
class L8M_View_Helper_EscapeNonEmpty extends Zend_View_Helper_Abstract
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
	
    /**
     * Escapes the content if is not empty, otherwise it shows the alternate
     * content, wrapped in span.empty 
     *
     * @param  string $content
     * @param  string $empty
     * @return string
     */
    public function escapeNonEmpty($content = NULL, $empty = 'n/a') 
    {
    	if (trim($content)) {
    		return $this->view->escape($content);
    	} else {
    	    return '<span class="empty">'  . $this->view->escape($this->view->translate($empty)) . '</span>';
    	}
    }
    
}