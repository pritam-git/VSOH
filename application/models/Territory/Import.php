<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Territory/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Territory_Import
 *
 *
 */
class Default_Model_Territory_Import extends L8M_Doctrine_Import_Abstract
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

		$array = array(
			array('id'=>1,'iso_nr'=>2,'territory_iso_nr'=>null,'name_en'=>'Africa','name_de'=>'Afrika'),
			array('id'=>2,'iso_nr'=>9,'territory_iso_nr'=>null,'name_en'=>'Oceania','name_de'=>'Ozeanien'),
			array('id'=>3,'iso_nr'=>19,'territory_iso_nr'=>null,'name_en'=>'Americas','name_de'=>'Nord-, Mittel- und Südamerika'),
			array('id'=>4,'iso_nr'=>142,'territory_iso_nr'=>null,'name_en'=>'Asia','name_de'=>'Asien'),
			array('id'=>5,'iso_nr'=>150,'territory_iso_nr'=>null,'name_en'=>'Europe','name_de'=>'Europa'),
			array('id'=>23,'iso_nr'=>17,'territory_iso_nr'=>2,'name_en'=>'Middle Africa','name_de'=>'Zentralafrika'),
			array('id'=>22,'iso_nr'=>15,'territory_iso_nr'=>2,'name_en'=>'Northern Africa','name_de'=>'Nordafrika'),
			array('id'=>21,'iso_nr'=>14,'territory_iso_nr'=>2,'name_en'=>'Eastern Africa','name_de'=>'Ostafrika'),
			array('id'=>20,'iso_nr'=>11,'territory_iso_nr'=>2,'name_en'=>'Western Africa','name_de'=>'Westafrika'),
			array('id'=>24,'iso_nr'=>18,'territory_iso_nr'=>2,'name_en'=>'Southern Africa','name_de'=>'Südafrika'),
			array('id'=>25,'iso_nr'=>53,'territory_iso_nr'=>9,'name_en'=>'Australia and New Zealand','name_de'=>'Australien und Neuseeland'),
			array('id'=>26,'iso_nr'=>54,'territory_iso_nr'=>9,'name_en'=>'Melanesia','name_de'=>'Melanesien'),
			array('id'=>27,'iso_nr'=>57,'territory_iso_nr'=>9,'name_en'=>'Micronesian Region','name_de'=>'Mikronesien'),
			array('id'=>28,'iso_nr'=>61,'territory_iso_nr'=>9,'name_en'=>'Polynesia','name_de'=>'Polynesien'),
			array('id'=>19,'iso_nr'=>29,'territory_iso_nr'=>19,'name_en'=>'Caribbean','name_de'=>'Karibik'),
			array('id'=>18,'iso_nr'=>21,'territory_iso_nr'=>19,'name_en'=>'Northern America','name_de'=>'Nordamerika'),
			array('id'=>17,'iso_nr'=>13,'territory_iso_nr'=>19,'name_en'=>'Central America','name_de'=>'Mittelamerika'),
			array('id'=>16,'iso_nr'=>5,'territory_iso_nr'=>19,'name_en'=>'South America','name_de'=>'Südamerika'),
			array('id'=>6,'iso_nr'=>30,'territory_iso_nr'=>142,'name_en'=>'Eastern Asia','name_de'=>'Ostasien'),
			array('id'=>7,'iso_nr'=>35,'territory_iso_nr'=>142,'name_en'=>'South-eastern Asia','name_de'=>'Südostasien'),
			array('id'=>8,'iso_nr'=>143,'territory_iso_nr'=>142,'name_en'=>'Central Asia','name_de'=>'Zentralasien'),
			array('id'=>9,'iso_nr'=>145,'territory_iso_nr'=>142,'name_en'=>'Western Asia','name_de'=>'Westasien'),
			array('id'=>30,'iso_nr'=>34,'territory_iso_nr'=>142,'name_en'=>'Southern Asia','name_de'=>'Südasien'),
			array('id'=>11,'iso_nr'=>151,'territory_iso_nr'=>150,'name_en'=>'Eastern Europe','name_de'=>'Osteuropa'),
			array('id'=>12,'iso_nr'=>154,'territory_iso_nr'=>150,'name_en'=>'Northern Europe','name_de'=>'Nordeuropa'),
			array('id'=>13,'iso_nr'=>155,'territory_iso_nr'=>150,'name_en'=>'Western Europe','name_de'=>'Westeuropa'),
			array('id'=>10,'iso_nr'=>39,'territory_iso_nr'=>150,'name_en'=>'Southern Europe','name_de'=>'Südeuropa')
		);

		$this->setArray($array);

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

			$territory = L8M_Doctrine_Record::factory($this->getModelClassName());

			$territory->merge($data);
			$territory->Translation['en']->name = $data['name_en'];
			$territory->Translation['de']->name = $data['name_de'];

			$this->_dataCollection->add($territory, $data['id']);
		}

	}

}