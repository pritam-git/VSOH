<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/View/Helper/EscapeCode.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EscapeCode.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_EscapeCode
 * 
 *
 */
class L8M_View_Helper_EscapeCode extends Zend_View_Helper_Abstract
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
 
    /**
     * Escapes the specified content and wraps it in <code>-tags, if it is non 
     * empty, otherwise returns result of escapeNonEmpty(NULL)
     *
     * @param  string $content
     * @return string
     */
    public function escapeCode($content = NULL)
    {
    	$content = trim($content);
    	if ($content) {
    		$content = nl2br($content);
    		$content = explode('<br />', $content);
    		if (count($content)) {
    			$escapeCode = array();
	    		foreach($content as $contentLine) {
	    			$contentLine = $this->view->escape($contentLine);
	    			$contentLine = preg_replace('/\t{1}/', '&nbsp;&nbsp;', $contentLine);
	    			$contentLine = str_replace(' ', '&nbsp;', $contentLine);
	    			$escapeCode[] = $contentLine;
	    		}
	    		$escapeCode = implode('<br />', $escapeCode);
    		} else {
    			$contentLine = $this->view->escape($contentLine);
				$contentLine = preg_replace('/\t{1}/', '&nbsp;&nbsp;', $contentLine);
	    		$contentLine = str_replace(' ', '&nbsp;', $contentLine);
    			$escapeCode = $contentLine;
    		}
    		return '<code>' . $escapeCode . '</code>';
    	} else {
    		return $this->view->escapeNonEmpty();
    	}
    }
    
}