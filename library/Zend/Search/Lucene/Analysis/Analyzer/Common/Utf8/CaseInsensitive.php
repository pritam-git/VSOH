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
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: CaseInsensitive.php 435 2015-09-28 15:14:38Z nm $
 */


/** Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Search' . DIRECTORY_SEPARATOR . 'Lucene' . DIRECTORY_SEPARATOR . 'Analysis' . DIRECTORY_SEPARATOR . 'Analyzer' . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'Utf8.php';

/** Zend_Search_Lucene_Analysis_TokenFilter_LowerCaseUtf8 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Search' . DIRECTORY_SEPARATOR . 'Lucene' . DIRECTORY_SEPARATOR . 'Analysis' . DIRECTORY_SEPARATOR . 'TokenFilter' . DIRECTORY_SEPARATOR . 'LowerCaseUtf8.php';


/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


class Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive extends Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8
{
    public function __construct()
    {
        parent::__construct();

        $this->addFilter(new Zend_Search_Lucene_Analysis_TokenFilter_LowerCaseUtf8());
    }
}

