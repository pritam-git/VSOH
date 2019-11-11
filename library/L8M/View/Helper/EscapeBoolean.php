<?php

/**
 * L8M
 *
 * 
 * @filesource /library/L8M/View/Helper/EscapeBoolean.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EscapeBoolean.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_EscapeBoolean
 * 
 *
 */
class L8M_View_Helper_EscapeBoolean extends Zend_View_Helper_Abstract
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
	
    /**
     * Returns yes or no depending on $value
     *
     * @param  bool   $value
     * @return string
     */
    public function escapeBoolean($value = NULL)
    {
    	if ($value === 0 ||
    		$value === '0' ||
    		$value === FALSE) {
			return $this->view->translate('no');
		} else
		
		if ($value === NULL) {
			return $this->view->escapeNonEmpty();
		} else {
			return $this->view->translate('yes');
		}
    }
    
}