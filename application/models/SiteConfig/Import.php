<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/SiteConfig/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 501 2016-07-19 12:55:17Z nm $
 */

/**
 *
 *
 * Default_Model_SiteConfig_Import
 *
 *
 */
class Default_Model_SiteConfig_Import extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	protected $_standsForClass = NULL;

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
		/**
		 * pass through to prevent failures
		 */
		parent::_init();

		/**
		 * retrieve class name
		 */
		$this->_retriveStandsForClassName();
		$modelName = $this->_standsForClass;

		/**
		 * retrieve last model
		 */
		$model = Doctrine_Query::create()
			->from($modelName . ' m')
			->limit(1)
			->orderBy('m.id DESC')
			->execute()
			->getFirst()
		;
		if ($model) {
			$i = $model->id + 1;
		} else {
			$i = 1;
		}

		$siteConfigArray = array(
			'Company Name'=>'Test-Company-Name',
			'Footer Text'=>'Inh. Erika Mustermann | Musterstraße 12 | 12345 Musterstadt',
			'Company Address'=>'Musterstraße 12 | 12345 Musterstadt',
			'Google Map Address'=>'Musterstraße 12 | 12345 Musterstadt',
			'Location'=>'Musterstadt',
			'Country'=>'Musterland',
			'Service Hotline'=>'030 12345678',
			'Fax Number'=>'030 12345679',
			'Kontoinhaber'=>'Erika Mustermann',
			'BLZ'=>'10000000',
			'Konto'=>'1234567',
			'Bankname'=>'Testbank',
			'IBAN'=>'DE171000000000123456700',
			'BIC'=>'SCHADE17TEB',
			'Email'=>'info@l8m.com',
			'Tax Number'=>'12/345/67890',
			'Owner'=>'Erika Mustermann',
			'Tax Office'=>'Musterstadt',
			'Jurisdiction'=>'Musterstadt',
			'Commercial Register Number'=>'HRB 123456',
		);

		$w = array();

		foreach ($siteConfigArray as $key => $value) {
			$w[] = array(
				'id'=>$i++,
				'short'=>L8M_Library::getUsableUrlStringOnly($key, '_', array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '_')),
				'name'=>$key,
				'value'=>$value,
			);
		}

		$this->setArray($w);
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		/**
		 * retrieve class name
		 */
		$modelName = $this->_standsForClass;

		/**
		 * check whether translatable or not
		 */
		$model = new $modelName();
		$modelRelations = $model->getTable()->getRelations();
		if (array_key_exists('Translation', $modelRelations)) {
			$transCols = $model->Translation->getTable()->getColumns();
			$transLangs = L8M_Locale::getSupported(TRUE);
			$translateable = TRUE;
		} else {
			$translateable = FALSE;
		}

		/**
		 * add data to collection
		 */
		$this->_dataCollection = new Doctrine_Collection($modelName);
		foreach($this->_data as $data) {
			$model = new $modelName();
			$model->merge($data);

			/**
			 * add translatables
			 */
			if ($translateable) {
				foreach ($transCols as $transCol => $colDefinition) {
					if ($transCol != 'id' &&
						$transCol != 'lang' &&
						$transCol != 'created_at' &&
						$transCol != 'updated_at' &&
						$transCol != 'deleted_at') {

						foreach ($transLangs as $transLang) {
							if (array_key_exists($transCol . '_' . $transLang, $data)) {
								$model->Translation[$transLang]->$transCol = $data[$transCol . '_' . $transLang];
							}
						}
					}
				}
			}

			/**
			 * just add data
			 */
			$this->_dataCollection->add($model, $data['id']);
		}
	}

	/**
	 * Retrieve stands for class name.
	 *
	 * @return void
	 */
	protected function _retriveStandsForClassName()
	{
		$name = get_class($this);
		$this->_standsForClass = substr($name, 0, strlen($name) - strlen('_Import'));
	}
}