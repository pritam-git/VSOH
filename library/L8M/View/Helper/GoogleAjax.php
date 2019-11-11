<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/View/Helper/GoogleAjax.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleAjax.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_GoogleAjax
 * 
 *
 */
class L8M_View_Helper_GoogleAjax extends Zend_View_Helper_Abstract 
{
    
    /**
     * 
     * 
     * Class Variables
     * 
     * 
     */
    
    /**
     * Is TRUE when the Google Ajax API has been loaded
     *
     * @var bool
     */
    protected static $_apiLoaded = FALSE;
    
    /**
     * 
     * 
     * Setter Methods
     * 
     * 
     */
    
	/**
     * Sets Google Ajax API as loaded.
     * 
     * @return L8M_View_Helper_GoogleAjax
     *
     */
    public function setApiLoaded($loaded = TRUE)
    {
        self::$_apiLoaded = (bool) $loaded;
        return $this;
    }    
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Returns an L8M_View_Helper_GoogleAjax instance.
     *
     * @return L8M_View_Helper_GoogleAjax
     */
    public function googleAjax()
    {
        return $this;        
    }
    
    /**
     * Adds headscript for loading the Google Ajax Api if it has not been added 
     * yet. 
     *
     * @return L8M_View_Helper_GoogleAjax
     */
    public function loadApi() 
    {
        if (!$this->isApiLoaded()) {
            $this->view->headScript()->appendFile(L8M_Google_Ajax_Api::URI_AJAX_API_SOURCE . '?' . L8M_Library::arrayToUrlParams(array('key'=>L8M_Google_Ajax_Api::getApiKey())));
            $this->setApiLoaded(TRUE);    
        }
        return $this;
    }
    
    /**
     * Returns TRUE when Google Ajax API has been loaded, FALSE otherwise.
     *
     * @return bool
     */
    public function isApiLoaded()
    {
        return self::$_apiLoaded;
    }
    
}