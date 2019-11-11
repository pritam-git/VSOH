<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Log/Writer/Firebug.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Firebug.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Log_Writer_Firebug
 * 
 * 
 */
class L8M_Log_Writer_Firebug extends Zend_Log_Writer_Firebug
{
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
	/**
     * Log a message to the Firebug Console.
     *
     * @param array $event The event data
     * @return void
     */
    protected function _write($event)
    {
        if (!$this->getEnabled()) {
            return;
        }
      
        if (array_key_exists($event['priority'],$this->_priorityStyles)) {
            $type = $this->_priorityStyles[$event['priority']];
        } else {
            $type = $this->_defaultPriorityStyle;
        }
        
        L8M_Wildfire_Plugin_FirePhp::getInstance()->send($event['message'], NULL, $type);
    }
    
}