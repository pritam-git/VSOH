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
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Dojo_Form_Decorator_DijitContainer */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Dojo' . DIRECTORY_SEPARATOR . 'Form' . DIRECTORY_SEPARATOR . 'Decorator' . DIRECTORY_SEPARATOR . 'DijitContainer.php';

/**
 * SplitContainer
 *
 * Render a dijit SplitContainer
 *
 * @uses       Zend_Dojo_Form_Decorator_DijitContainer
 * @package    Zend_Dojo
 * @subpackage Form_Decorator
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: SplitContainer.php 435 2015-09-28 15:14:38Z nm $
 */
class Zend_Dojo_Form_Decorator_SplitContainer extends Zend_Dojo_Form_Decorator_DijitContainer
{
    /**
     * View helper
     * @var string
     */
    protected $_helper = 'SplitContainer';
}
