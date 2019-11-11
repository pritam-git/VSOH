<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/Navigation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Navigation.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_Navigation
 *
 *
 */
class L8M_Doctrine_Import_Navigation extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		parent::_init();
		$this->setArray(array(
			array(
				'id'=>1,
				'navigation_id'=>NULL,
				'short'=>'home.guest',
				'name'=>'Home',
				'title'=>'Home',
				'action_resource'=>'default.index.index',
				'role_short'=>'guest',
				'show_all'=>TRUE,
				'show_all_loggedin'=>TRUE,
				'css_class'=>'home',
				'disabled'=>FALSE,
				'position'=>-100,
			),
			array(
				'id'=>2,
				'navigation_id'=>NULL,
				'short'=>'home.admin',
				'name'=>'System',
				'title'=>'System',
				'action_resource'=>'system.index.index',
				'role_short'=>'admin',
				'show_all'=>FALSE,
				'show_all_loggedin'=>FALSE,
				'css_class'=>'system',
				'disabled'=>FALSE,
				'position'=>-100,
			),
			array(
				'id'=>3,
				'navigation_id'=>NULL,
				'short'=>'contact',
				'name'=>'Contact',
				'title'=>'Contact',
				'action_resource'=>'default.contact.index',
				'role_short'=>'guest',
				'show_all'=>TRUE,
				'show_all_loggedin'=>TRUE,
				'css_class'=>'contact',
				'disabled'=>FALSE,
				'position'=>-100,
			),
			array(
				'id'=>4,
				'navigation_id'=>NULL,
				'short'=>'faq',
				'name'=>'FAQ',
				'title'=>'FAQ',
				'action_resource'=>'default.faq.index',
				'role_short'=>'guest',
				'show_all'=>TRUE,
				'show_all_loggedin'=>TRUE,
				'css_class'=>'faq',
				'disabled'=>FALSE,
				'position'=>-100,
			),
			array(
				'id'=>7,
				'navigation_id'=>NULL,
				'short'=>'login.user',
				'name'=>'Login',
				'title'=>'Login',
				'action_resource'=>'default.login.index',
				'role_short'=>'guest',
				'show_all'=>FALSE,
				'show_all_loggedin'=>FALSE,
				'css_class'=>'login',
				'disabled'=>FALSE,
				'position'=>-100,
			),
			array(
				'id'=>8,
				'navigation_id'=>NULL,
				'short'=>'logout.user',
				'name'=>'Logout',
				'title'=>'Logout',
				'action_resource'=>'default.logout.index',
				'role_short'=>'user',
				'show_all'=>FALSE,
				'show_all_loggedin'=>TRUE,
				'css_class'=>'logout',
				'disabled'=>FALSE,
				'position'=>-100,
			),
		));
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{

		/**
		 * collection
		 */
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());

		foreach($this->_data as $data) {

			$record = L8M_Doctrine_Record::factory($this->getModelClassName());

			$record->merge($data);

			$this->_dataCollection->add($record, $data['id']);
		}
	}

}