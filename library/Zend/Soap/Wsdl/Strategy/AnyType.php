<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Soap
 * @subpackage Wsdl
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AnyType.php 435 2015-09-28 15:14:38Z nm $
 */

/**
 * @see Zend_Soap_Wsdl_Strategy_Interface
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Soap' . DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Strategy' . DIRECTORY_SEPARATOR . 'Interface.php';

/**
 * Zend_Soap_Wsdl_Strategy_AnyType
 *
 * @category   Zend
 * @package    Zend_Soap
 * @subpackage Wsdl
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Soap_Wsdl_Strategy_AnyType implements Zend_Soap_Wsdl_Strategy_Interface
{
    /**
     * Not needed in this strategy.
     *
     * @param Zend_Soap_Wsdl $context
     */
    public function setContext(Zend_Soap_Wsdl $context)
    {

    }

    /**
     * Returns xsd:anyType regardless of the input.
     *
     * @param string $type
     * @return string
     */
    public function addComplexType($type)
    {
        return 'xsd:anyType';
    }
}