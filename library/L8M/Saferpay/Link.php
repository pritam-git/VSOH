<?php

/**
 * L8M 
 *
 * 
 * @filesource /library/L8M/Saferpay//Link.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Link.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Saferpay_Link
 * 
 * 
 */
class L8M_Saferpay_Link 
{
    
	/**
	 * 
	 * 
	 * Class Variables
	 * 
	 * 
	 */
    
	/**
	 * The protocol of the link
	 * 
	 * @var string
	 */
	protected $_protocol = 'http';

	/**
	 * An array of allowed protocols
	 *
	 * @var array
	 */
	protected $_allowedProtocols = array('http',
										 'https', 
										 'ftp');
    
    /**
     * The base of the link
     * 
     * @var string
     */
	protected $_base = NULL;
	
	/**
	 * An array of link params
	 *
	 * @var array 
	 */
	protected $_params = array();
	
	/**
	 * 
	 * 
	 * Class Constructor
	 * 
	 * 
	 */
	
	/**
	 * Constructs L8M_Saferpay_Link instance.
	 *
	 * @param  array $params
	 * @return void
	 */
	public function __construct($params = NULL)
	{
	    $this->setParams($params);
	}
	
	/**
	 * 
	 * 
	 * Class Setters
	 *
	 * 
	 */
	
	/**
	 * Sets protocol to be used with link.
	 *
	 * @param  string           $protocol
	 * @return L8M_Saferpay_Link
	 */
	public function setProtocol($protocol = NULL)
	{
	    $protocol = trim(strtolower($protocol));
	    if ($protocol!=NULL &&
	        in_array($protocol, $this->_allowedProtocols)) {
            $this->_protocol = $protocol . '://';	            
        } else {
            throw new L8M_Saferpay_Link_Exception('Protocol ' . htmlentities($protocol) . '" is not allowed.');
        }
        return $this;
	}
	
	/**
	 * Sets link base.
	 *
	 * @todo   validation 
	 * @param  string          $base
	 * @return L8M_Saferpay_Link
	 */
	public function setBase($base = NULL)
	{
	    if (is_string($base)) {
            $this->_base = $base;
	    }            
	    return $this;
	}			
	
	/**
	 * Sets link params.
	 *
	 * @param  array            $params
	 * @return L8M_Saferpay_Link
	 */
	public function setParams($params = NULL)
	{
	    if (is_array($params)) {
	        $this->_params = $params;
	    }	        
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
	 * Adds param to linkParams.
	 *
	 * @param  string           $name
	 * @param  mixed            $value
	 * @return L8M_Saferpay_Link
	 */
	public function addParam($name = NULL, $value = NULL)
	{
	    if ($name!=NULL &&
	        is_string($name)) {
	        $this->addParams(array($name=>$value));
	    }
	    return $this;
	}
	
	/**
	 * Adds params to linkParams.
	 *
	 * @todo   consider multi-dimensional arrays
	 * @param  array            $params
	 * @return L8M_Saferpay_Link
	 */
	public function addParams($params = NULL) 
	{
	    if (is_array($params)) {
	        $this->_params = array_merge($params, $this->_params);
	    }
	    return $this;
	}
	
	/**
	 * Returns assembled link
	 *
	 * @return string
	 * @todo   XHTML Strict (ampersand)
	 *         verify whether a link base has been set?
	 */
    public function getLink ()
    {
        if (count($this->_params)>0) {
            $params = array();
            foreach($this->_params as $paramName=>$paramValue) {
                $params[] = urlencode($paramName) . '=' . urlencode($paramValue); 
            }
            $params = '?' . implode('&', $params);
        } else {
            $params = NULL;
        }
        return $this->_protocol . $this->_base . $params;
	}		
	
}