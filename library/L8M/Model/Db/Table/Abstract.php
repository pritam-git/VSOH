<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Model/Db/Table/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Model_Db_Table_Abstract
 * 
 * 
 */
class L8M_Model_Db_Table_Abstract extends L8M_Model_Abstract
{
    
    /**
     * 
     * 
     * Class Variables
     * 
     * 
     */
	
    
    /**
     * The name of the table in the database
     * 
     * @var string
     */
    protected $_tableName = NULL;
    
    /**
     * The name of the primary key of the table
     * 
     * @var string
     */
    protected $_tablePrimaryKey = 'ID';
    
    /**
     * An instance of the table class
     * 
     * @var string
     */
    protected $_tableInstance = NULL;
    
    /**
     * The ID of the row in the table 
     * 
     * @var int
     */
    protected $_tableRow = NULL;
    
    /**
     * An array of table field names, original table field name => translated field name
     * 
     * @var array
     */
    protected $_tableColumns = array();
    
    /**
     * An array of table field names to exclude (in what actions . . . ?) 
     * 
     * @var array
     */
    protected $_tableColumnsHidden = array();
    
    /**
     * An array of class names of dependent tables
     * 
     * @var array
     */
    protected $_tableDependentTables = array();
    
    /**
     * 
     * 
     * Getter Methods
     *
     * 
     */
    
    /**
     * Retrieves table instance
     * 
     * @return L8M_Db_Table
     */
    public function getTableInstance ()
    {
        if ($this->_tableInstance === NULL) {
            $this->_tableInstance = new L8M_Db_Table(array('name'=>$this->_tableName));
        }
        return $this->_tableInstance;
    }
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Inserts row
     * 
     * @return int
     */
    public function insert($data = NULL)
    {
    	$data = $this->_prepareDataForInsert($data);
        return $this->getTableInstance()->insert(array_intersect_key($data,array_flip($this->_tableColumns)));
    }
    
    /**
     * Updates row
     *
     * @param  array $data
     * @return bool
     */
    public function update($data = NULL, $where = NULL)
    {
    	$data = $this->_prepareDataForUpdate($data);
    	
    	/**
    	 * primary key given in data
    	 */
		if (array_key_exists($this->_tablePrimaryKey,$data) &&
   			preg_match('/^[1-9]+[0-9]*$/',$data[$this->_tablePrimaryKey])) {
			return $this->getTableInstance()->update(array_intersect_key($data,array_flip($this->_tableColumns)), $this->_tablePrimaryKey . '=' . Zend_Registry::get('database')->quote($data[$this->_tablePrimaryKey]));     			
  		} else 

  		/**
  		 * otherwise
  		 */
  		if ($where!==NULL) {
			return $this->getTableInstance()->update(array_intersect_key($data,array_flip($this->_tableColumns)), $where);  			
  		}

   		return FALSE;
   		
    }
    
    /**
     * Deletes row
     *
     * @todo optimize, as at the moment only deletes are supported where the
     *       where is provided as a number, the id of the row
     * @todo reconsider whether entries should be deleted or generally just marked
     *       as deleted 
     * @param string $where
     * @return bool
     */
    public function delete($where = NULL)
    {
    	if (preg_match('/^[1-9]+[0-9]*$/',$where)) {
    		return $this->getTableInstance()->delete($this->_tableName . '.' . $this->_tablePrimaryKey . '=' . Zend_Registry::get('database')->quote($where)); 
    	} 
    	
    	return FALSE;
    	
    }
    
    /**
     * Returns true, when a record with the provided ID exists and false
     * otherwise
     *
     * @param int $ID
     * @return bool
     */    
    public function exists ($id = NULL)
    {
    	
    	/**
    	 * select
    	 * 
    	 * @todo consider using (!) $this->_prepareSelectQuery
    	 */
		$select = new Zend_Db_Select(Zend_Registry::get('database'));
        $select->from($this->_tableName,array($this->_tablePrimaryKey));
        $select->where($this->_tableName . '.' . $this->_tablePrimaryKey . '='. Zend_Registry::get('database')->quote($id));
        
        /**
         * result . . . ?
         */
        return is_array(Zend_Registry::get('database')->fetchRow($select, Zend_db::FETCH_ASSOC));
    	
    }
    
    /**
     * Fetches all rows.
     * 
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchAll()
    {

    	$select = $this->_prepareSelectQuery();
    	return Zend_Registry::get('database')->fetchAll($select, Zend_db::FETCH_ASSOC);
    	
    }
    
	/**
     * Fetches row(s) by conditions(s).
     * 
     * @param string $condition
     * @param string $value
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchByCondition($condition = NULL, $value = NULL)
    {
        if ($condition) {
            $select = $this->_prepareSelectQuery();
            $select->where($condition, $value);
            return Zend_Registry::get('database')->fetchAll($select, Zend_db::FETCH_ASSOC);
        }
        return FALSE;
    }    
    
    /**
     * Fetches row by ID
     *
     * @param int $ID
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchByID ($ID = NULL)
    {
    	$select = $this->_prepareSelectQuery();
        $select->where($this->_tableName . '.' . $this->_tablePrimaryKey . '=' . Zend_Registry::get('database')->quote($ID))
               ->limit(1);
        return Zend_Registry::get('database')->fetchRow($select, Zend_Db::FETCH_ASSOC);
    }
    
	/**
     * Fetches rows prepared for Zend_Dojo_Form_Element_FilteringSelect, if 
     * $optional is set to TRUE, then an empty element will be pushed on top of 
     * the result, so selecting an element from the 
     * Zend_Dojo_Form_Element_FilteringSelect will be optional (this is a work-
     * around)
     *
     * @param  Zend_Db_Select|string $id
     * @param  string                $name
     * @param  string                $where
     * @param  bool                  $optional
     * @return Zend_Dojo_Data
     */
    public function fetchForAutoComplete($id = NULL, $name = NULL, $where = NULL, $optional = NULL)
    {
    	/**
    	 * pass in a Zend_Db_Select instance to select your autocomplete data on 
    	 * the run
    	 */
    	if ($id instanceof Zend_Db_Select) {
    		$select = $id;
    		$optional = (bool) $name;
    	} else {
    		
    		/**
    		 * $id, $name, $optional
    		 */
    		if (func_num_args()==3 &&
    			is_string($id) &&
    			is_string($name) &&
    			is_bool($where)) {
				$optional = $where;
				$where = NULL;
			} else 
			
			/**
			 * $where, $optional
			 */
			if (func_num_args()==2 &&
				is_string($id) && 
				is_bool($name)) {
				$where = $id;
				$id = NULL;
				$optional = (bool) $name;					
			} else
			
			/**
			 * less then or 1 argument (which would be $optional)
			 */
			if (func_num_args()<=1 &&
				is_bool($id)) {
				$optional = (bool) $id;
				$id = NULL;
    		}
			
    		/**
    		 * prepare select query
    		 */
    		$select = $this->_prepareSelectQueryForAutoComplete($id, $name, $where);
    	}
    	
    	/**
    	 * retrieve data
    	 */
        $result = Zend_Registry::get('database')->fetchAll($select, Zend_Db::FETCH_ASSOC);
        
        /**
         * if optional is TRUE, add empty entry
         */
        $optional = (bool) $optional;
        if ($optional===TRUE) {
        	$result = array_merge(array(array('id'=>NULL,'name'=>'')), $result);
        }
        
		$data = new Zend_Dojo_Data('id', $result);
		
		return $data;
    	
    }
    
    /**
     * Fetches rows paginated
     *
     * @param  string $order
     * @return Zend_Paginator
     */
    public function fetchPaginated ()
    {
    	
    	/**
    	 * select
    	 */
    	$select = $this->_prepareSelectQuery();
        
        /**
         * order
         */
		$request = Zend_Controller_Front::getInstance()->getRequest();
		
		$orderBy        = (NULL!=$orderBy = $request->getParam('orderBy')) && preg_match('/^[a-z0-9_]+$/i',$orderBy) ? $orderBy : $this->_tablePrimaryKey;
		$orderDirection = (NULL!=$orderDirection = $request->getParam('orderDirection')) && preg_match('/^(asc|desc)$/i',$orderDirection) ? strtoupper($orderDirection) : 'ASC';
		
		$select->order($orderBy . ' ' . $orderDirection);
		
        $list = Zend_Paginator::factory($select);
        $list->setItemCountPerPage(15)
        	 ->setPageRange(5)
        	 ->setCurrentPageNumber(Zend_Controller_Front::getInstance()->getRequest()->getParam('page'));
        
        return $list;
    
    }
    
	/**
     * Fetches row by condition
     * 
     * @param  string $condition
     * @param  string $value
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchRowByCondition ($condition = NULL, $value = NULL)
    {
        if ($condition) {
        	/**
        	 * select
        	 */
        	$select = $this->_prepareSelectQuery();
            $select->where($condition, $value)
            	   ->order($this->_tableName . '.' . $this->_tablePrimaryKey . ' ASC');
            return Zend_Registry::get('database')->fetchRow($select, Zend_db::FETCH_ASSOC);
        }
        return FALSE;
    }    
    
	/**
     * Prepares data for insert
     *
     * @param  array $data
     * @return array
     */
    protected function _prepareDataForInsert($data = NULL)
    {
    	/**
    	 * dataCreated
    	 */
    	$dataCreatedField = preg_replace('/ID$/', 'Created', $this->_tablePrimaryKey);
    	if (!array_key_exists($dataCreatedField,$data)) {
    		$data[$dataCreatedField] = date('Y-m-d H:i:s');
    	}
    	return $data;
    }
    
	/**
     * Prepares data for update
     *
     * @param  array $data
     * @return array
     */
    protected function _prepareDataForUpdate($data = NULL)
    {
    	/**
    	 * dataEdited
    	 */
    	$dataEditedField = preg_replace('/ID$/', 'Edited', $this->_tablePrimaryKey);
    	if (!array_key_exists($dataEditedField,$data)) {
    		$data[$dataEditedField] = date('Y-m-d H:i:s');
    	}
    	return $data;
    }    
    
    /**
     * Returns basic Zend_Db_Select which can then be modified and adjusted by 
     * fetch functions
     *
     * @return Zend_Db_Select
     */
    protected function _prepareSelectQuery()
    {
    	$select = new Zend_Db_Select(Zend_Registry::get('database'));
        $select->from($this->_tableName, $this->_tableColumns);
        return $select;
    }
    
    /**
     * Returns Zend_Db_Select optimized for return results rows for auto 
     * complete 
     *
     * @return Zend_Db_Select
     */
    protected function _prepareSelectQueryForAutoComplete($id = NULL, $name = NULL, $where = NULL)
    {

    	$id = $id ? $id : 'id';
    	$name = $name ? $name : 'name';

    	/**
    	 * subSelect
    	 */
    	$subSelect = $this->_prepareSelectQuery();
    	
    	/**
         * @todo potential risk
         */
        if (is_string($where)) {
        	$subSelect->where($where);
        }
    
    	/**
    	 * we now select from a subselect, the query returned by 
    	 * _prepareSelectQuery
    	 */
    	$select = new Zend_Db_Select(Zend_Registry::get('database'));
    	$select->from($subSelect, array('id'=>$id, 'name'=>$name))
    		   ->group('id')
    		   ->group('name');
    
    	return $select;
    }
    
}