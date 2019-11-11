<?php

/**
 * L8M
 *
 * 
 * @filesource /library/L8M/Session/SaveHandler/DbTable.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: DbTable.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Session_SaveHandler_DbTable
 * 
 * 
 */
class L8M_Session_SaveHandler_DbTable extends Zend_Session_SaveHandler_DbTable
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
	
    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return boolean
     */
    public function write($id, $data)
    {
        $return = false;

        $data = array($this->_modifiedColumn => date('Y-m-d H:i:s'),
                      $this->_dataColumn     => (string) $data);

        $rows = call_user_func_array(array(&$this, 'find'), $this->_getPrimary($id));

        if (count($rows)) {
            $data[$this->_lifetimeColumn] = $this->_getLifetime($rows->current());

            if ($this->update($data, $this->_getPrimary($id, self::PRIMARY_TYPE_WHERECLAUSE))) {
                $return = true;
            }
        } else {
        	$data['remote_ip'] = $_SERVER['REMOTE_ADDR'];
        	$data['created_at'] = date('Y-m-d H:i:s');
            $data[$this->_lifetimeColumn] = $this->_lifetime;

            if ($this->insert(array_merge($this->_getPrimary($id, self::PRIMARY_TYPE_ASSOC), $data))) {
                $return = true;
            }
        }

        return $return;
    }
    
}