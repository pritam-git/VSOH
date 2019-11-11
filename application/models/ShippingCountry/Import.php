<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/ShippingCountry/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 378 2015-07-08 10:16:41Z nm $
 */

/**
 *
 *
 * Default_Model_ShippingCountry_Import
 *
 *
 */
class Default_Model_ShippingCountry_Import extends L8M_Doctrine_Import_Abstract
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

		$w = array();

		/**
		 * get western europe territory countries
		 */
		$countryCollection = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.territory_iso_nr = ?', array('155'))
			->execute()
		;

		foreach ($countryCollection as $countryModel) {

			$w[] = array(
				'id'=>$i++,
				'country_id'=>$countryModel->id,
				'costs'=>NULL,
				'consistent_costs'=>FALSE,
			);

		}

		/**
		 * get southern europe territory countries
		 */
		$countryCollection = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.territory_iso_nr = ?', array('39'))
			->execute()
		;

		foreach ($countryCollection as $countryModel) {

			$w[] = array(
				'id'=>$i++,
				'country_id'=>$countryModel->id,
				'costs'=>NULL,
				'consistent_costs'=>FALSE,
			);

		}

		/**
		 * get northern europe territory countries
		 */
		$countryCollection = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.territory_iso_nr = ?', array('154'))
			->execute()
		;

		foreach ($countryCollection as $countryModel) {

			$w[] = array(
				'id'=>$i++,
				'country_id'=>$countryModel->id,
				'costs'=>NULL,
				'consistent_costs'=>FALSE,
			);

		}

		/**
		 * get eastern europe territory countries
		 */
		$countryCollection = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.territory_iso_nr = ?', array('151'))
			->execute()
		;

		foreach ($countryCollection as $countryModel) {

			$w[] = array(
				'id'=>$i++,
				'country_id'=>$countryModel->id,
				'costs'=>NULL,
				'consistent_costs'=>FALSE,
			);

		}

		/**
		 * get us country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('US'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get russia country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('RU'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get canada country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('CA'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get china country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('CN'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get turkey country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('TR'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get vae country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('AE'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get vae country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('BR'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get vae country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('AU'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);

		/**
		 * get vae country model
		 */
		$countryModel = Doctrine_Query::create()
			->from('Default_Model_Country c')
			->addWhere('c.iso_2 = ?', array('ZA'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$w[] = array(
			'id'=>$i++,
			'country_id'=>$countryModel->id,
			'costs'=>NULL,
			'consistent_costs'=>FALSE,
		);





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