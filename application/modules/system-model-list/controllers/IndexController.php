<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system-model-list/controllers/IndexController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: IndexController.php 300 2015-03-31 11:01:26Z nm $
 */

/**
 *
 *
 * SystemModelList_IndexController
 *
 *
 */
class SystemModelList_IndexController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = NULL;
	private $_modelListShort = NULL;
	private $_modelListConfig = array();

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
	 * Initializes Admin_IndexController.
	 *
	 * @return void
	 */
	public function init ()
	{
		/**
		 * retrieve model name
		 */
		$this->_modelListName = $this->getRequest()->getParam('modelListName', NULL, FALSE);
		if ($this->_modelListName) {
			$modelNamePrefix = 'Default_Model_';
			if (substr($this->_modelListName, 0, strlen($modelNamePrefix)) != $modelNamePrefix) {
				$this->_modelListName = $modelNamePrefix . $this->_modelListName;
			}
		}

		/**
		 * set headline
		 */
		$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
		if ($this->_modelListName) {
			$modelNames = explode('_', $this->_modelListName);
			$this->_helper->layout()->headline .= ': ' . $modelNames[count($modelNames) - 1];
		}

		/**
		 * create model-list short
		 */
		if ($this->_modelListName) {
			$modelNameModel = Doctrine_Query::create()
				->from('Default_Model_ModelName m')
				->addWhere('m.name = ? ', array($this->_modelListName))
				->limit(1)
				->execute()
				->getFirst()
			;
			if ($modelNameModel) {
				$this->_modelListShort = 'm' . $modelNameModel->id;
			}
		}

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		if ($this->_modelListName) {

			/**
			 * start model list
			 */
			$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
			$this->_modelList
				->disableSubLinks()
//				->disableButtonAdd()
//				->disableButtonDelete()
//				->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//				->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
//				->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
//				->disableSaveWhere()
//				->useDbWhere(FALSE)
//				->showAjax()
//				->doNotRedirect()
//				->setDeleteOldList()
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
}