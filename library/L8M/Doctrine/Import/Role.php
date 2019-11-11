<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/Role.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Role.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_Role
 *
 *
 */
class L8M_Doctrine_Import_Role extends L8M_Doctrine_Import_Abstract
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
							  array('id'=>1,
							        'role_id'=>NULL,
							        'disabled'=>FALSE,
							        'short'=>'admin',
									'default_action_resource'=>'system.index.index',
								    'name_en'=>'Administrator',
								    'name_de'=>'Administrator'),
							  array('id'=>2,
							        'role_id'=>1,
						   		    'disabled'=>FALSE,
							        'short'=>'client',
									'default_action_resource'=>'admin.index.index',
								    'name_de'=>'Kunde',
								    'name_en'=>'Client'),
							  array('id'=>3,
							        'role_id'=>1,
							        'disabled'=>FALSE,
								    'short'=>'user',
									'default_action_resource'=>'default.index.index',
								    'name_de'=>'Benutzer',
								    'name_en'=>'User'),
							  array('id'=>4,
							        'role_id'=>3,
							        'disabled'=>FALSE,
							        'short'=>'guest',
									'default_action_resource'=>'default.index.index',
								    'name_en'=>'Guest',
								    'name_de'=>'Gast'),
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
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());
		foreach($this->_data as $data) {
		    $role = L8M_Doctrine_Record::factory($this->getModelClassName());
			$role->merge($data);
			$role->Translation['en']->name = $data['name_en'];
			$role->Translation['de']->name = $data['name_de'];
			$this->_dataCollection->add($role, $data['id']);
		}
	}

}