<?php

/**
 * L8M
 *  
 *
 * @filesource /library/L8M/Acl/Adapter/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Acl_Adapter_Abstract
 * 
 *
 */
 abstract class L8M_Acl_Adapter_Abstract 
 {
 	
 	/**
 	 * 
 	 * 
 	 * Class Variables
 	 * 
 	 * 
 	 */
 	
    /**
     * A Zend_Acl instance.
     * 
     * @var Zend_Acl
     */
    protected $_acl = NULL; 	
    
 	/**
 	 * An array of options.
 	 * 
 	 * @var array
 	 */
 	protected $_options = NULL;
 	
 	/**
 	 * 
 	 * 
 	 * Class Constructor
 	 * 
 	 * 
 	 */
 	
 	/**
 	 * Constructs L8M_Acl_Adapter_Abstract instance.
 	 * 
 	 * @param  array|Zend_Config $options
 	 * @return void
 	 */
    public function __construct($options = NULL)
    {
        if ($options) {
        	$this->setOptions($options);
        }
        $this->init();
        
        return $this;
    }
    
    /**
     * 
     * 
     * Setter Methods
     * 
     * 
     */
    
    /**
     * Sets options.
     * 
     * @param  array|Zend_Config $options
     * @return L8M_Acl_Adapter_Abstract
     */
    public function setOptions($options = NULL)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            throw new L8M_Acl_Adapter_Abstract_Exception('Options need to be specified as an array or a Zend_Config instance.');
        }
        $this->_options = $options;
    }    
    
    /**
     * 
     * 
     * Getter Methods
     * 
     * 
     */
    
     /**
     * Returns Zend_Acl instance as provided by this L8M_Acl_Adapter_Abstract
     * instance.
     * 
     * @return Zend_Acl
     */
    public function getAcl()
    {
    	return $this->_acl;
    }

 	/**
 	 * 
 	 * 
 	 * Class Methods
 	 * 
 	 * 
 	 */
 	
 	/**
 	 * Initializes L8M_Acl_Adapter_Abstract instance and creates Zend_Acl
 	 * instance..
 	 * 
 	 * @return L8M_Acl_Adapter_Abstract
 	 */
 	abstract public function init();
 	
 }