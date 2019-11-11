<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/AuthControlled/Acl.php
 * @author     Norbert Marks <nm@l8m.com>
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Acl.php 47 2014-04-22 17:06:30Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_AuthControlled_Acl
 *
 *
 */
class L8M_Controller_Plugin_AuthControlled_Acl extends Zend_Acl
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_resourceParts = array();
	private $_resourcePartsReverse = array();

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Methodes
	 *
	 *
	 */
	public function addActionParam($action = NULL, $controller = NULL, $module = NULL, $newController = NULL, $newAction = NULL, $newParam = NULL)
	{
		/**
		 * add action param parts
		 */
		if (array_key_exists($module, $this->_resourceParts)) {
			$this->_resourceParts[$module][$controller] = array(
				'controller'=>$newController,
				'action'=>$newAction,
				'param'=>$newParam,
			);
		} else {
			$this->_resourceParts[$module] = array(
				$controller=>array(
					'controller'=>$newController,
					'action'=>$newAction,
					'param'=>$newParam,
				),
			);
		}
	}

	public function getActionParamNewController($module = NULL, $controller = NULL)
	{
		if (array_key_exists($module, $this->_resourceParts) &&
			array_key_exists($controller, $this->_resourceParts[$module])) {

			return $this->_resourceParts[$module][$controller]['controller'];
		}
		return NULL;
	}

	public function getActionParamNewAction($module = NULL, $controller = NULL)
	{
		if (array_key_exists($module, $this->_resourceParts) &&
			array_key_exists($controller, $this->_resourceParts[$module])) {

			return $this->_resourceParts[$module][$controller]['action'];
		}
		return NULL;
	}

	public function getActionParamNewParam($module = NULL, $controller = NULL)
	{
		if (array_key_exists($module, $this->_resourceParts) &&
			array_key_exists($controller, $this->_resourceParts[$module])) {

			return $this->_resourceParts[$module][$controller]['param'];
		}
		return NULL;
	}

	public function checkActionParam($module = NULL, $controller = NULL)
	{
		if (array_key_exists($module, $this->_resourceParts) &&
			array_key_exists($controller, $this->_resourceParts[$module])) {

			return TRUE;
		}
		return FALSE;
	}
}