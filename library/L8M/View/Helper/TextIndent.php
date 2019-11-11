<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/TextIndent.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TextIndent.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_TextIndent
 *
 *
 */
class L8M_View_Helper_TextIndent extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * The default indentation character, a tab
	 *
	 */
	const INDENT_CHARACTER = "\t";

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Contains TRUE if nice indentation is enabled.
	 *
	 * @var bool
	 */
	protected static $_enabled = NULL;


    /**
     *
     *
     * Class Methods
     *
     *
     */

	/**
	 * Returns the provided text indented by $indents tabs
	 *
	 * @param  string $text
	 * @param  int    $indents
	 * @param  bool   $trim
	 * @return string
	 */
    public function textIndent($text = NULL, $indents = 1, $trim = FALSE)
    {
        if ($this->isEnabled() == FALSE) {
            return $text;
        }
    	$text = trim($text);
    	$indents = (int) $indents;
    	if ($text) {
    		$text = preg_replace('(\r\n)', PHP_EOL, $text);
    		$text = explode(PHP_EOL, $text);
    		if ($trim) {
	    		$trimmedText = array();
	    		foreach($text as $line) {
	    			$trimmedText[] = trim($line);
	    		}
	    		$text = $trimmedText;
    		}
    		$indent = str_pad('',  $indents, self::INDENT_CHARACTER);
    		$indentedText =  $indent . implode(PHP_EOL . $indent, $text);
    		return $indentedText;
    	}
    	return NULL;
    }

    /**
     * Returns TRUE if nice indentation is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        if (self::$_enabled === NULL) {
            $codeConfig = Zend_Registry::get('Zend_Config')->get('code');
            if ($codeConfig &&
                $codeConfig->get('indentation')) {
                self::$_enabled = (bool) $codeConfig->indentation->enabled;
            }
        }
        return self::$_enabled;
    }

}