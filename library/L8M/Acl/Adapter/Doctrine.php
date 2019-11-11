<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Acl/Adapter/Doctrine.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Doctrine.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Acl_Adapter_Doctrine
 *
 *
 */
 class L8M_Acl_Adapter_Doctrine extends L8M_Acl_Adapter_Abstract
 {

    /**
     *
     *
     * Class Constructor
     *
     *
     */

 	/**
 	 * Constructs L8M_Acl_Adapter_Doctrine instance.
 	 *
 	 * @param  array|Zend_Config $options
 	 * @return void
 	 */
    public function __construct($options = NULL)
    {
        if (L8M_Doctrine::isEnabled() === FALSE) {
            throw new L8M_Acl_Adapter_Doctrine_Exception('Doctrine is disabled.');
        }
        parent::__construct($options);
    }

 	/**
 	 *
 	 *
 	 * Class Methods
 	 *
 	 *
 	 */

    /**
     * Initializes L8M_Acl_Adapter_Doctrine instance.
     *
     * @return L8M_Acl_Adapter_Doctrine
     */
 	public function init()
 	{

 		$this->_acl = new L8M_Acl();

 		/**
 		 * model classes exist?
 		 *
 		 * @todo verify that Doctrine is enabled
 		 */
 		if (class_exists('Default_Model_Base_Role', TRUE) &&
 		    class_exists('Default_Model_Base_Resource', TRUE) &&
 		    class_exists('Default_Model_Base_Permission', TRUE) &&
 		    class_exists('Default_Model_Role', TRUE) &&
 		    class_exists('Default_Model_Resource', TRUE) &&
 		    class_exists('Default_Model_Permission', TRUE)) {

     		/**
     		 * roles
     		 */
     		try {
         		$roles = Doctrine_Query::create()->from('Default_Model_Role r')
         		                                 ->where('r.disabled = ?')
         		                                 ->orderBy('r.id ASC')
         		                                 ->execute(array(0));
                if ($roles->count()>0) {
                	$this->_acl = new Zend_Acl();
                	foreach($roles as $role) {
                		$roleShort = $role->short;
                		if ($role->role_id != NULL) {
                			$parentRoleShort = $role->Role->short;
                		} else {
                			$parentRoleShort = NULL;
                		}
                		$this->_acl->addRole($roleShort, $parentRoleShort);
                	}
                }
     		} catch (Exception $exception) {
     		}


     		/**
     		 * resources
     		 */
     		try {
                $resources = Doctrine_Query::create()->from('Default_Model_Resource r')
                                                     ->where('r.disabled = ?')
                                                     ->orderBy('r.id ASC')
                                                     ->execute(array(0));
         	    if ($resources->count()>0) {
                    foreach($resources as $resource) {
                        $this->_acl->addResource($resource->identifier,
                                                 $resource->Parent->identifier);
                    }
                }
     		} catch (Exception $exception) {

     		}

     		/**
     		 * retrieve permissions
     		 */
     		try {
                $permissions = Doctrine_Query::create()->from('Default_Model_Permission p')
                                                       ->execute();
                if ($permissions->count()>0) {
                    foreach($permissions as $permission) {
                    	if ($permission->is_allowed == 1) {
                            $this->_acl->allow($permission->Role->short,
                                               $permission->Resource->identifier,
                                               $permission->Action->name);
                    	} else {
                    		$this->_acl->deny($permission->Role->short,
                    		                  $permission->Resource->identifier,
                    		                  $permission->Action->name);
                    	}
                    }
                }
     		} catch (Exception $exception) {

     		}

	    }

        return $this;
 	}

 }