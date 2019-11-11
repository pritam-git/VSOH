<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Saferpay/Xml/Parser/Native.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Native.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Saferpay_Xml_Parser_Native
 * 
 * 
 */
class L8M_Saferpay_Xml_Parser_Native extends L8M_Saferpay_Xml_Parser_Abstract 
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
     * @return void 
     */
    protected function _initParser($xml = NULL) 
    {
    }
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
    /**
     * Parses specified xml and returns an array of parsed elements  
     * (preParsedElementName=>postParsedElementName)   
     *
     * @param  array $xml
     * @param  array $elements An array of preParsedElementName=>postParsedElementName
     * @return array
     */
    public function parse($xml = NULL, $elements = NULL) {
        
		/**
         * xml
         */
        if ($xml===NULL ||
            !is_string($xml)) {
            throw new L8M_Saferpay_Xml_Parser_Exception('Parameter "xml" is empty or not a string.');     
        }
        
        /**
         * elements
         */
		if (!is_array($elements) || 
		    count($elements)==0) {
            throw new L8M_Saferpay_Xml_Parser_Exception('Parameter "elements" is not or an empty array.');
        }
        
        /**
		 * prepare xml
		 */
        $xml = ereg_replace('^<IDP( )*', '', $xml);
		$xml = ereg_replace('( )*/( )>$', '', $xml);
		$xml = trim($xml);
		
        /**
         * reset parsed xml
         */
        $xmlParsed = array();		
		
        /**
         * parse it
         */
		while (strlen($xml)>0) {
			
			/**
			 * find equal sign
			 */
			$position = strpos($xml, '="');
			/**
			 * copy preParsedElementName from 0 to position of equals sign 
			 */
			$preParsedElementName = substr($xml, 0, $position);
			/**
			 * update xml (cut out preParsedElementName and equals sign and opening ")
			 */ 
			$xml = substr($xml, $position + 2);
			/**
			 * find next "
			 */ 
			$position = strpos($xml, '"');
			/**
			 * copy elementValue
			 */
			$elementValue = substr($xml, 0, $position);
			/**
			 * updated xml (cut out elementValue and closing ")
			 */
			$xml = substr($xml, $position + 1); 
			$xml = trim($xml);
			/**
			 * preParsedElementName exists in elements to parse?
			 */
			if (array_key_exists($preParsedElementName, $elements) &&
			    $elementValue!==NULL) {
			    $postParsedElementName = $elements[$preParsedElementName];
			    $xmlParsed[$postParsedElementName] = $elementValue;
			}
			
		}        
            
		return $xmlParsed;             
        
    }
    
	/**
     * Returns value of element with specified elementName
     *
     * @param string $elementName
     */
    public function getElementValue($elementName = NULL)
    {
         return FALSE;    
    }
     
}