<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/EntityUser.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: EntityUser.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * class L8M_Doctrine_Import_EntityUser
 * 
 *
 */
class L8M_Doctrine_Import_EntityUser extends L8M_Doctrine_Import_Abstract
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
		$this->setArray(
			array(
				array(
					'login'=>'user',
					'password'=>md5('user'),
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
			$user = L8M_Doctrine_Record::factory($this->getModelClassName());
			$user->merge($data);
			$this->_dataCollection->add($user);
		} 
	}
	
}