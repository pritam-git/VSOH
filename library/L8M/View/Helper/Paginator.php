<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/View/Helper/Paginator.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Paginator.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_Paginator
 * 
 *
 */
class L8M_View_Helper_Paginator extends Zend_View_Helper_Abstract 
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
    
    /**
     * Renders a paginator.
     *
     * @param  array|Doctrine_Collection|Zend_Paginator $paginator
     * @param  string                                   $scrollingStyle
     * @param  string                                   $partial
     * @param  string                                   $params
     * @return string
     */
    public function paginator($paginator = NULL, $scrollingStyle = NULL, $partial = NULL, $params = NULL)
    {
//        if ($paginator instanceof Doctrine_Collection) {
//            $paginator = $paginator->getData();
//        }
//        if (is_array($paginator)) {
//            $paginator = Zend_Paginator::factory($paginator, 'Array');
//        }
//    	return $this->view->paginationControl($paginator, $scrollingStyle, $partial, $params);
		return 'PAGINATOR!';
    }
    
}