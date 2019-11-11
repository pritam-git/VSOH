<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/View/Helper/WhoIsLink.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: WhoIsLink.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_View_Helper_WhoIsLink
 * 
 *
 */
class L8M_View_Helper_WhoIsLink extends Zend_View_Helper_Abstract
{
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    const URL_WHOIS_RIPE = 'http://www.db.ripe.net/whois?searchtext=';

    /**
     * Return whoIsLink if an IP address is specified.
     *
     * @param  string $IP
     * @return string
     */
    public function whoIsLink($IP = NULL)
    {
    	if (Zend_Validate_Ip::isValid($IP)) {
    		return self::URL_WHOIS_RIPE . urlencode($IP);
    	}
    	return NULL;
    }    	
}