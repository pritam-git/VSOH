<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/ModelListColumn.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ModelListColumn.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_ModelListColumn
 *
 *
 */
class L8M_Doctrine_Import_ModelListColumn extends L8M_Doctrine_Import_Abstract
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
		$this->_data = array();
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{

		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());

		/**
		 * create model list record: entity
		 */
		$modelListRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelList'));
		$modelListRecord->name = 'Default_Model_Entity';
		$modelListRecord->name_short = 'e';
		$modelListRecord->default_sort = 'e_login';
		$modelListRecord->button_edit = TRUE;
		$modelListRecord->button_add = TRUE;
		$modelListRecord->button_delete = TRUE;
		$modelListRecord->width = 860;
		$modelListRecord->height = 385;
		$modelListRecord = L8M_Locale::addModelTranslation($modelListRecord, 'title', 'User');

		/**
		 * create model list column: id
		 */
		$modelColumnNameModel = Doctrine_Query::create()
			->from('Default_Model_ModelColumnName mcn')
			->leftJoin('mcn.ModelName mn')
			->where('mn.name = ? ', array('Default_Model_Entity'))
			->addWhere('mcn.name = ? ', array('id'))
			->execute()
			->getFirst()
		;
		$modelColumnNameValues = array();
		$modelColumnNameValues = L8M_Locale::addModelArrayTranslation($modelColumnNameValues, 'display', 'ID');
		$modelColumnNameModel->merge($modelColumnNameValues);
		$modelColumnNameModel->save();

		$modelListColumnRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelListColumn'));
		$modelListColumnRecord->name = 'id';
		$modelListColumnRecord->search_like = TRUE;
		$modelListColumnRecord->width = 30;
		$modelListColumnRecord->ModelList = $modelListRecord;
		$modelListColumnRecord->ModelColumnName = $modelColumnNameModel;

		/**
		 * add to collection
		 */
		$this->_dataCollection->add($modelListColumnRecord);

		/**
		 * create model list column: login
		 */
		$modelColumnNameModel = Doctrine_Query::create()
			->from('Default_Model_ModelColumnName mcn')
			->leftJoin('mcn.ModelName mn')
			->where('mn.name = ? ', array('Default_Model_Entity'))
			->addWhere('mcn.name = ? ', array('login'))
			->execute()
			->getFirst()
		;
		$modelColumnNameValues = array();
		$modelColumnNameValues = L8M_Locale::addModelArrayTranslation($modelColumnNameValues, 'display', 'Login');
		$modelColumnNameModel->merge($modelColumnNameValues);
		$modelColumnNameModel->save();

		$modelListColumnRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelListColumn'));
		$modelListColumnRecord->name = 'login';
		$modelListColumnRecord->search_like = TRUE;
		$modelListColumnRecord->width = 180;
		$modelListColumnRecord->ModelList = $modelListRecord;
		$modelListColumnRecord->ModelColumnName = $modelColumnNameModel;

		/**
		 * add to collection
		 */
		$this->_dataCollection->add($modelListColumnRecord);

		/**
		 * create model list column: lastname
		 */
		$modelColumnNameModel = Doctrine_Query::create()
			->from('Default_Model_ModelColumnName mcn')
			->leftJoin('mcn.ModelName mn')
			->where('mn.name = ? ', array('Default_Model_Entity'))
			->addWhere('mcn.name = ? ', array('lastname'))
			->execute()
			->getFirst()
		;
		$modelColumnNameValues = array();
		$modelColumnNameValues = L8M_Locale::addModelArrayTranslation($modelColumnNameValues, 'display', 'Lastname');
		$modelColumnNameModel->merge($modelColumnNameValues);
		$modelColumnNameModel->save();

		$modelListColumnRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelListColumn'));
		$modelListColumnRecord->name = 'lastname';
		$modelListColumnRecord->search_like = TRUE;
		$modelListColumnRecord->width = 180;
		$modelListColumnRecord->ModelList = $modelListRecord;
		$modelListColumnRecord->ModelColumnName = $modelColumnNameModel;

		/**
		 * add to collection
		 */
		$this->_dataCollection->add($modelListColumnRecord);

		/**
		 * create model list column: firstname
		 */
		$modelColumnNameModel = Doctrine_Query::create()
			->from('Default_Model_ModelColumnName mcn')
			->leftJoin('mcn.ModelName mn')
			->where('mn.name = ? ', array('Default_Model_Entity'))
			->addWhere('mcn.name = ? ', array('firstname'))
			->execute()
			->getFirst()
		;
		$modelColumnNameValues = array();
		$modelColumnNameValues = L8M_Locale::addModelArrayTranslation($modelColumnNameValues, 'display', 'Firstname');
		$modelColumnNameModel->merge($modelColumnNameValues);
		$modelColumnNameModel->save();

		$modelListColumnRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelListColumn'));
		$modelListColumnRecord->name = 'firstname';
		$modelListColumnRecord->search_like = TRUE;
		$modelListColumnRecord->width = 180;
		$modelListColumnRecord->ModelList = $modelListRecord;
		$modelListColumnRecord->ModelColumnName = $modelColumnNameModel;

		/**
		 * add to collection
		 */
		$this->_dataCollection->add($modelListColumnRecord);
	}

	/**
	 * try loading Default_Model_ModelList_Import for some customizions
	 *
	 * @return void
	 */
	protected function _generateCustomizedDataCollection()
	{
		/**
		 * model name
		 *
		 * @var string
		 */
		$model = 'ModelListColumn';

		/**
		 * import class
		 */
		$importClass = NULL;

		/**
		 * if a model class prefix is present in options, let's try to retrieve an import
		 * class from the corresponding module
		 */
		if (isset($this->_options['options']['builder']['classPrefix'])) {
			$importClass = $this->_options['options']['builder']['classPrefix']
						 . $model
						 . '_Import'
			;
			if (!class_exists($importClass)) {
				try {
					@Zend_Loader::loadClass($importClass);
				} catch (Zend_Exception $exception) {
				}
			}
		}

		if ($importClass !== NULL &&
			class_exists($importClass)) {

			/**
			 * check whether the import class actually extends L8M_Doctrine_Import_Abstract
			 */
			$reflectionClass = new ReflectionClass($importClass);
			if (!$reflectionClass->isSubclassOf('L8M_Doctrine_Import_Abstract')) {
				throw new L8M_Doctrine_Import_Exception($importClass . ' does not extend L8M_Doctrine_Import_Abstract.');
			}

			/**
			 * construct $import of $model
			 */
			$import = new $importClass($this->_options);

			if ($import instanceof L8M_Doctrine_Import_Abstract) {
				$import->process();
				$this->addMessages($import->getMessages());
			}
		}
	}

}