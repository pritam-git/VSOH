<?php
/**
 * L8M
 *
 *
 * @filesource library/PRJ/Controller/Action/Param/Abstract.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Abstract.php 47 2014-04-22 17:06:30Z nm $
 */


/**
 *
 *
 * L8M_Controller_Action_Param_Abstract
 *
 *
 */
abstract class L8M_Controller_Action_Param_Abstract
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
	 * @param String $lang
	 * @return boolean
	 */
	abstract public function checkController($action = NULL, $controller = NULL, $module = 'default', $lang = NULL);

	/**
	 * retrieve controller
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	abstract public function getController($action = NULL, $controller = NULL, $module = 'default', $lang = NULL);

	/**
	 * retrieve action
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	abstract public function getAction($action = NULL, $controller = NULL, $module = 'default');

	/**
	 * retrieve param
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	abstract public function getParam($action = NULL, $controller = NULL, $module = 'default');

	/**
	 * retrieve role
	 *
	 * @param String $action
	 * @param String $controller
	 * @param String $module
	 * @param String $lang
	 * @return String
	 */
	abstract public function getRole($action = NULL, $controller = NULL, $module = 'default', $lang = NULL);
}