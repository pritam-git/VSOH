<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Abstract/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_Abstract
 *
 *
 */
abstract class L8M_JQuery_View_Helper_Abstract extends Zend_View_Helper_Abstract
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

	/**
	 * A string representing the path in the public directory in which the
	 * jQuery plugins reside.
	 *
	 * @var string
	 */
	protected static $_pluginPath = '/js/jquery/plugins/';

}