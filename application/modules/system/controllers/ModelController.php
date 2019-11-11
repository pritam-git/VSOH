<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/ModelController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ModelController.php 544 2017-08-23 18:28:23Z nm $
 */

/**
 *
 *
 * System_ModelController
 *
 *
 */
class System_ModelController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_ModelList';
	private $_modelListShort = 'modlu';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Model';

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	private $_modelList = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_ModelController.
	 *
	 * @return void
	 */
	public function init ()
	{

		/**
		 * set headline
		 */
		$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
		$this->_helper->layout()->headline .= ': ' . $this->view->translate($this->_modelListUntranslatedTitle);

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		/**
		 * delete-all
		 * this needs to be done before any action if called to prevent errors!
		 */
		if (L8M_Acl_CalledFor::resource() == 'system.model.delete-all') {
			$errors = array();

			$tablesForAutoDelete = array(
				'Default_Model_ModelMarkedForEditor',
				'Default_Model_ModelListWhere',
				'Default_Model_ModelListColumn',
				'Default_Model_ModelListEditIgnore',
				'Default_Model_ModelListConnection',
// 				'ModelColumnName-translation',
				'Default_Model_ModelColumnName',
				'Default_Model_ModelColumnNameEditAs',
				'Default_Model_ModelName',
				'Default_Model_ModelListColumnExport',
				'Default_Model_ModelListExport',
				'Default_Model_EntityModelListConfig',
// 				'ModelList-translation',
				'Default_Model_ModelList',

			);
			foreach ($tablesForAutoDelete as $deleteTable) {
				$whereClause = NULL;
				if ($deleteTable == 'Default_Model_ModelName') {
					$whereClauses = array();

					$backenAdminBoxModelNames = L8M_Sql::factory('BackendAdminBoxesAction')
						->execute('SELECT model_name_id FROM `backend_admin_boxes_action` WHERE model_name_id IS NOT NULL')
					;

					$productOptionModelNames = L8M_Sql::factory('ProductOptionModel')
						->execute('SELECT model_name_id FROM `product_option_model` WHERE model_name_id IS NOT NULL')
					;
					for ($i = 0; $i < $backenAdminBoxModelNames->count(); $i++) {
						$whereClauses[] = ' id != ' . $backenAdminBoxModelNames[$i]->model_name_id;
					}

					for ($i = 0; $i < $productOptionModelNames->count(); $i++) {
						$whereClauses[] = ' id != ' . $productOptionModelNames[$i]->model_name_id;
					}

					if (count($whereClauses) > 0) {
						$whereClause = ' WHERE ' . implode(' AND ', $whereClauses);
					}

				}

				$sqlObj = L8M_Sql::factory($deleteTable);
				if ($whereClause) {
					$tmpModel = new $deleteTable();
					$result = L8M_Db::execute('DELETE FROM `' . $tmpModel->getTable()->getTableName() . '` ' . $whereClause);
					if (L8M_Db::hasException()) {
						$errors[] = L8M_Db::getException();
					}
				} else {
					$result = L8M_Db::truncate($deleteTable);
					if (L8M_Db::hasException()) {
						if ($deleteTable == 'Default_Model_ModelName') {
							$deleteCollection = Default_Model_ModelName::createQuery('Default_Model_ModelName')
								->execute()
							;
							$temp = NULL;
							$exception = NULL;
							foreach ($deleteCollection as $deleteModel) {
								try {
									$deleteModel->hardDelete();
								} catch (Doctrine_Exception $exception) {
									$temp = $exception;
								}
							}
							$temp = L8M_Db::getException();
						} else {
							$errors[] = L8M_Db::getException();
						}
					}
				}
			}

			$this->view->deleteAllErrors = $errors;
		}

		if (L8M_Acl_CalledFor::resource() != 'system.model.delete-all' &&
			L8M_Acl_CalledFor::resource() != 'system.model.update') {

			/**
			 * start model list
			 */
			$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
			$this->_modelList
				->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
				->disableSubLinks()
				->disableButtonAdd()
				->disableButtonDelete()
//				->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//				->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
				->setButton('Update', array('action'=>'update', 'controller'=>'model', 'module'=>'system'), 'update', FALSE)
				->setButtonSeperator()
				->setButton($this->view->translate('Delete All'), array('action'=>'delete-all'), 'delete-all', FALSE)
//				->disableSaveWhere()
//				->useDbWhere(FALSE)
//				->showAjax();
//				->doNotRedirect()
				->setDeleteOldList()
			;

			$this->_modelListConfig = array(
				'order'=>array(
				),
				'addIgnoredColumns'=>array(
				),
				'addIgnoredM2nRelations'=>array(
				),
				'ignoreColumnRelation'=>array(
				),
				'ignoreColumnInMultiRelation'=>array(
				),
				'relationM2nValuesDefinition'=>array(
				),
				'mediaDirectory'=>array(
				),
				'mediaRole'=>array(
				),
				'columnLabels'=>array(
				),
				'buttonLabel'=>'Save',
				'columnTypes'=>array(
				),
				'addStaticFormElements'=>array(
				),
				'M2NRelations'=>array(
				),
				'replaceColumnValuesInMultiRelation'=>array(
				),
				'relationColumnInMultiRelation'=>array(
				),
				'multiRelationCondition'=>array(
				),
				'tinyMCE'=>array(
				),
				'setFormLanguage'=>L8M_Locale::getDefaultSystem(),
				'action'=>$this->_request->getActionName(),
				//'debug'=>TRUE,
			);

			$this->view->modelFormListButtons = $this->_modelList->getButtons(NULL, $this->_modelListShort, $this->_modelListConfig);
		}
	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		if ($this->_modelListName) {
			$this->_forward('list');
		}
	}

	/**
	 * List action.
	 *
	 * @return void
	 */
	public function listAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('List');

		/**
		 * start model list
		 */
		$this->_modelList->listCollection($this->_modelListShort);
	}

	/**
	 * Create action.
	 *
	 * @return void
	 */
	public function createAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Add');

		/**
		 * start model list
		 */
		$this->_modelList->createModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeSave'=>array(
			),
			'addStandardColumnValues'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function deleteAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Delete');

		/**
		 * start model list
		 */
		$this->_modelList->deleteModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforePreDelete'=>array(
			),
			'doBefore'=>array(
			),
		)));
	}

	/**
	 * Delete all action.
	 *
	 * @return void
	 */
	public function deleteAllAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Delete All');

		if (isset($this->view->deleteAllErrors) &&
			count($this->view->deleteAllErrors) == 0) {

			$this->_redirect($this->_helper->url('update', 'model', 'system'));
		}
	}

	/**
	 * Edit action.
	 *
	 * @return void
	 */
	public function editAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Edit');

		/**
		 * start model list
		 */
		$this->_modelList->editModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeFormOutput'=>array(
			),
			'doBeforeSave'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	public function updateAction()
	{
		set_time_limit(0);

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Update');

		/**
		 * vars
		 */
		$viewModelNames = array();

		/**
		 * update
		 */
		$directoryIterator = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'models');
		foreach($directoryIterator as $file) {
			/* @var $file DirectoryIterator */
			if ($file->isFile() &&
				preg_match('/^(.+)\.php$/', $file->getFilename(), $match)) {

				/**
				 * retrieve model name
				 */
				$modelName = 'Default_Model_' . $match[1];

				/**
				 * load model
				 */
				$loadedModel = new $modelName();

				$modelNameModel = Doctrine_Query::create()
					->from('Default_Model_ModelName m')
					->addWhere('m.name = ? ', array($modelName))
					->execute()
					->getFirst()
				;
				if (!$modelNameModel) {
					$modelNameModel = new Default_Model_ModelName();
					$modelNameModel->name = $modelName;
					$modelNameModel->save();
				}

				/**
				 * retrieve columns
				 */
				$modelColumns = $loadedModel->getTable()->getColumns();

				/**
				 * create model column name
				 */
				foreach ($modelColumns as $columnName => $columnDefinition) {
					$modelColumnNameModel = Doctrine_Query::create()
						->from('Default_Model_ModelColumnName m')
						->addWhere('m.name = ? ', array($columnName))
						->addWhere('m.model_name_id = ? ', array($modelNameModel->id))
						->execute()
						->getFirst()
					;
					if (!$modelColumnNameModel) {
						$modelColumnNameModel = new Default_Model_ModelColumnName();
						$modelColumnNameModel->name = $columnName;
						$modelColumnNameModel->model_name_id = $modelNameModel->id;
						$modelColumnNameModel->save();
						$viewModelNames[$modelName][] = $columnName;
					}
				}
			}
		}

		$this->view->listOfModelNames = $viewModelNames;
	}

	/**
	 * PDF action.
	 *
	 * @return void
	 */
	public function exportAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Export');

		/**
		 * this can go on for 5 minutes
		 */
		set_time_limit(300);

		/**
		 * start model list
		 */
		$this->_modelList->exportModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
		)));
	}
}