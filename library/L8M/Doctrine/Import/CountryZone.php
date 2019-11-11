<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/CountryZone.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: CountryZone.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_CountryZone
 * 
 * 
 */
class L8M_Doctrine_Import_CountryZone extends L8M_Doctrine_Import_Abstract 
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
						   zn_country_iso_3 AS country_iso_3,
						   zn_name_local AS name_local,
						   IF(zn_name_en<>"", zn_name_en, NULL) AS name_en,
						   IF(zn_name_de<>"", zn_name_de, NULL) AS name_de
			
			           FROM
			               static_country_zones
			               
			           ORDER BY
			               uid ASC');
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
			$countryZone = L8M_Doctrine_Record::factory($this->getModelClassName());
			$countryZone->merge($data);
			if ($data['name_en']) {
				$countryZone->Translation['en']->name = $data['name_en'];
			}
			if ($data['name_de']) {
				$countryZone->Translation['de']->name = $data['name_de'];
			}			
			$this->_dataCollection->add($countryZone, $data['id']);
		} 
		
	}
	
}