<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/EntityAdmin.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EntityAdmin.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_EntityAdmin
 * 
 *
 */
class L8M_Doctrine_Import_EntityAdmin extends L8M_Doctrine_Import_Abstract 
{
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
	
	/**
	 * Sets import query.
	 * 
	 * @return void
	 */
	protected function _init()
	{
		parent::_init();
		$this->setArray(
			array(
				array(
					'login'=>'admin',
					'password'=>md5('admin'),
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
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());
		foreach($this->_data as $data) {
			$admin = L8M_Doctrine_Record::factory($this->getModelClassName());
			$admin->merge($data);
			$this->_dataCollection->add($admin);
		}
	}
	
}