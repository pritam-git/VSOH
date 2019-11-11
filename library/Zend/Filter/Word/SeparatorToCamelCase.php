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
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: SeparatorToCamelCase.php 510 2016-08-01 10:50:19Z nm $
 */

/**
 * @see Zend_Filter_PregReplace
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Filter' . DIRECTORY_SEPARATOR . 'Word' . DIRECTORY_SEPARATOR . 'Separator' . DIRECTORY_SEPARATOR . 'Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Filter_Word_SeparatorToCamelCase extends Zend_Filter_Word_Separator_Abstract
{
    /**
     * Defined by Zend\Filter\Filter
     *
     * @param  string|array $value
     * @return string|array
     */
    public function filter($value)
    {
        if (PHP_VERSION_ID < 50400) {
            // a unicode safe way of converting characters to \x00\x00 notation
            $pregQuotedSeparator = preg_quote($this->_separator, '#');

            if (self::isUnicodeSupportEnabled()) {
                parent::setMatchPattern(array('#('.$pregQuotedSeparator.')(\p{L}{1})#e','#(^\p{Ll}{1})#e'));
                parent::setReplacement(array("strtoupper('\\2')","strtoupper('\\1')"));
            } else {
                parent::setMatchPattern(array('#('.$pregQuotedSeparator.')([A-Za-z]{1})#e','#(^[A-Za-z]{1})#e'));
                parent::setReplacement(array("strtoupper('\\2')","strtoupper('\\1')"));
            }
            $filtered = parent::filter($value);
        } else {
            if (!is_scalar($value) && !is_array($value)) {
                return $value;
            }

            // a unicode safe way of converting characters to \x00\x00 notation
            $pregQuotedSeparator = preg_quote($this->_separator, '#');

            if (self::isUnicodeSupportEnabled()) {
                $patterns = array(
                    '#(' . $pregQuotedSeparator.')(\P{Z}{1})#u',
                    '#(^\P{Z}{1})#u',
                );
                if (!extension_loaded('mbstring')) {
                    $replacements = array(
                        function ($matches) {
                            return strtoupper($matches[2]);
                        },
                        function ($matches) {
                            return strtoupper($matches[1]);
                        },
                    );
                } else {
                    $replacements = array(
                        function ($matches) {
                            return mb_strtoupper($matches[2], 'UTF-8');
                        },
                        function ($matches) {
                            return mb_strtoupper($matches[1], 'UTF-8');
                        },
                    );
                }
            } else {
                $patterns = array(
                    '#(' . $pregQuotedSeparator.')([\S]{1})#',
                    '#(^[\S]{1})#',
                );
                $replacements = array(
                    function ($matches) {
                        return strtoupper($matches[2]);
                    },
                    function ($matches) {
                        return strtoupper($matches[1]);
                    },
                );
            }

            $filtered = $value;
            foreach ($patterns as $index => $pattern) {
                $filtered = preg_replace_callback($pattern, $replacements[$index], $filtered);
            }
        }
        return $filtered;
    }
}
