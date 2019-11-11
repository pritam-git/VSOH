<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/Territory.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Territory.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_Territory
 * 
 * 
 */
class L8M_Doctrine_Import_Territory extends L8M_Doctrine_Import_Abstract 
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
		$this->setSql('SELECT
						   uid AS id,
						   tr_iso_nr AS iso_nr,
						   IF(tr_parent_iso_nr<>0, tr_parent_iso_nr, NULL) AS territory_iso_nr,
						   tr_name_en AS name_en,
						   tr_name_de AS name_de
						   
					   FROM
					       static_territories
	
					   ORDER BY
					       tr_parent_iso_nr ASC');
	}
	
	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 * 
	 * @return void
	 */
	protected function _generateDataCollection()
	{						
		/**
		 * territoryCollection
		 */
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());
		foreach($this->_data as $data) {
			$territory = L8M_Doctrine_Record::factory($this->getModelClassName());
			$territory->merge($data);
			$territory->Translation['en']->name = $data['name_en'];
			$territory->Translation['de']->name = $data['name_de'];
			$this->_dataCollection->add($territory, $data['id']);
		} 
		
	}
	
}