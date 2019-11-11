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
 * @package    Zend_Queue
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Queue.php 435 2015-09-28 15:14:38Z nm $
 */

/**
 * @see Zend_Db_Table_Abstract
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Db' . DIRECTORY_SEPARATOR . 'Table' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Queue
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Queue_Adapter_Db_Queue extends Zend_Db_Table_Abstract
{
    /**
     * @var string
     */
    protected $_name = 'queue';

    /**
     * @var string
     */
    protected $_primary = 'queue_id';

    /**
     * @var mixed
     */
    protected $_sequence = true;
}
