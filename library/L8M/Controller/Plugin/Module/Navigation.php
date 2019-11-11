<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Module/Navigation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Navigation.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Module_Navigation
 *
 *
 */
class L8M_Controller_Plugin_Module_Navigation extends Zend_Controller_Plugin_Abstract
{

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Called on route startup
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {

    }

    /**
     * Called on route shutdown
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {

    }

    /**
     * Called on dispatch loop startup
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {

    }

    /**
     * Called on pre dispatch.
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	/**
    	 * module
    	 */
    	$module = $request->getModuleName();
    	$module = $module ? $module : 'default';
    	
    	/**
    	 * navigation
    	 */
    	$navigation = $this->_getNavigation($module);
    	if ($navigation instanceof Zend_Navigation) {
    		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
    		$viewRenderer->initView();
    		$viewRenderer->view->navigation($navigation);
    	}

	}

    /**
     * Called on post dispatch
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {

    }

    /**
     * Called on dispatch loop shutdown
     *
     */
    public function dispatchLoopShutdown()
    {

    }
    
    /**
     * 
     * 
     * Helper Methods
     * 
     * 
     */
    
    /**
     * Retrieves and returns a Zend_Navigation instance designated for the 
     * specified module.
     * 
     * @return Zend_Navigation
     */
    protected function _getNavigation($module = NULL)
    {
    	return new Zend_Navigation();
    }

}