<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Region/Import.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: Import.php 252 2019-01-29 04:35:58Z nm $
 */

/**
 *
 *
 * Default_Model_Region_Import
 *
 *
 */
class Default_Model_Region_Import extends L8M_Doctrine_Import_Abstract
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

		$setupImagePath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'setup' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
		$image1 = Default_Service_Media::fromFileToMediaID($setupImagePath . 'nutzfahrzeuge.png', 'guest', '/images/blog');


		$w = array();

		$w[] = array(
			'id'=>$i,
			'short'=>'ost',
			'name'=>'East',
			'media_image_id'=>$image1,
			'position'=>$i,
			'title_de'=>'East',
			'title_en'=>'East',
			'description_de'=>'aurepudia nonesciis dolore vel escitatur?<br>mporeicia consendiigolestori occaturia qu',
			'description_en'=>'aurepudia nonesciis dolore vel escitatur?<br>mporeicia consendiigolestori occaturia qu',
		);

		$i++;

		$w[] = array(
			'id'=>$i,
			'short'=>'mitte',
			'name'=>'Middle',
			'media_image_id'=>$image1,
			'position'=>$i,
			'title_de'=>'Middle',
			'title_en'=>'Middle',
			'description_de'=>'aurepudia nonesciis dolore vel escitatur?<br>mporeicia consendiigolestori occaturia qu',
			'description_en'=>'aurepudia nonesciis dolore vel escitatur?<br>mporeicia consendiigolestori occaturia qu',
		);

		$i++;

		$w[] = array(
			'id'=>$i,
			'short'=>'west',
			'name'=>'West',
			'media_image_id'=>$image1,
			'position'=>$i,
			'title_de'=>'West',
			'title_en'=>'West',
			'description_de'=>'aurepudia nonesciis dolore vel escitatur?<br>mporeicia consendiigolestori occaturia qu',
			'description_en'=>'aurepudia nonesciis dolore vel escitatur?<br>mporeicia consendiigolestori occaturia qu',
		);

		$i++;


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