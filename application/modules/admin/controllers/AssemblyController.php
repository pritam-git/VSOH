<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/AssemblyController.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: AssemblyController.php 299 2017-01-08 09:59:38Z nm $
 */

/**
 *
 *
 * Admin_AssemblyController
 *
 *
 */
class Admin_AssemblyController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Assembly';
	private $_modelListShort = 'Assembly';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Assembly';

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
	 * Initializes Admin_AssemblyController.
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
//			->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
			->setButton('Send PushMail', array('action'=>'update', 'controller'=>'assembly', 'module'=>'admin'), 'update', TRUE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
				'uname',
				'publish_datetime',
				'title',
				'description',
				'content',
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
				'fr_media_id'=>'/medias/assembly/fr',
				'de_media_id'=>'/medias/assembly/de',
				'fr_presentation_media_id'=>'/medias/assembly/frPresentation',
				'de_presentation_media_id'=>'/medias/assembly/dePresentation',
			),
			'mediaRole'=>array(
				'fr_media_id'=>'user',
				'de_media_id'=>'user',
				'fr_presentation_media_id'=>'user',
				'de_presentation_media_id'=>'user',
			),
			'columnLabels'=>array(
				'assembly_media_image_id'=>'Image',
				'relation_m2n_assemblym2nregion'=>'Region',
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'title'=>'text',
				'description'=>'textarea',
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
				'PRJ_Sitemap_Create_AfterSave',
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
				'PRJ_Sitemap_Edit_AfterSave',
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
	 * Send Push Mail action.
	 *
	 * @return void
	 */
	public function updateAction ()
	{
		$protocolId = $this->_request->getParam('id');
		$isTested = $this->_request->getParam('do');

		/**
		 * prepare back button url
		 */
		$paramArray = array(
			'page',
			'rp',
			'query',
			'qtype',
			'sortorder',
			'sortname',
		);
		$paramValues = '?';
		foreach ($paramArray as $param) {
			$paramValues .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
		}
		$backUrl = $this->view->url(array('action'=>'list','controller'=>'assembly','module'=>'admin','modelListName'=>'Default_Model_Assembly') , NULL, TRUE) . $paramValues;
		$currentUrl = $this->view->url(array('action'=>'update','controller'=>'assembly','module'=>'admin','modelListName'=>'Default_Model_Assembly') , NULL, TRUE) . $paramValues;

		//redirect to index page if result not found.
		if(!PRJ_PushEmail::getSelectionLists('Default_Model_Assembly','AssemblyM2nDepartment','Assembly',$protocolId)){
			$this->_redirect($backUrl);
		}

		$this->view->protocolId = $protocolId;
		$this->view->redirectUrl = $backUrl;
		$this->view->currentUrl = $currentUrl;
		$this->view->isTested = $isTested === 'TRUE' ? TRUE : FALSE;
	}

	/**
	 * Ajax for sending emails in sets
	 *
	 * @return void
	 */
	public function mailProcessAjaxAction(){
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get links for email.
			$protocolId = $requestParams['id'];
			$protocolModel = Default_Model_Assembly::getModelByID($protocolId);

			//get all users and email to them
			$result = PRJ_PushEmail::processPushEmail('AssemblyM2nDepartment', 'Assembly', $requestParams, 'assembly', $protocolModel->short);

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode($result);

			//disable layout
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			/**
			 * header
			 */
			$bodyContentHeader = 'application/json';
			$this->getResponse()
				->setHeader('Content-Type', $bodyContentHeader)
				->setBody($bodyData);
		}
	}

	/**
	 * Ajax for getting user details from selected contract type and region ids.
	 *
	 * @return void
	 */
	public function getSelectedUserAjaxAction(){
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get all users and email to them
			$result = PRJ_PushEmail::getUserFromSelection('AssemblyM2nDepartment','Assembly',$requestParams);

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode($result);

			//disable layout
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			/**
			 * header
			 */
			$bodyContentHeader = 'application/json';
			$this->getResponse()
				->setHeader('Content-Type', $bodyContentHeader)
				->setBody($bodyData);
		}
	}

	/**
	 * Send Test Push Mail action.
	 *
	 * @return void
	 */
	public function sendTestPushmailsAjaxAction ()
	{
		if ($this->getRequest()->isXmlHttpRequest()) {
			$requestParams = $this->_request->getParams();

			//get protocol model.
			$protocolModel = Default_Model_Assembly::getModelByID($requestParams['id']);

			//get all users and email to them
			$result = PRJ_PushEmail::sendTestMail($requestParams,'assembly', $protocolModel->short);

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode($result);

			//disable layout
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			/**
			 * header
			 */
			$bodyContentHeader = 'application/json';
			$this->getResponse()
				->setHeader('Content-Type', $bodyContentHeader)
				->setBody($bodyData);
		}
	}
}