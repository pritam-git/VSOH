<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/Salutation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Salutation.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_Salutation
 * 
 *
 */
class L8M_Doctrine_Import_Salutation extends L8M_Doctrine_Import_Abstract 
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
								    'disabled'=>FALSE,
							  		'is_male'=>FALSE,
								    'name_de'=>'Frau',
								    'name_en'=>'Ms./Mrs.'),
							  array('id'=>2,
						   		    'disabled'=>FALSE,
									'is_male'=>TRUE,							  
								    'name_de'=>'Herr',
								    'name_en'=>'Mr'),
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
			$salutation = L8M_Doctrine_Record::factory($this->getModelClassName());
			$salutation->merge($data);
			$salutation->Translation['en']->name = $data['name_en'];
			$salutation->Translation['de']->name = $data['name_de']; 
			$this->_dataCollection->add($salutation, $data['id']);
		} 
	}
	
}