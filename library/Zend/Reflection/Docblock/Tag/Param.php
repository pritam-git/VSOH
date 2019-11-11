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
 * @package    Zend_Reflection
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Param.php 435 2015-09-28 15:14:38Z nm $
 */

/** Zend_Reflection_Docblock_Tag */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Reflection' . DIRECTORY_SEPARATOR . 'Docblock' . DIRECTORY_SEPARATOR . 'Tag.php';

/**
 * @category   Zend
 * @package    Zend_Reflection
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Reflection_Docblock_Tag_Param extends Zend_Reflection_Docblock_Tag
{
    /**
     * @var string
     */
    protected $_type = null;

    /**
     * @var string
     */
    protected $_variableName = null;

    /**
     * Constructor
     *
     * @param string $tagDocblockLine
     */
    public function __construct($tagDocblockLine)
    {
        $matches = array();

        if (!preg_match('#^@(\w+)\s+([\w|\\\]+)(?:\s+(\$\S+))?(?:\s+(.*))?#s', $tagDocblockLine, $matches)) {
            require_once 'Zend' . DIRECTORY_SEPARATOR . 'Reflection' . DIRECTORY_SEPARATOR . 'Exception.php';
            throw new Zend_Reflection_Exception('Provided docblock line is does not contain a valid tag');
        }

        if ($matches[1] != 'param') {
            require_once 'Zend' . DIRECTORY_SEPARATOR . 'Reflection' . DIRECTORY_SEPARATOR . 'Exception.php';
            throw new Zend_Reflection_Exception('Provided docblock line is does not contain a valid @param tag');
        }

        $this->_name = 'param';
        $this->_type = $matches[2];

        if (isset($matches[3])) {
            $this->_variableName = $matches[3];
        }

        if (isset($matches[4])) {
            $this->_description = preg_replace('#\s+#', ' ', $matches[4]);
        }
    }

    /**
     * Get parameter variable type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get parameter name
     *
     * @return string
     */
    public function getVariableName()
    {
        return $this->_variableName;
    }
}
