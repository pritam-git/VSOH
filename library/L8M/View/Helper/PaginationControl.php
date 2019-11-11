<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/PaginationControl.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PaginationControl.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_PaginationControl
 *
 *
 */
class L8M_View_Helper_PaginationControl extends Zend_View_Helper_PaginationControl
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Renders a pagination control. Actually just sets a partial and then calls
     * Zend_View_Helper_PaginationControl
     *
     * @param  Zend_Paginator|Doctrine_Collection $paginator
     * @param  string                             $scrollingStyle
     * @param  string|array                       $partial
     * @param  string                             $params
     * @return string
     */
    public function paginationControl(Zend_Paginator $paginator = NULL, $scrollingStyle = NULL, $partial = NULL, $params = NULL)
    {
    	if ($partial===NULL) {
	    	$partial = array('paginator.phtml', 'default');
    	}
    	return parent::paginationControl($paginator, $scrollingStyle, $partial, $params);
    }

}