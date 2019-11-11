<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Navigation/Adapter/Doctrine.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Doctrine.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Navigation_Adapter_Doctrine
 *
 *
 */
 class L8M_Navigation_Adapter_Doctrine extends L8M_Navigation_Adapter_Abstract
 {

 	/**
 	 *
 	 *
 	 * Class Constructor
 	 *
 	 *
 	 */

 	/**
     * Constructs L8M_Navigation_Adapter_Doctrine instance.
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($options = NULL)
    {
		/**
 	     * Doctrine enabled?
 	     */
 	    if (L8M_Doctrine::isEnabled() === FALSE) {
 	        throw new L8M_Navigation_Adapter_Doctrine_Exception('Doctrine is disabled.');
 	    }
		parent::__construct();
	}

 	/**
 	 *
 	 *
 	 * Class Methods
 	 *
 	 *
 	 */

 	/**
 	 * Initializes L8M_Navigation instance.
 	 *
 	 * @return L8M_Navigation_Adapter_Doctrine
 	 */
 	public function init()
 	{
 		/**
 		 * navigation
 		 */
 		$this->_navigation = new L8M_Navigation();

 		/**
 		 * model classes exist?
 		 */
 		if (class_exists('Default_Model_Base_Page') &&
 		    class_exists('Default_Model_Page')) {

     		/**
     		 * pages
     		 *
     		 * @todo consider $this->_options['roleShort'] and retrieve only role-
     		 *       specific navigation
     		 */
     		try {
         		$pages = Doctrine_Query::create()->from('Default_Model_Page p')
         		                                 ->where('p.disabled = ?')
         		                                 ->execute(array(0));
                if ($pages->count()>0) {
                    $this->_navigation->removePages();
                    foreach($pages as $page) {
                    	/**
                    	 * @todo adjust model accordingly
                    	 */
                    	$this->_navigation->addPage(new Zend_Navigation_Page($page->toArray()));
                    }
                }
     		} catch (Exception $exception) {

     		}

	    }

        return $this;

 	}

 }