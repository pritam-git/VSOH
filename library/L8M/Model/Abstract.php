<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Model/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Model_Abstract
 * 
 * 
 */
abstract class L8M_Model_Abstract
{
    /**
     * 
     * 
     * Abstract Methods
     *  
     * 
     */
	
	/**
	 * Inserts row
	 *
	 * @param  array $data
	 * @return int
	 * @todo   consider inserting multiple rows at once
	 */
    abstract public function insert($data = NULL);
    
    /**
     * Updates row
     *
     * @param  array $data
     * @param  string $where
     * @return bool
     */
    abstract public function update($data = NULL, $where = NULL);
    
    /**
     * Deletes row
     *
     * @param string $where
     */
    abstract public function delete($where = NULL);
      
    /**
     * Returns true, when a record with the provided ID exists and false
     * otherwise
     *
     * @param  int $ID
     * @return bool
     */
    abstract public function exists($ID = NULL);
    
/**
     * Fetches all rows
     * 
     * @return Zend_Db_Table_Rowset_Abstract
     */
    abstract public function fetchAll();
    
    /**
     * Fetches row(s) by condition
     *
     * @param mixed $condition
     * @return Zend_Db_Table_Rowset_Abstract
     */
    abstract public function fetchByCondition($condition = NULL);

	/**
     * Fetches row by ID
     *
     * @param int $ID
     * @return Zend_Db_Table_Rowset_Abstract
     */
    abstract public function fetchByID($ID = NULL);

    /**
     * Fetches rows prepared for Zend_Dojo_Form_Element_FilteringSelect, if 
     * $optional is set to TRUE, then an empty element will be pushed on top of 
     * the result, so selecting an element from the 
     * Zend_Dojo_Form_Element_FilteringSelect will be optional (this is a work-
     * around)
     * 
     * @param string $id
     * @param string $name
     * @param string $where
     * @param bool   $optional
     */
    abstract public function fetchForAutoComplete($id = NULL, $name = NULL, $where = NULL, $optional = NULL);
    
    /**
     * Fetches rows paginated
     *
     * @return Zend_Paginator
     */
    abstract public function fetchPaginated();
    
	/**
     * Prepares data for insert
     *
     * @param  array $data
     * @return array
     */
    abstract protected function _prepareDataForInsert($data = NULL);

    /**
     * Prepares data for update
     *
     * @param  array $data
     * @return array
     */
    abstract protected function _prepareDataForUpdate($data = NULL);    
    
    /**
     * 
     * 
     * Form Methods
     *  
     * 
     */
    
	/**
     * Returns form if a corresponding function exists
     *
     * @return L8M_Form
     */
    public function getForm($formName = NULL, $formOptions = NULL)
    {
        
        $formFunction = '_get' . ucfirst($formName) . 'Form';
        
    	if (preg_match('/^[a-z]+$/i', $formName) && 
    		method_exists($this, $formFunction)) {
			return $this->{$formFunction}($formOptions);
   		} else {
   			throw new L8M_Model_Abstract_Exception('formName needs to be specified and the function needs to exist');	
   		}
	    
    }
   
}