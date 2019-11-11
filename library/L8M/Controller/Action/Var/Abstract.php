<?php
/**
 * L8M
 *
 *
 * @filesource library/L8M/Controller/Action/Var/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 7 2014-03-11 16:18:40Z nm $
 */


/**
 *
 *
 * L8M_Controller_Action_Var_Abstract
 *
 *
 */
abstract class L8M_Controller_Action_Var_Abstract
{

	private $_resourceParts = array();

	/**
	 * set resource parts
	 *
	 * @param array $resourceParts
	 */
	abstract public function setResourceParts($resourceParts);

	/**
	 * check Controller
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @return boolean
	 */
	abstract public function checkController($action = NULL, $controller = NULL, $module = 'default');

	/**
	 * retrieve value
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @return String
	 */
	abstract public function getValue($action = NULL, $controller = NULL, $module = 'default');

	/**
	 * retrieve param
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @return String
	 */
	abstract public function getParam($action = NULL, $controller = NULL, $module = 'default');
}