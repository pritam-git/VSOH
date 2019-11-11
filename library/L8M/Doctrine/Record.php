<?php 

/**
 * L8M
 *  
 *
 * @filesource /library/L8M/Doctrine/Record.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Record.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Record
 * 
 *
 */
class L8M_Doctrine_Record 
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */	
    
    /**
     * Returns a new Doctrine_Record instance from the specified $modelClassName
     * if the specified class exists and is a subclass of Doctrine_Record.
     * 
     * @param  string $modelClassName
     * @return Doctrine_Record
     */
    public static function factory($modelClassName = NULL)
    {
        if (!is_string($modelClassName) ||
            !class_exists($modelClassName)) {
            throw new L8M_Doctrine_Record_Exception('Model class name needs to be specified as a string amd the corresponding model class needs to exist.');                
        }
        
        $modelClassInstance = new $modelClassName();
        
        if (!is_subclass_of($modelClassInstance, 'Doctrine_Record')) {
            throw new L8M_Doctrine_Record_Exception('Model class needs to be a subclass of Doctrine_Record..');            
        }
        
        return $modelClassInstance;
        
    }
	
}
