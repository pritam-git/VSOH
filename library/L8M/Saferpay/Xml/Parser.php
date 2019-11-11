<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Saferpay/Xml/Parser.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Parser.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Saferpay_Xml_Parser
 *
 *
 */
class L8M_Saferpay_Xml_Parser
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * The prefix prepended to the parserName
     *
     * @var string
     */
    protected static $_parserPrefix = 'L8M_Saferpay_Xml_Parser_';

    /**
     * An array with names of available parsers
     *
     * @var array
     */
    protected static $_allowedParsers = array('SimpleXml',
                                          	  'Dom',
                                              'DomXml',
                                              'Native');

    /**
     *
     *
     * Class Constructor
     *
     *
     */

    /**
     * Constructs L8M_Saferpay_Xml_Parser instance.
     *
     * @return void
     */
    protected function __construct()
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
     * Constructs a L8M_Saferpay_Xml_Parser instance.
     *
     * @param  string                          $parserName
     * @param  Zend_Config|array               $options
     * @return L8M_Saferpay_Xml_Parser_Abstract
     */
    public static function factory($parserName = NULL, $options = NULL)
    {
        /**
         * parserName specified, but not in list of allowed parsers
         */
        if ($parserName !== NULL &&
            !in_array($parserName, self::$_allowedParsers)) {
            throw new L8M_Saferpay_Xml_Parser_Exception('The specified parserName "' . (string) $parserName . '" is not on the list of allowed parsers.');
        }
        /**
         * parserName not specified, detect available parsers
         */
        if ($parserName===NULL) {
        	$loadedExtensions = get_loaded_extensions();
			if (in_array('SimpleXML', $loadedExtensions)) {
				$parserName = 'SimpleXml';
			} else
			if (in_array('dom', $loadedExtensions)) {
			    $parserName = 'Dom';
			} else
			if (in_array('domxml', $loadedExtensions)) {
			    $parserName = 'DomXml';
			} else {
			    $parserName = 'Native';
			}
        }
        /**
         * prepend prefix, if necessary
         */
        if (substr($parserName, 0, strlen(self::$_parserPrefix)-1)!==self::$_parserPrefix) {
            $parserName = self::$_parserPrefix  . $parserName;
        }
        /**
         * load class or throw an exception
         */
        if (!class_exists($parserName)) {
            require_once 'Zend' . DIRECTORY_SEPARATOR . 'Loader.php';
            Zend_Loader::loadClass($parserName);
        }
        return new $parserName($options);
    }

}