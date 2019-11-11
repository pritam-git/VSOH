<?php

/**
 * L8M
 *  
 *
 * @filesource /library/L8M/Doctrine/Template/Activatable.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Activatable.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Template_Activatable
 * 
 *
 */
class L8M_Doctrine_Template_Activatable extends Doctrine_Template
{
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Sets up table definition.
     * 
     * @return void
     */
    public function setTableDefinition()
    {
        $this->hasColumn('activated_at', 'timestamp', NULL, array('notnull'=>FALSE));
    }
}