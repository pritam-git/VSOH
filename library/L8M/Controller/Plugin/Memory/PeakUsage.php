<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Memory/PeakUsage.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PeakUsage.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Memory_PeakUsage
 *
 *
 */
class L8M_Controller_Plugin_Memory_PeakUsage extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

    /**
     * A Zend_Log instance.
     *
     * @var Zend_Log
     */
    protected $_log = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
     */

    /**
     * Constructs L8M_Controller_Plugin_Mobile_Detector instance.
     *
     * @param  string      $class
     * @param  Zend_Config $options
     * @return void
     */
    public function __construct($log = NULL)
    {
        if (!$log) {
            if (!Zend_Registry::isRegistered('Zend_Log')) {
                throw new L8M_Controller_Plugin_Memory_PeakUsage_Exception('Could not retrieve Zend_Log instance from Zend_Registry as no key "Zend_Log" has been registered.');
            }
            $log = Zend_Registry::get('Zend_Log');
        }
        if (!$log ||
            !($log instanceof Zend_Log)) {
                throw new L8M_Controller_Plugin_Memory_PeakUsage_Exception('Log needs to be passed as a Zend_Log instance or registered in Zend_Registry with key "Zend_Log".');
        }
        $this->_log = $log;
    }

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Called before Zend_Controller_Front exits its dispatch loop.
     *
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        $this->_log->info('Memory peak usage: ' . memory_get_peak_usage(TRUE) . ' (' . $this->getRequest()->getRequestUri() . ')');
    }

}