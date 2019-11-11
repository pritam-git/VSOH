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
 * @package    Zend_InfoCard
 * @subpackage Zend_InfoCard_Cipher
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Aes128cbc.php 435 2015-09-28 15:14:38Z nm $
 */

/**
 * Zend_InfoCard_Cipher_Symmetric_Adapter_Aes256cbc
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'InfoCard' . DIRECTORY_SEPARATOR . 'Cipher' . DIRECTORY_SEPARATOR . 'Symmetric' . DIRECTORY_SEPARATOR . 'Adapter' . DIRECTORY_SEPARATOR . 'Aes256cbc.php';

/**
 * Implements AES128 with CBC encryption implemented using the mCrypt extension
 *
 * @category   Zend
 * @package    Zend_InfoCard
 * @subpackage Zend_InfoCard_Cipher
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_InfoCard_Cipher_Symmetric_Adapter_Aes128cbc
    extends Zend_InfoCard_Cipher_Symmetric_Adapter_Aes256cbc
{
}
