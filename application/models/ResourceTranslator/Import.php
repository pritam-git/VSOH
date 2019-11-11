<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/ResourceTranslator/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 515 2016-09-21 09:33:29Z nm $
 */

/**
 *
 *
 * Default_Model_ResourceTranslator_Import
 *
 *
 */
class Default_Model_ResourceTranslator_Import extends L8M_Doctrine_Import_Abstract
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

		$w = array(
			array(
				'id'=>$i++,
				'resource'=>'default.imprint.index',
				'uresource_en'=>'default.imprint.index',
				'uresource_de'=>'default.impressum.index',
			),
			array(
				'id'=>$i++,
				'resource'=>'default.privacy-policy.index',
				'uresource_en'=>'default.privacy-policy.index',
				'uresource_de'=>'default.datenschutz.index',
			),
			array(
				'id'=>$i++,
				'resource'=>'default.contact.index',
				'uresource_en'=>'default.contact.index',
				'uresource_de'=>'default.kontakt.index',
			),
			array(
				'id'=>$i++,
				'resource'=>'default.news.index',
				'uresource_en'=>'default.news.index',
				'uresource_de'=>'default.aktuelles.index',
			),
			array(
				'id'=>$i++,
				'resource'=>'default.news.detail',
				'uresource_en'=>'default.news.detail',
				'uresource_de'=>'default.aktuelles.detail',
			),
			array(
				'id'=>$i++,
				'resource'=>'default.news.page',
				'uresource_en'=>'default.news.page',
				'uresource_de'=>'default.aktuelles.seite',
			),
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