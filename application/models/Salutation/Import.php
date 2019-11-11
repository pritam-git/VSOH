<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Salutation/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Model_Salutation_Import
 *
 *
 */
class Default_Model_Salutation_Import extends L8M_Doctrine_Import_Abstract
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
			array(
				'id'=>1,
				'disabled'=>FALSE,
				'is_male'=>FALSE,
				'name_de'=>'Frau',
				'name_en'=>'Mrs.',
				'name_ch'=>'Frauen',
				'name_fr'=>'Madame',
				'name_ar'=>'donna'
			),
			array(
				'id'=>2,
				'disabled'=>FALSE,
				'is_male'=>TRUE,
				'name_de'=>'Herr',
				'name_en'=>'Mr.',
				'name_ch'=>'Herren',
				'name_fr'=>'Monsieur',
				'name_ar'=>'Signor'
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
		$this->_dataCollection = new Doctrine_Collection('Default_Model_Salutation');
		foreach($this->_data as $data) {
			$salutation = new Default_Model_Salutation();
			$salutation->merge($data);
			$salutation->Translation['de']->name = $data['name_de'];
			$salutation->Translation['en']->name = $data['name_en'];
			$salutation->Translation['ch']->name = $data['name_ch'];
			$salutation->Translation['fr']->name = $data['name_fr'];
			$salutation->Translation['ar']->name = $data['name_ar'];
			$this->_dataCollection->add($salutation, $data['id']);
		}
	}

}