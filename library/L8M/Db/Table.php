<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Db/Table.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Table.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Db_Table
 * 
 *
 */
class L8M_Db_Table extends Zend_Db_Table 
{

	/**
	 * 
	 * 
	 * Class Constructor
	 * 
	 * 
	 */
	
	/**
	 * Constructs L8M_Db_Table instance
	 * 
	 * @param  mixed $config
	 * @return void
	 */
    public function __construct($config = NULL)
    {
        parent::__construct($config);
    }

}