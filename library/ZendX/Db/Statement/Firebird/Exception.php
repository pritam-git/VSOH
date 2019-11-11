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
 * @package    ZendX_Db
 * @subpackage Statement
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Exception.php 435 2015-09-28 15:14:38Z nm $
 */

/**
 * @see Zend_Db_Statement_Exception
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Db' . DIRECTORY_SEPARATOR . 'Statement' . DIRECTORY_SEPARATOR . 'Exception.php';

/**
 * ZendX_Db_Adapter_Firebird_Exception
 *
 * @category   ZendX 
 * @package    ZendX_Db
 * @subpackage Statement
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZendX_Db_Statement_Firebird_Exception extends Zend_Db_Statement_Exception
{
}

