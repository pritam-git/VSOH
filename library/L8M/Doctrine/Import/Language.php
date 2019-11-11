<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/Language.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Language.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_Language
 * 
 * 
 */
class L8M_Doctrine_Import_Language extends L8M_Doctrine_Import_Abstract 
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
						   lg_iso_2 AS iso_2,
						   lg_name_en AS name_en,
						   IF(lg_country_iso_2<>"", lg_country_iso_2, NULL) AS country_iso_2,
						   IF(lg_collate_locale<>"", lg_collate_locale, NULL) AS collate_locale,
						   lg_name_local AS name_local,
						   lg_sacred AS is_sacred,
						   lg_constructed AS is_constructed,
						   lg_name_de AS name_de
						   					   
					   FROM
					       static_languages
					       
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
			$language = L8M_Doctrine_Record::factory($this->getModelClassName());
			$language->merge($data);
			$language->Translation['en']->name = $data['name_en'];
			$language->Translation['de']->name = $data['name_de'];
			$this->_dataCollection->add($language, $data['id']);
		} 
		
	}
	
}