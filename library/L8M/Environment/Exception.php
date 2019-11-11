<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Environment/Exception.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Exception.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 * needed as the autoloader very likely has not been loaded yet
 */
require_once('L8M' . DIRECTORY_SEPARATOR . 'Exception.php');

/**
 *
 *
 * L8M_Environment_Exception
 *
 *
 */
class L8M_Environment_Exception extends L8M_Exception
{

}