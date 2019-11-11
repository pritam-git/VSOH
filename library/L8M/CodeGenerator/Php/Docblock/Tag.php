<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/CodeGenerator/Php/Docblock/Tag.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Tag.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_CodeGenerator_Php_Docblock_Tag
 *
 *
 */
class L8M_CodeGenerator_Php_Docblock_Tag extends Zend_CodeGenerator_Php_Docblock_Tag
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
     * generate()
     *
     * @return string
     */
    public function generate($maxTagLength = NULL)
    {
    	if (!$maxTagLength) {
    		$maxTagLength = strlen($this->_name);
    	}

        return '@' . $this->_name . str_pad(' ', $maxTagLength - strlen($this->_name) + 1, ' ') . $this->_description;
    }

}