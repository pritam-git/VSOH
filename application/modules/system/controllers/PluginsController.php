<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/PluginsController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PluginsController.php 307 2015-03-31 11:00:06Z nm $
 */

/**
 *
 *
 * System_PluginsController
 *
 *
 */
class System_PluginsController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Plugin';
	private $_modelListShort = 'plugin';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Plugins';

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
	 * Initializes System_PluginsController.
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
		 * start model list
		 */
		$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
		$this->_modelList
			->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
			->disableSubLinks()
			->disableButtonAdd()
			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
			->setButton($this->view->translate('Install Plugin'), array('action'=>'setup', 'controller'=>'plugins', 'module'=>'system'), 'plugin-go', FALSE)
// 			->setListRelationName('Action', 'Action', 180, 'resource', TRUE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
			),
			'addIgnoredColumns'=>array(
				'name',
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
				'author'=>'text',
				'description'=>'textarea'
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

	/**
	 * Setup action.
	 *
	 * @return void
	 */
	public function setupAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Install Plugin');

		$form = new System_Form_Plugin_Upload();

		$this->view->pluginUploadForm = $form;
		$this->view->installedPlugin = FALSE;

		/**
		 * form is submitted and valid
		 */
		$exception = array();
		if ($form->isSubmitted()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				try {
					$plugin = Default_Service_Plugin::fromFormElementFile($form->getElement('FileData'));
					if (!$plugin) {
						$exception = Default_Service_Plugin::getExceptions();
					} else {
						$this->view->installedPlugin = $plugin;
					}
				} catch (Exception $e) {
					$exception = Default_Service_Plugin::getExceptions();
					$exception[] = $e;
					$plugin = FALSE;
				}
			} else {
				$exception[] = $this->view->translate('Plugin is faulty.');
			}
		}

		$form->setDecorators(
			array(
				new Zend_Form_Decorator_FormElements(),
				new Zend_Form_Decorator_HtmlTag(),
				//new L8M_Form_Decorator_Ajaxable(),
				new Zend_Form_Decorator_Form(),
				new L8M_Form_Decorator_FormHasRequiredElements(),
				new L8M_Form_Decorator_HasException($exception),
				new L8M_Form_Decorator(array(
					'boxClass'=>'small l8m-model-form-base',
					'appendJsFile'=>array(
						'/js/jquery/system/model-form-base.js',
						'/js/jquery/system/model-form-change-window-unload.js'
					),
				)),
			)
		);
	}
}