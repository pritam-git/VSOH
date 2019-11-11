<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/View/Helper/ListOrderLink.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ListOrderLink.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_ListOrderLink
 * 
 *
 */
class L8M_View_Helper_ListOrderLink extends Zend_View_Helper_Abstract
{
    
    /**
     * 
     * 
     * Class Constants
     * 
     * 
     */
    
    /**
     * The name of the parameter to use as an identifier for the column by which
     * the list should be sorted.
     */
    const PARAM_NAME_BY = 'by';
    /**
     * The name of the parameter to use as an identifier for the direction in 
     * which the list should be sorted by the specified column.
     */
    const PARAM_NAME_DIRECTION = 'direction';
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */

    /**
     * Returns a link that can be used to order a list
     *
     * @todo   translation
     * @param  string $by
     * @param  string $title
     * @return string
     */
    public function listOrderLink($by = NULL, $title = NULL)
    {
        if (preg_match('/^[a-z0-9_]+$/i', $by) && 
        	$title) {
    		$request = Zend_Controller_Front::getInstance()->getRequest();
    		$direction = strtolower($request->getParam(self::PARAM_NAME_DIRECTION))==='asc' ? 'desc' : 'asc';
    		$listOrderClass = $request->getParam(self::PARAM_NAME_BY)===$by ? 'order ' . $direction : FALSE;  
    		return '<a ' . ($listOrderClass ? 'class="' . $listOrderClass . '" ' : '') . 'href="' . $this->view->url(array(self::PARAM_NAME_BY=>$by, self::PARAM_NAME_DIRECTION=>$direction)) . '" title="Order by ' . $this->view->escape($title) . ' ' . ($direction==='desc' ? 'descending' : 'ascending'). '">' . $this->view->escape($title) . '</a>';
    	}
    	return NULL;
    }    	
}