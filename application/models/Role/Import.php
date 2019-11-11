<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Role/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Role_Import
 *
 *
 */
class Default_Model_Role_Import extends L8M_Doctrine_Import_Abstract
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
				'role_id'=>NULL,
				'disabled'=>FALSE,
				'short'=>'admin',
				'default_action_resource'=>'system.index.index',
				'name_en'=>'Administrator',
				'name_de'=>'Administrator',
			),
			array(
				'id'=>2,
				'role_id'=>1,
				'disabled'=>FALSE,
				'short'=>'supervisor',
				'default_action_resource'=>'admin.index.index',
				'name_en'=>'Supervisor',
				'name_de'=>'Leiter',
			),
			array(
				'id'=>3,
				'role_id'=>2,
				'disabled'=>FALSE,
				'short'=>'author',
				'default_action_resource'=>'author.index.index',
				'name_en'=>'Author',
				'name_de'=>'Autor',
			),
			array(
				'id'=>4,
				'role_id'=>3,
				'disabled'=>FALSE,
				'short'=>'translator',
				'default_action_resource'=>'admin.index.index',
				'name_en'=>'Translator',
				'name_de'=>'Übersetzer',
			),
			array(
				'id'=>5,
				'role_id'=>4,
				'disabled'=>FALSE,
				'short'=>'user',
				'default_action_resource'=>'default.index.index',
				'name_en'=>'User',
				'name_de'=>'Benutzer',
			),
			array(
				'id'=>6,
				'role_id'=>5,
				'disabled'=>FALSE,
				'short'=>'guest',
				'default_action_resource'=>'default.index.index',
				'name_en'=>'Guest',
				'name_de'=>'Gast',
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
		 * roleCollection
		 */
		$this->_dataCollection = new Doctrine_Collection('Default_Model_Role');
		foreach($this->_data as $data) {
			$role = new Default_Model_Role();
			$role->merge($data);
			$role->Translation['en']->name = $data['name_en'];
			$role->Translation['de']->name = $data['name_de'];
			$this->_dataCollection->add($role, $data['id']);
		}
	}

}