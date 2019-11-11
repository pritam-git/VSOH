<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/ShopManageProductOptionsController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ShopManageProductOptionsController.php 350 2015-05-20 07:19:12Z nm $
 */

/**
 *
 *
 * Admin_ShopManageProductOptionsController
 *
 *
 */
class Admin_ShopManageProductOptionsController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_ProductOptionModel';
	private $_modelListShort = 'pom';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Manage ProductOptions';

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
	 * Initializes Admin_ShopManageProductOptionsController.
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
//			->disableButtonAdd()
//			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
			->setButton($this->view->translate('Create Model Name'), array('action'=>'create-model-name', 'controller'=>'shop-manage-product-options', 'module'=>'admin'), 'work', FALSE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
				'name',
				'title',
				'model_name_id',
			),
			'addIgnoredColumns'=>array(
			),
			'addIgnoredM2nRelations'=>array(
				'ProductM2nProductOptionModel',
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
				'title'=>'text',
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
				'ModelName'=>array(
					'name'=>array(
						'like'=>TRUE,
						'value'=>'Default_Model_ProductOptions_%',
					),
				),
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
				'PRJ_ProductOptionModel_Create_BeforeSave',
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
				'PRJ_ProductOptionModel_Edit_BeforeSave'
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
	 * Create Model Name
	 *
	 * @return void
	 */
	public function createModelNameAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Create ModelName');

		$form = new Admin_Form_ModelName_ProductOptions();
		$form
			->setAction($this->_helper->url('create-model-name', 'shop-manage-product-options', 'admin'))
		;
		$form->setDecorators(
			array(
				new Zend_Form_Decorator_FormElements(),
				new Zend_Form_Decorator_HtmlTag(),
				//new L8M_Form_Decorator_Ajaxable(),
				new Zend_Form_Decorator_Form(),
				new L8M_Form_Decorator_ModelListFormBack(),
				new L8M_Form_Decorator_FormHasRequiredElements(),
				new L8M_Form_Decorator(array(
					'boxClass'=>'small l8m-model-form-base',
					'appendJsFile'=>'/js/jquery/system/model-form-base.js',
				)),
			)
		);

		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getParams())) {

			$allowedChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
			$name = ucfirst(L8M_Library::getUsableUrlStringOnly($form->getValue('name'), '', $allowedChars));
			if (mb_strlen($name) > 0) {
				$modelName = 'Default_Model_ProductOptions' . ucfirst($name);
				$productOptionModel = Doctrine_Query::create()
					->from('Default_Model_ModelName m')
					->addWhere('m.name = ? ', array($modelName))
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$productOptionModel) {
					$filter = new Zend_Filter_Word_CamelCaseToUnderscore();
					$tableName = strtolower($filter->filter('ProductOptions' . ucfirst($name)));
					$YmlModelName = 'ProductOptions' . ucfirst($name);

					$this->view->msg = PRJ_ProductOptionModel_CreateModelName_Generate::start($modelName, $YmlModelName, $tableName);
				} else {

					/**
					 * add error to form element
					 *
					 * @var Zend_Form_Element
					 */
					$errorMsg = vsprintf(L8M_Translate::string('The Model Name already exists: "%1s".'),
						array(
							$modelName,
						)
					);
					$nameElement = $form->getElement('name');
					$nameElement->setDisableTranslator(TRUE);
					$nameElement->addErrorMessage($errorMsg);
					$nameElement->markAsError();

					$this->view->form = $form;
				}
			} else {

				/**
				 * add error to form element
				 *
				 * @var Zend_Form_Element
				 */
				$errorMsg = vsprintf(L8M_Translate::string('The only allowed chars are: "%1s".'),
					array(
						implode($allowedChars, '", "'),
					)
				);
				$nameElement = $form->getElement('name');
				$nameElement->setDisableTranslator(TRUE);
				$nameElement->addErrorMessage($errorMsg);
				$nameElement->markAsError();

				$this->view->form = $form;
			}
		} else {
			$this->view->form = $form;
		}
	}
}