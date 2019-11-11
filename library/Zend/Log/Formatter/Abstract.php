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
 * @package    Zend_Log
 * @subpackage Formatter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Abstract.php 435 2015-09-28 15:14:38Z nm $
 */

/** @see Zend_Log_Formatter_Interface */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Log' . DIRECTORY_SEPARATOR . 'Formatter' . DIRECTORY_SEPARATOR . 'Interface.php';

/** @see Zend_Log_FactoryInterface */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Log' . DIRECTORY_SEPARATOR . 'FactoryInterface.php';

/**
 * @category   Zend
 * @package    Zend_Log
 * @subpackage Formatter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Abstract.php 435 2015-09-28 15:14:38Z nm $
 */
abstract class Zend_Log_Formatter_Abstract
    implements Zend_Log_Formatter_Interface, Zend_Log_FactoryInterface
{
}