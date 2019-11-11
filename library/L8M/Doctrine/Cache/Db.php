<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Cache/Db.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Db.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Cache_Db
 *
 *
 */
class L8M_Doctrine_Cache_Db extends Doctrine_Cache_Db
{

    /**
     * 
     * 
     * Class Constructor
     * 
     * 
     */
	
    /**
     * Constructs L8M_Doctrine_Cache_Db instance. Further to the parent class
     * constructor, the passed connection can be a string representing the name
     * of a connection.
     *
     * @param  array $options
     * @return void
     */
    public function __construct($options = array()) 
    {
    	if (isset($options['connection']) &&
    		is_string($options['connection'])) {
			try {
    			$options['connection'] = Doctrine_Manager::getInstance()->getConnection($options['connection']);
			} catch (Doctrine_Exception $exception) {
				throw new L8M_Doctrine_Cache_Db_Exception('Could not retrieve Doctrine_Connection instance with specified connection name');
			}
		}
		
		parent::__construct($options);
    }	
    
}