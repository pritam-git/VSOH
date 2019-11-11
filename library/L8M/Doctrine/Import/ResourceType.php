<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/ResourceType.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ResourceType.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_ResourceType
 *
 *
 */
class L8M_Doctrine_Import_ResourceType extends L8M_Doctrine_Import_Abstract
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
								    'name'=>'mvc',
							  ),
							  array('id'=>2,
								    'name'=>'other',
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
			$resourceType = L8M_Doctrine_Record::factory($this->getModelClassName());
			$resourceType->merge($data);
			$this->_dataCollection->add($resourceType, $data['id']);
		}

	}

}