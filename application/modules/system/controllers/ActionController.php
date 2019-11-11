<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/ActionController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ActionController.php 359 2015-06-17 15:18:42Z nm $
 */


/**
 *
 *
 * System_ActionController
 *
 *
 */
class System_ActionController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Action';
	private $_modelListShort = 'sact';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Action';

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
	 * Initializes System_ActionController.
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
		 * canonical supported language
		 */
		$canonicalSupportedLanguage = array(''=>'');
		foreach (L8M_Locale::getSupported() as $supportedLanguage) {
			$canonicalSupportedLanguage[$supportedLanguage] = $supportedLanguage;
		}

		/**
		 * start model list
		 */
		$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
		$this->_modelList
			->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
			->disableSubLinks()
//			->disableButtonAdd()
			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
			->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
				'canonical_lang',
				'name',
				'title',
				'headline',
				'subheadline',
				'keywords',
				'description',
				'content',
				'relation_m2n_mediaimagem2naction',
			),
			'addIgnoredColumns'=>array(
				'is_html_view',
				'is_ajax_view',
				'is_json_view',
				'resource',
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
				'relation_m2n_mediaimagem2naction'=>'Images',
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'description'=>'textarea',
			),
			'addStaticFormElements'=>array(
				'canonical_lang'=>array(
					'type'=>'select',
					'values'=>$canonicalSupportedLanguage,
					'label'=>'Canonical for Language',
				),
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
				'PRJ_Action_Edit_AfterSave',
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

		$resourceParam = $this->_request->getParam('resource', NULL, FALSE);
		$doParam = $this->_request->getParam('do', NULL, FALSE);

		$this->view->listOfNewActions = array();
		$this->view->listOfActionMethods = array();

		/**
		 * roleGuest
		 */
		$roleGuest = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.short = ?')
			->execute(array('guest'))
			->getFirst()
		;

		/**
		 * roleAfterGuestUser
		 */
		$roleAfterGuestUser = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.id = ?', array($roleGuest->role_id))
			->execute()
			->getFirst()
		;

		/**
		 * roleAdmin
		 */
		$roleAdmin = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.short = ?')
			->execute(array('admin'))
			->getFirst()
		;

		/**
		 * roleSupervisor
		 */
		$roleSupervisor = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->where('r.short = ?')
			->execute(array('supervisor'))
			->getFirst()
		;

		/**
		 * modules
		 */
		$modules = Zend_Controller_Front::getInstance()->getControllerDirectory();

		/**
		 * filter
		 */
		$filterController = new Zend_Filter_Word_CamelCaseToDash();
		$filterModule = new Zend_Filter_Word_DashToCamelCase();

		foreach ($modules as $moduleShort=>$moduleControllerPath) {

			/**
			 * module
			 */
			$moduleModel = Doctrine_Query::create()
				->from('Default_Model_Module r')
				->addWhere('r.name = ? ', array($moduleShort))
				->execute()
				->getFirst()
			;

			if (!$moduleModel) {
				$moduleModel = new Default_Model_Module();
				$moduleModel->name = strtolower($moduleShort);
				$moduleModel->save();
			}

			$directoryIterator = new DirectoryIterator($moduleControllerPath);
			foreach ($directoryIterator as $file) {
				/* @var $file DirectoryIterator */
				if ($file->isFile() &&
					preg_match('/^(.+)Controller\.php$/', $file->getFilename(), $match)) {

					$controllerFileName = $match[1];
					$controllerDBName = strtolower($filterController->filter($controllerFileName));

					/**
					 * controller
					 */
					$controllerModel = Doctrine_Query::create()
						->from('Default_Model_Controller r')
						->leftJoin('r.Module m')
						->addWhere('r.name = ? ', array($controllerDBName))
						->addWhere('m.name = ? ', array($moduleShort))
						->execute()
						->getFirst()
					;

					if (!$controllerModel) {
						$controllerModel = new Default_Model_Controller();
						$controllerModel->name = $controllerDBName;
						$controllerModel->Module = $moduleModel;
						$controllerModel->save();
					}

					$controllerFile = $controllerFileName . 'Controller';
					$controllerClass = $controllerFileName . 'Controller';

					if ($moduleShort != 'default') {
						$controllerClass = ucfirst($filterModule->filter($moduleShort)) . '_' . $controllerClass;
					}
					if (!class_exists($controllerClass)) {
						try {
							Zend_Loader::loadFile($controllerFile . '.php', $moduleControllerPath);
						} catch (Exception $exception) {
							L8M_Library::arrayShow(array('c'=>$controllerClass, 'p'=>$moduleControllerPath)); die();
						}
					}

					$reflectionClass = new Zend_Reflection_Class($controllerClass);
					if ($reflectionClass->isSubclassOf('Zend_Controller_Action')) {
						$methods = $reflectionClass->getMethods();

						foreach ($methods as $method) {
							/* @var $method Zend_Reflection_Method */
							if ($method->isPublic() &&
								preg_match('/^(.+)Action$/', $method->getName(), $match)) {

								$actionName = strtolower($filterController->filter($match[1]));

								/**
								 * action
								 */
								$actionModel = Doctrine_Query::create()
									->from('Default_Model_Action a')
									->leftJoin('a.Controller c')
									->leftJoin('c.Module m')
									->addWhere('a.name = ? ', array($actionName))
									->addWhere('c.name = ? ', array($controllerDBName))
									->addWhere('m.name = ? ', array($moduleShort))
									->execute()
									->getFirst()
								;

								if (!$actionModel) {
									$actionModel = new Default_Model_Action();
									$actionModel->name = $actionName;
									$actionModel->Controller = $controllerModel;
									$actionModel->is_allowed = TRUE;
									$actionModel->is_action_method = FALSE;
									$actionModel->resource = L8M_Acl_Resource::getResourceName(
										$actionModel->Controller->Module->name,
										$actionModel->Controller->name,
										$actionModel->name
									);
								}

								if (!$actionModel->role_id) {

									/**
									 * default and other user controller actions
									 */
									if ($moduleShort == 'default' &&
										$controllerModel->name == 'user' &&
										$actionName != 'index' &&
										$actionName != 'register' &&
										$actionName != 'registration-complete' &&
										$actionName != 'account-activated' &&
										$actionName != 'retrieve-password' &&
										$actionName != 'reset-password' &&
										$actionName != 'enable-account') {

										$actionModel->Role = $roleAfterGuestUser;
									} else

									/**
									 * default or shop module
									 */
									if ($moduleShort == 'default' ||
										$moduleShort == 'shop') {

										$actionModel->Role = $roleGuest;
									} else

									/**
									 * controller login
									 */
									if ($controllerModel->name == 'login' &&
										$actionName == 'index') {

										$actionModel->Role = $roleGuest;
									} else

									/**
									 * admin module
									 */
									if ($moduleShort == 'admin') {
										$actionModel->Role = $roleSupervisor;
									} else

									/**
									 * system module and controller media
									 */
									if ($moduleShort == 'system' &&
										$controllerModel->name == 'media') {

										$actionModel->Role = $roleSupervisor;
									} else

									/**
									 * system module and controller media
									 */
									if ($moduleShort == 'system' &&
										$controllerModel->name == 'auto-complete') {

										$actionModel->Role = $roleSupervisor;
									} else

									/**
									 * all other modules
									 */
									{
										$actionModel->Role = $roleAdmin;
									}
								}

								if (isset($doParam) &&
									isset($resourceParam)) {

									if (($doParam == 'new-action' && $actionModel->resource == $resourceParam) ||
										$doParam == 'new-action-all' && $resourceParam == 'all') {

										$actionModel->save();
									} else
									if ($doParam == 'real-action' &&
										$actionModel->resource == $resourceParam) {

										$actionModel->is_action_method = FALSE;
										$actionModel->save();
									}
								}

								/**
								 * lead through view
								 */
								if (!$actionModel->id) {
									$this->view->listOfNewActions[] = $actionModel;
								}
								if ($actionModel->is_action_method) {
									$this->view->listOfActionMethods[] = $actionModel;
								}
							}
						}
					}
				}
			}
		}
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