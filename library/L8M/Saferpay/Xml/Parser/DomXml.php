<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Saferpay/Xml/Parser/DomXml.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: DomXml.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Saferpay_Xml_Parser_DomXml
 * 
 * 
 */
class L8M_Saferpay_Xml_Parser_DomXml extends L8M_Saferpay_Xml_Parser_Abstract 
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
        $domXmlDocument = domxml_open_mem($xml);
        $this->_xmlParser = $domXmlDocument->document_element();
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
        return $this->_xmlParser->getAttribute($elementName);
    }    
    
}