<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/Country.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Country.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_Country
 * 
 * 
 */
class L8M_Doctrine_Import_Country extends L8M_Doctrine_Import_Abstract 
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
						   cn_iso_2 AS iso_2,
						   cn_iso_3 AS iso_3,
						   cn_short_local AS name_local,
						   cn_short_en AS name_en,
						   cn_short_de AS name_de,
						   IF(cn_parent_tr_iso_nr NOT IN (0, 172), cn_parent_tr_iso_nr, NULL) AS territory_iso_nr
						   
					   FROM
					       static_countries
					       
					   ORDER BY
					       static_countries.uid ASC');
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
			$country = L8M_Doctrine_Record::factory($this->getModelClassName());
			$country->merge($data);
			$country->Translation['en']->name = $data['name_en'];
			$country->Translation['de']->name = $data['name_de'];
			$this->_dataCollection->add($country, $data['id']);
		} 
		
	}
	
}