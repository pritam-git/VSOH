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
 * @package    Zend_Service
 * @subpackage Ebay
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Set.php 435 2015-09-28 15:14:38Z nm $
 */

/**
 * @see Zend_Service_Ebay_Finding_Set_Abstract
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . 'Ebay' . DIRECTORY_SEPARATOR . 'Finding' . DIRECTORY_SEPARATOR . 'Set' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Ebay
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses       Zend_Service_Ebay_Finding_Set_Abstract
 */
class Zend_Service_Ebay_Finding_Category_Histogram_Set extends Zend_Service_Ebay_Finding_Set_Abstract
{
    /**
     * Implement SeekableIterator::current()
     *
     * @return Zend_Service_Ebay_Finding_Category_Histogram
     */
    public function current()
    {
        // check node
        $node = $this->_nodes->item($this->_key);
        if (!$node) {
            return null;
        }

        /**
         * @see Zend_Service_Ebay_Finding_Category_Histogram
         */
        require_once 'Zend' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . 'Ebay' . DIRECTORY_SEPARATOR . 'Finding' . DIRECTORY_SEPARATOR . 'Category' . DIRECTORY_SEPARATOR . 'Histogram.php';
        return new Zend_Service_Ebay_Finding_Category_Histogram($node);
    }
}