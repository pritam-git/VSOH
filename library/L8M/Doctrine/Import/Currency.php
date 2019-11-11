<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/Currency.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Currency.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_Currency
 * 
 * 
 */
class L8M_Doctrine_Import_Currency extends L8M_Doctrine_Import_Abstract 
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
						   cu_iso_3 AS iso_3,
						   cu_iso_nr AS iso_nr,
						   cu_name_en AS name_en,
						   IF(cu_symbol_left!="", cu_symbol_left, NULL) AS symbol_left,
						   IF(cu_symbol_right!="", cu_symbol_right, NULL) AS symbol_right,
						   IF(cu_thousands_point!="", cu_thousands_point, NULL) AS thousands_point,
						   IF(cu_decimal_point!="", cu_decimal_point, NULL) AS decimal_point,
						   IF(cu_decimal_digits!="", cu_decimal_digits, NULL) As decimal_digits,
						   IF(cu_sub_name_en!="", cu_sub_name_en, NULL) AS sub_name_en,
						   IF(cu_sub_divisor!="", cu_sub_divisor, NULL) AS sub_divisor,
						   IF(cu_sub_symbol_left!="", cu_sub_symbol_left, NULL) AS sub_symbol_left,
						   IF(cu_sub_symbol_right!="", cu_sub_symbol_right, NULL) AS sub_symbol_right,
						   IF(cu_name_de!="", cu_name_de, NULL) AS name_de,
						   IF(cu_sub_name_de!="", cu_sub_name_de, NULL) AS sub_name_de
						   
					   FROM
					       static_currencies
					       
					   ORDER BY
					       static_currencies.uid ASC');
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
			$currency = L8M_Doctrine_Record::factory($this->getModelClassName());
			$currency->merge($data);
			$currency->Translation['en']->name = $data['name_en'];
			$currency->Translation['de']->name = $data['name_de'];
			$currency->Translation['en']->sub_name = $data['sub_name_en'];
			$currency->Translation['de']->sub_name = $data['sub_name_de'];
			$this->_dataCollection->add($currency, $data['id']);
		} 
		
	}
	
}