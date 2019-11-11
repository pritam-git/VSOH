<?php

/**
 * L8M 
 *
 * 
 * @filesource /library/L8M/Saferpay/Xml/Parser/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Saferpay_Xml_Parser_Abstract
 * 
 * 
 */
abstract class L8M_Saferpay_Xml_Parser_Abstract {
    
    /**
     * 
     * 
     * Class Variables
     * 
     * 
     */
    
    /**
     * The XML string to parse
     * 
     * @var string
     */
    protected $_xml;
    
	/**
     * An instance of the parser
     *
     * @var unknown_type
     */
    protected $_xmlParser;    
    
    /**
     * 
     * 
     * Class Constructor
     * 
     * 
     */
    
    /**
     * Constructs L8M_Saferpay_Xml_Parser_Abstract instance.
     * 
     * @return void
     */
    public function __construct() 
    {
    } 

    /**
     * 
     * 
     * Initialization Function
     * 
     * 
     */
    
	/**
     * Initializes parser instance.
     * 
     * @return void 
     */
    abstract protected function _initParser($xml = NULL);
    
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
    public function parse($xml = NULL, $elements = NULL)
    {
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
         * initialize parser (this routine is/can be overwritten by inheriting 
         * classes)
         */
        $this->_initParser($xml);
        /**
         * reset parsed xml
         */
        $xmlParsed = array();
		/**
		 * iterate over elements
		 * 
		 * @todo further checking?
		 */
		foreach ($elements as $preParsedElementName=>$postParsedElementName) {
		    $elementValue = $this->getElementValue($preParsedElementName);
			if ($elementValue!=NULL) {
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
    abstract public function getElementValue($elementName = NULL);
     
}