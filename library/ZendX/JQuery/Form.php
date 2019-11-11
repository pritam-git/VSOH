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
 * @category    ZendX
 * @package     ZendX_JQuery
 * @subpackage  View
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license     http://framework.zend.com/license/new-bsd     New BSD License
 * @version     $Id: Form.php 438 2015-09-29 09:22:47Z nm $
 */

require_once 'Zend' . DIRECTORY_SEPARATOR . 'Form.php';

/**
 * Form Wrapper for jQuery-enabled forms
 *
 * @package    ZendX_JQuery
 * @subpackage Form
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
  */
class ZendX_JQuery_Form extends Zend_Form
{
    /**
     * Constructor
     *
     * @param  array|Zend_Config|null $options
     * @return void
     */
    public function __construct($options = null)
    {
        $this->addPrefixPath('ZendX_JQuery_Form_Decorator', 'ZendX'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator', 'decorator')
             ->addPrefixPath('ZendX_JQuery_Form_Element', 'ZendX'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Element', 'element')
             ->addElementPrefixPath('ZendX_JQuery_Form_Decorator', 'ZendX'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator', 'decorator')
             ->addDisplayGroupPrefixPath('ZendX_JQuery_Form_Decorator', 'ZendX'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'Form'. DIRECTORY_SEPARATOR . 'Decorator');
        parent::__construct($options);
    }

    /**
     * Set the view object
     *
     * Ensures that the view object has the jQuery view helper path set.
     *
     * @param  Zend_View_Interface $view
     * @return ZendX_JQuery_Form
     */
    public function setView(Zend_View_Interface $view = null)
    {
        if (null !== $view) {
            if (false === $view->getPluginLoader('helper')->getPaths('ZendX_JQuery_View_Helper')) {
                $view->addHelperPath('ZendX'. DIRECTORY_SEPARATOR . 'JQuery'. DIRECTORY_SEPARATOR . 'View'. DIRECTORY_SEPARATOR . 'Helper', 'ZendX_JQuery_View_Helper');
            }
        }
        return parent::setView($view);
    }
}