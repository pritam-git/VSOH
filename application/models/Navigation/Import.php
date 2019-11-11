<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Navigation/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 507 2016-07-21 12:24:40Z nm $
 */

/**
 *
 *
 * Default_Model_Navigation_Import
 *
 *
 */
class Default_Model_Navigation_Import extends L8M_Doctrine_Import_Abstract
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

//		$w[] = array(
//			'id'=>$i++,
//			'navigation_id'=>NULL,
//			'name'=>'Admin',
//			'short'=>'admin',
//			'title'=>'Admin',
//			'action_resource'=>'admin.index.index',
//			'role_short'=>'admin',
//			'show_all'=>FALSE,
//			'show_all_loggedin'=>TRUE,
//			'show_in_module'=>'admin',
//			'css_class'=>'admin',
//			'visible'=>TRUE,
//			'position'=>-100,
//		);

//		$w[] = array(
//			'id'=>$i++,
//			'navigation_id'=>NULL,
//			'name'=>'Logout',
//			'short'=>'logout',
//			'title'=>'Logout',
//			'action_resource'=>'default.logout.index',
//			'role_short'=>'admin',
//			'show_all'=>FALSE,
//			'show_all_loggedin'=>TRUE,
//			'show_in_module'=>'admin',
//			'css_class'=>'admin',
//			'visible'=>TRUE,
//			'position'=>-100,
//		);

		$w[] = array(
			'id'=>$i++,
			'navigation_id'=>NULL,
			'name'=>'Home',
			'short'=>'home',
			'title'=>'Home',
			'action_resource'=>'default.index.index',
			'role_short'=>'guest',
			'show_all'=>TRUE,
			'show_all_loggedin'=>TRUE,
			'show_in_module'=>NULL,
			'css_class'=>'home',
			'visible'=>TRUE,
			'dynamic'=>'home',
			'do_not_translate'=>TRUE,
			'default_language'=>L8M_Locale::getDefault(),
			'position'=>1,
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