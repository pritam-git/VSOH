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
 * @package    Zend_Dojo
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Enable Dojo components
 *
 * @package    Zend_Dojo
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Dojo.php 446 2015-09-29 11:45:22Z nm $
 */
class Zend_Dojo
{
    /**
     * Base path to AOL CDN
     */
    const CDN_BASE_AOL = 'http://o.aolcdn.com/dojo/';

    /**
     * Path to dojo on AOL CDN (following version string)
     */
    const CDN_DOJO_PATH_AOL = '/dojo/dojo.xd.js';

    /**
     * Base path to Google CDN
     */
    const CDN_BASE_GOOGLE = 'http://ajax.googleapis.com/ajax/libs/dojo/';

    /**
     * Path to dojo on Google CDN (following version string)
     */
    const CDN_DOJO_PATH_GOOGLE = '/dojo/dojo.xd.js';

    /**
     * Dojo-enable a form instance
     *
     * @param  Zend_Form $form
     * @return void
     */
    public static function enableForm(Zend_Form $form)
    {
        $form->addPrefixPath('Zend_Dojo_Form_Decorator', 'Zend' . DIRECTORY_SEPARATOR . 'Dojo' . DIRECTORY_SEPARATOR . 'Form' . DIRECTORY_SEPARATOR . 'Decorator', 'decorator')
             ->addPrefixPath('Zend_Dojo_Form_Element', 'Zend' . DIRECTORY_SEPARATOR . 'Dojo' . DIRECTORY_SEPARATOR . 'Form' . DIRECTORY_SEPARATOR . 'Element', 'element')
             ->addElementPrefixPath('Zend_Dojo_Form_Decorator', 'Zend' . DIRECTORY_SEPARATOR . 'Dojo' . DIRECTORY_SEPARATOR . 'Form' . DIRECTORY_SEPARATOR . 'Decorator', 'decorator')
             ->addDisplayGroupPrefixPath('Zend_Dojo_Form_Decorator', 'Zend' . DIRECTORY_SEPARATOR . 'Dojo' . DIRECTORY_SEPARATOR . 'Form' . DIRECTORY_SEPARATOR . 'Decorator')
             ->setDefaultDisplayGroupClass('Zend_Dojo_Form_DisplayGroup');

        foreach ($form->getSubForms() as $subForm) {
            self::enableForm($subForm);
        }

        if (null !== ($view = $form->getView())) {
            self::enableView($view);
        }
    }

    /**
     * Dojo-enable a view instance
     *
     * @param  Zend_View_Interface $view
     * @return void
     */
    public static function enableView(Zend_View_Interface $view)
    {
        if (false === $view->getPluginLoader('helper')->getPaths('Zend_Dojo_View_Helper')) {
            $view->addHelperPath('Zend' . DIRECTORY_SEPARATOR . 'Dojo' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Helper', 'Zend_Dojo_View_Helper');
        }
    }
}

