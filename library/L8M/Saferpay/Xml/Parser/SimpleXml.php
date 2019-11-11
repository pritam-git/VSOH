<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Saferpay/Xml/Parser/SimpleXml.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SimpleXml.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Saferpay_Xml_Parser_SimpleXml
 * 
 * 
 */
class L8M_Saferpay_Xml_Parser_SimpleXml extends L8M_Saferpay_Xml_Parser_Abstract 
{
    
    /**
     * 
     * 
     * Initialization Function
     * 
     * 
     */
    
	/**
     * Initializes parser instance
     *
     * @param  string $xml
     * @return void
     */
    protected function _initParser($xml = NULL)
    {
        $this->_xmlParser = new SimpleXMLElement($xml);
    }
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
	/**
     * Returns value of element with specified elementName
     *
     * @param string $elementName
     */
    public function getElementValue($elementName = NULL) 
    {    
        return $this->_xmlParser[$elementName];
    }    
		    
}