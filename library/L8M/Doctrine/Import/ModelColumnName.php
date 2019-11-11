<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/ModelColumnName.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ModelColumnName.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_ModelColumnName
 *
 *
 */
class L8M_Doctrine_Import_ModelColumnName extends L8M_Doctrine_Import_Abstract
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

		$directoryIterator = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'models');
		foreach($directoryIterator as $file) {
			/* @var $file DirectoryIterator */
			if ($file->isFile() &&
				preg_match('/^(.+)\.php$/', $file->getFilename(), $match)) {

				/**
				 * retrieve model name
				 */
				$modelName = $this->getModelClassName($match[1]);

				/**
				 * create model name record
				 */
				$modelNameRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelName'));
				$modelNameRecord->name = $modelName;

				/**
				 * load model
				 */
				$loadedModel = new $modelName();

				/**
				 * retrieve columns
				 */
				$modelColumns = $loadedModel->getTable()->getColumns();

				/**
				 * create model column name
				 */
				foreach ($modelColumns as $columnName => $columnDefinition) {
					$modelColumnNameRecord = L8M_Doctrine_Record::factory($this->getModelClassName('ModelColumnName'));
					$modelColumnNameRecord->name = $columnName;
					$modelColumnNameRecord->ModelName = $modelNameRecord;

					/**
					 * add to collection
					 */
					$this->_dataCollection->add($modelColumnNameRecord);
				}
			}
		}
	}

	/**
	 * try loading Default_Model_ModelColumnName_Import for some customizions
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
		$model = 'ModelColumnName';

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