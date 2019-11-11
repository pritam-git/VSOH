<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Utility/Minify/Css.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Css.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Utility_Minify_Css
 *
 *
 */
class L8M_Utility_Minify_Css extends L8M_Utility_Minify_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Minifies contents of specified files with specified options.
	 *
	 * @param  string|array      $css
	 * @param  array|Zend_Config $options
	 * @return string
	 */
	protected function _minify($css = array(), $options = array())
	{
		if (is_string($css)) {
			$css = array($css);
		}
        if (!is_array($css)) {
            throw new L8M_Utility_Minify_Css_Exception('Css needs to be specified as string or an array of strings.');
        }
        if ($options instanceof Zend_Config) {
        	$options = $options->toArray();
        }
        if (!$options ||
        	count($options) == 0) {
        	$options = $this->_options;
        }
        require_once('Minify' . DIRECTORY_SEPARATOR . 'CSS.php');
		return Minify_CSS::minify(implode(PHP_EOL, $css), $options);
	}


}