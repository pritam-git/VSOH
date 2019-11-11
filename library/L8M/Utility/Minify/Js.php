<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/Minify/Js.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Js.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Utility_Minify_Js
 *
 *
 */
class L8M_Utility_Minify_Js extends L8M_Utility_Minify_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */


	/**
	 * Minifies passed JavaScript code with specified options.
	 *
	 * @param  string|array      $js
	 * @param  array|Zend_Config $options
	 * @return string
	 */
	protected function _minify($js = array(), $options = array())
	{
		if (is_string($js)) {
			$js = array($js);
		}
        if (!is_array($js)) {
            throw new L8M_Utility_Minify_Js_Exception('JavaScript needs to be specified as string or an array of strings.');
        }
        if ($options instanceof Zend_Config) {
        	$options = $options->toArray();
        }
        if (!$options ||
        	count($options) == 0) {
        	$options = $this->_options;
        }
        require_once('JSMinPlus.php');
        return JSMinPlus::minify(implode(PHP_EOL, $js));
	}


}