<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/MediaController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaController.php 541 2017-08-21 22:57:48Z nm $
 */

/**
 *
 *
 * System_MediaController
 *
 *
 */
class System_MediaController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Media';
	private $_modelListShort = 'media';
	private $_modelListConfig = array();

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	private $_modelList = NULL;

	/**
	 * working vars
	 */
	private $_browserType = NULL;
	private $_popUpInputSrc = NULL;
	private $_popUpInputAlt = NULL;
	private $_popUpInputWidth = NULL;
	private $_popUpInputHeight = NULL;
	private $_popUpInputAbsUrl = NULL;
	private $_mediaType = NULL;
	private $_mediaTypeModel = NULL;
	private $_mediaFolderID = NULL;
	private $_mediaFolderModel = NULL;
	private $_mediaRoleShort = NULL;
	private $_mediaRoleModel = NULL;
	private $_mediaHasNoRoleModel = NULL;
	private $_mediaModelString = NULL;
	private $_mediaColumn = NULL;
	private $_mediaJsObjRef = NULL;
	private $_mediaParamValues = NULL;
	private $_mediaAddMediaOnly = NULL;
	private $_modelColumnNameID = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_MediaController.
	 *
	 * @return void
	 */
	public function init ()
	{

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		if (L8M_Acl_CalledFor::action() != 'data') {

			/**
			 * get browser type
			 */
			$this->_browserType = $this->getRequest()->getParam('browserType', NULL, FALSE);
			$this->view->varBrowserType = $this->_browserType;

			/**
			 * popUpInput Source
			 */
			$this->_popUpInputSrc = $this->getRequest()->getParam('popUpInputSrc', NULL, FALSE);
			$this->view->varPopUpInputSrc = $this->_popUpInputSrc;

			/**
			 * popUpInput Description
			 */
			$this->_popUpInputAlt = $this->getRequest()->getParam('popUpInputAlt', NULL, FALSE);
			$this->view->varPopUpInputAlt = $this->_popUpInputAlt;

			/**
			 * popUpInput Width
			 */
			$this->_popUpInputWidth = $this->getRequest()->getParam('popUpInputWidth', NULL, FALSE);
			$this->view->varPopUpInputWidth = $this->_popUpInputWidth;

			/**
			 * popUpInput Height
			 */
			$this->_popUpInputHeight = $this->getRequest()->getParam('popUpInputHeight', NULL, FALSE);
			$this->view->varPopUpInputHeight = $this->_popUpInputHeight;

			/**
			 * popUpInput AbsUrl
			 */
			$this->_popUpInputAbsUrl = $this->getRequest()->getParam('popUpInputAbsUrl', NULL, FALSE);
			$this->view->varPopUpInputAbsUrl = $this->_popUpInputAbsUrl;

			/**
			 * set layout
			 */
			if ($this->_browserType &&
				strlen($this->_browserType) > 5 &&
				substr($this->_browserType, 0, 5) == 'popup') {

				$this->_helper->layout->setLayout('screen-mediabrowser-popup');
			} else {
				$this->_helper->layout->setLayout('screen-mediabrowser');
			}

			/**
			 * set headline
			 */
			$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
			$this->_helper->layout()->headline .= ': ' . $this->view->translate('Media');

			/**
			 * retrieve datas
			 */
			/**
			 * type
			 */
			$this->_mediaType = strtolower($this->getRequest()->getParam('type', NULL, FALSE));
			$typeArray = array(
				'all',
				'image',
				'file',
				'shockwave',
			);
			if (!in_array($this->_mediaType, $typeArray)) {
				$this->_mediaType = $typeArray[0];
			}
			$this->view->varMediaType = $this->_mediaType;

			if ($this->_mediaType == 'all') {
				$this->_mediaTypeModel = 'Default_Model_Media';
			} else {
				$this->_mediaTypeModel = 'Default_Model_Media' . ucfirst($this->_mediaType);
			}

			/**
			 * referenced media folder
			 */
			$this->_mediaFolderID = $this->getRequest()->getParam('mediaFolderID', NULL, FALSE);
			if ($this->_mediaFolderID) {
				$this->_mediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_MediaFolder m')
					->where('m.id = ?', $this->_mediaFolderID)
					->limit(1)
					->execute()
					->getFirst()
				;
				if (!$this->_mediaFolderModel) {
					$this->_mediaFolderID = NULL;
				}
			}
			$this->view->mediaFolderID = $this->_mediaFolderID;

			/**
			 * referenced media role
			 */
			$this->_mediaRoleShort = $this->getRequest()->getParam('mediaRole', NULL, FALSE);
			$this->_mediaHasNoRoleModel = TRUE;
			$mediaRoleID = NULL;
			$mediaRoleModel = NULL;
			$mediaRoleChildIds = array();
			$this->view->mediaRole = $this->_mediaRoleShort;
			$this->view->mediaRoleModel = NULL;
			if ($this->_mediaRoleShort) {
				$roleModel = Doctrine_Query::create()
					->from('Default_Model_Role m')
					->where('m.short = ? ', array($this->_mediaRoleShort))
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$roleModel) {
					$roleModel = NULL;
					$this->_mediaRoleShort = NULL;
					$this->view->mediaRole = NULL;
				} else {
					$this->_mediaHasNoRoleModel = FALSE;
					$this->_mediaRoleModel = $roleModel;
					$this->view->mediaRoleModel = $roleModel;
					$mediaRoleID = $roleModel->id;
					$l8mRole = new L8M_Acl_Role($roleModel->id, $roleModel->short);
					if (method_exists($l8mRole, 'getChildRoleIDs')) {
						$mediaRoleChildIds = $l8mRole->getChildRoleIDs();
					}
				}
			}

			/**
			 * referenced media model
			 */
			$this->_mediaModelString = $this->getRequest()->getParam('mediaModel', NULL, FALSE);
			$this->_mediaColumn = $this->getRequest()->getParam('mediaColumn', NULL, FALSE);

			/**
			 * javascript reference
			 */
			$this->_mediaJsObjRef = $this->getRequest()->getParam('jsObjRef', NULL, FALSE);

			/**
			 * add media only
			 */
			$this->_mediaAddMediaOnly = $this->getRequest()->getParam('add-media-only', NULL, FALSE);

			/**
			 * model column name id
			 */
			$this->_modelColumnNameID = $this->getRequest()->getParam('modelColumnNameID', NULL, FALSE);
			$columnNameModel = Doctrine_Query::create()
				->from('Default_Model_ModelColumnName m')
				->addWhere('m.id = ?', array($this->_modelColumnNameID))
				->limit(1)
				->execute()
				->getFirst()
			;
			if (!$columnNameModel) {
				$this->_modelColumnNameID = NULL;
			}
			$this->view->modelColumnNameID = $this->_modelColumnNameID;
			if (class_exists('Default_Model_RememberMediaFolder', TRUE)) {
				Default_Model_RememberMediaFolder::setInfo($columnNameModel, $this->_mediaFolderModel);
			}


			/**
			 * start model list
			 */
			$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
			$this->_modelList
				->disableSubLinks()
	//			->disableButtonAdd()
	//			->disableButtonDelete()
	//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
	//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
			;

			/**
			 * type
			 */
			if ($this->_mediaType == 'all') {
				$this->_modelList->addWhereDqlString('mediatype.short = ? OR mediatype.short = ? OR mediatype.short = ? ', array('image', 'file', 'shockwave'), 'media', 'MediaType', 'mediatype');
			} else
			if ($this->_mediaType == 'image') {
				$this->_modelList->addWhereDqlString('mediatype.short = ? ', array('image'), 'media', 'MediaType', 'mediatype');
			} else
			if ($this->_mediaType == 'file') {
				$this->_modelList->addWhereDqlString('mediatype.short = ? ', array('file'), 'media', 'MediaType', 'mediatype');
			} else
			if ($this->_mediaType == 'shockwave') {
				$this->_modelList->addWhereDqlString('mediatype.short = ? ', array('shockwave'), 'media', 'MediaType', 'mediatype');
			}

			/**
			 * media folder
			 */
			if ($this->_mediaFolderID) {
				$this->_modelList->addWhereDqlString('media.media_folder_id = ? ', array($this->_mediaFolderID));
			} else {
				$this->_modelList->addWhereDqlString('media.media_folder_id IS NULL ');
			}

			/**
			 * media role
			 */
			if ($mediaRoleID) {
				$allPosibleRollIDs = $mediaRoleChildIds;
				$allPosibleRollIDs[] = $mediaRoleID;
				$allPosibleRollIDs = array_unique($allPosibleRollIDs);
				$mediaRoleDqlArray = array();
				$mediaRoleDqlString = NULL;
				for ($i = 0; $i < count($allPosibleRollIDs); $i++) {
					$mediaRoleDqlArray[] = 'media.role_id = ?';
				}
				$mediaRoleDqlString = implode(' OR ', $mediaRoleDqlArray);
				$this->_modelList->addWhereDqlString($mediaRoleDqlString, $allPosibleRollIDs);
			}

			$this->_modelList
				->showListColumn('width', 'Width', 30, TRUE, FALSE)
				->showListColumn('height','Height', 30, TRUE, FALSE)
				->showListColumn('file_size','File Size', 50, TRUE, FALSE)
				->showListColumn('mime_type','Mime Type', 50, TRUE, FALSE)
				->setListRelationName('MediaType', 'MediaType', 90)
				->setListRelationName('Role', 'Role', 78)
	//			->setListRelationName('MediaFolder', 'MediaFolder', 176)
				->setListRelationName('Entity', 'User', 176)
				->hideListColumn('media_folder_id')
			;

			$this->_modelList
				->setButtonSeperator()
	//			->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
			;

			if ($this->_browserType &&
				strlen($this->_browserType) > 5 &&
				substr($this->_browserType, 0, 5) == 'popup') {

				$this->_modelList
					->disableButtonEdit()
					->setButton($this->view->translate('Select'), array('action'=>'select'), 'select')
				;
			} else {
				$this->_modelList
					->enableButtonEdit()
					->setButton($this->view->translate('Crop'), array('action'=>'crop'), 'crop')
					->setButtonSeperator()
					->setButton($this->view->translate('Replace'), array('action'=>'replace'), 'replace')
				;
				if (Zend_Auth::getInstance()->getIdentity()->Role->short == 'admin') {
					$this->_modelList
						->setButtonSeperator()
						->setButton($this->view->translate('FixMedias'), array('action'=>'fix-medias'), 'images-fix', FALSE)
					;
				}
			}

			$this->_modelList
				->setButtonSeperator()
				->setButton($this->view->translate('Download'), array(), 'download-media', TRUE)
			;

			$this->_modelList
				->leadThroughUrl(array(
					'type'=>$this->_mediaType,
					'jsObjRef'=>$this->_mediaJsObjRef,
					'mediaModel'=>$this->_mediaModelString,
					'mediaColumn'=>$this->_mediaColumn,
					'browserType'=>$this->_browserType,
					'popUpInputSrc'=>$this->_popUpInputSrc,
					'popUpInputAlt'=>$this->_popUpInputAlt,
					'popUpInputWidth'=>$this->_popUpInputWidth,
					'popUpInputHeight'=>$this->_popUpInputHeight,
					'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
					'mediaFolderID'=>$this->_mediaFolderID,
					'mediaRole'=>$this->_mediaRoleShort,
				))
				->leadThroughButton(array(
					'type'=>$this->_mediaType,
					'jsObjRef'=>$this->_mediaJsObjRef,
					'mediaModel'=>$this->_mediaModelString,
					'mediaColumn'=>$this->_mediaColumn,
					'browserType'=>$this->_browserType,
					'popUpInputSrc'=>$this->_popUpInputSrc,
					'popUpInputAlt'=>$this->_popUpInputAlt,
					'popUpInputWidth'=>$this->_popUpInputWidth,
					'popUpInputHeight'=>$this->_popUpInputHeight,
					'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
					'mediaRole'=>$this->_mediaRoleShort,
				))
				->setStartOrder(L8M_Config::getOption('mediabrowser.startup.sortname'), L8M_Config::getOption('mediabrowser.startup.sortorder'))
				->setResultsPerPage(L8M_Config::getOption('mediabrowser.startup.resultPerPage'))
				->disableSaveWhere()
				->useDbWhere(FALSE)
				->setCssClassName('filter')
				->setDefault('width', '569')
//				->showAjax()
//				->doNotRedirect()
				->loadDefaultButtonsFormDefault()
// 				->setDeleteOldList()
			;

			$this->_modelListConfig = array(
				'order'=>array(
					'file_name',
					'name',
					'description',
					'keywords',
					'role_id',
					'entity_id',
				),
				'addIgnoredColumns'=>array(
					'media_folder_id',
					'media_type_id',
					'file_size',
					'mime_type',
					'channels',
					'width',
					'height',
					'media_image_id',
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
					'description'=>'text',
					'keywords'=>'text',
					'file_name'=>'text',
					'name'=>'text',
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
				'setFormLanguage'=>L8M_Locale::getDefaultSystem(),
				'action'=>$this->_request->getActionName(),
				//'debug'=>TRUE,
			);

			$this->view->modelFormListButtons = $this->_modelList->getButtons(NULL, $this->_modelListShort, $this->_modelListConfig);

			/**
			 * some vars for directory action for view
			 */
			$this->view->dirVarArray = array(
				'type'=>$this->_mediaType,
				'jsObjRef'=>$this->_mediaJsObjRef,
				'mediaModel'=>$this->_mediaModelString,
				'mediaColumn'=>$this->_mediaColumn,
				'browserType'=>$this->_browserType,
				'popUpInputSrc'=>$this->_popUpInputSrc,
				'popUpInputAlt'=>$this->_popUpInputAlt,
				'popUpInputWidth'=>$this->_popUpInputWidth,
				'popUpInputHeight'=>$this->_popUpInputHeight,
				'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
				'mediaFolderID'=>$this->_mediaFolderID,
				'mediaRole'=>$this->_mediaRoleShort,
			);

			/**
			 * some general list params for view
			 */
			$paramArray = array(
				'rp',
				'query',
				'qtype',
				'sortname',
				'sortorder',
				'page',
				'mediaFolderID',
			);
			$this->_mediaParamValues = '?';
			foreach ($paramArray as $param) {
				$this->_mediaParamValues .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
			}
			$this->view->dirVarParams = $this->_mediaParamValues;

			/**
			 * form param values for back button
			 */
			$this->view->formParamValues = $this->_mediaParamValues;
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
		 * add media only
		 */
		if ($this->_mediaAddMediaOnly != 'true' &&
			!$this->getRequest()->isXmlHttpRequest()) {

			/**
			 * render multiupload
			 */
			$this->_helper->viewRenderer('create-multiupload');
			$renderAllFormElements = FALSE;
		} else {
			$renderAllFormElements = TRUE;
		}

		/**
		 * no back-button
		 */
		if ($this->_request->getParam('no-back-button') == 'true') {
			$this->view->noBackButton = TRUE;
		} else {
			$this->view->noBackButton = FALSE;
		}

		/**
		 * upload form
		 */
		$backFormParamArray = array(
			'rp',
			'query',
			'qtype',
			'sortname',
			'sortorder',
			'page',
		);
		$backFormParamValues = '?';
		foreach ($backFormParamArray as $backFormParam) {
			$backFormParamValues .= $backFormParam . '=' . $this->getRequest()->getParam($backFormParam, NULL, FALSE) . '&';
		}
		$backFormUrlArray = array(
			'action'=>'list',
			'controller'=>'media',
			'module'=>'system',
			'type'=>$this->_mediaType,
			'jsObjRef'=>$this->_mediaJsObjRef,
			'mediaModel'=>$this->_mediaModelString,
			'mediaColumn'=>$this->_mediaColumn,
			'browserType'=>$this->_browserType,
			'popUpInputSrc'=>$this->_popUpInputSrc,
			'popUpInputAlt'=>$this->_popUpInputAlt,
			'popUpInputWidth'=>$this->_popUpInputWidth,
			'popUpInputHeight'=>$this->_popUpInputHeight,
			'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
			'mediaFolderID'=>$this->_mediaFolderID,
			'mediaRole'=>$this->_mediaRoleShort,
			'add-media-only'=>$this->_mediaAddMediaOnly,
			'modelColumnNameID'=>$this->_modelColumnNameID,
		);
		$this->view->backFormUrl = $this->view->url($backFormUrlArray , NULL, TRUE) . $backFormParamValues;

		$form = new System_Form_Media_Upload();
		$form->buildMeUp($renderAllFormElements, $this->getRequest()->isXmlHttpRequest(), $this->_mediaHasNoRoleModel);

		if ($renderAllFormElements) {
			$form
				->addDecorators(array(
					new L8M_Form_Decorator_ModelListFormBack($this->view->backFormUrl),
					new L8M_Form_Decorator_FormHasRequiredElements(),
					new L8M_Form_Decorator(array(
						'boxClass'=>'small l8m-model-form-base',
						'appendJsFile'=>'/js/jquery/system/model-form-base.js',
					))
				))
			;
		}
		$form
			->setAction(
				$this->view->url(
					array(
						'action'=>'create',
						'type'=>$this->_mediaType,
						'jsObjRef'=>$this->_mediaJsObjRef,
						'mediaModel'=>$this->_mediaModelString,
						'mediaColumn'=>$this->_mediaColumn,
						'browserType'=>$this->_browserType,
						'popUpInputSrc'=>$this->_popUpInputSrc,
						'popUpInputAlt'=>$this->_popUpInputAlt,
						'popUpInputWidth'=>$this->_popUpInputWidth,
						'popUpInputHeight'=>$this->_popUpInputHeight,
						'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
						'mediaFolderID'=>$this->_mediaFolderID,
						'mediaRole'=>$this->_mediaRoleShort,
						'add-media-only'=>$this->_mediaAddMediaOnly,
						'modelColumnNameID'=>$this->_modelColumnNameID,
					)
				) . $this->_mediaParamValues
			)
		;

		/**
		 * form is submitted and valid
		 */
		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost()) &&
			!$this->getRequest()->isXmlHttpRequest()) {

			if ($this->_mediaHasNoRoleModel) {
				$roleValue = $form->getValue('role_short');

				/**
				 * check role
				 */
				$roleModel = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->where('r.short = ? ', array($roleValue))
					->execute()
					->getFirst()
				;
			} else {
				$roleModel = $this->_mediaRoleModel;
			}

			/**
			 * do the media upload
			 */
			$media = Default_Service_Media::fromFormElementFile($form->getElement('FileData'), $this->_mediaFolderModel, $roleModel);

			if ($media instanceof Default_Model_Media) {
				$urlArray = array(
					'action'=>'list',
					'type'=>$this->_mediaType,
					'jsObjRef'=>$this->_mediaJsObjRef,
					'mediaModel'=>$this->_mediaModelString,
					'mediaColumn'=>$this->_mediaColumn,
					'mediaFolderID'=>$this->_mediaFolderID,
					'mediaRole'=>$this->_mediaRoleShort,
					'browserType'=>$this->_browserType,
					'popUpInputSrc'=>$this->_popUpInputSrc,
					'popUpInputAlt'=>$this->_popUpInputAlt,
					'popUpInputWidth'=>$this->_popUpInputWidth,
					'popUpInputHeight'=>$this->_popUpInputHeight,
					'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
					'modelColumnNameID'=>$this->_modelColumnNameID,
				);
				if ($this->_mediaAddMediaOnly == 'true') {
					$urlArray['action'] = 'select';
					$urlArray['add-media-only'] = 'true';
					$this->_mediaParamValues .= '&id=' . $media->id;
				}

				/**
				 * check for allowed media type
				 */
				if ($media instanceof $this->_mediaTypeModel) {
					$this->_redirect(
						$this->view->url(
							$urlArray
						) . $this->_mediaParamValues
					);
				} else {
					$errorMsg = vsprintf(
						$this->view->translate('Media is not type of %1s and was rejected.'),
						array(
							$this->view->translate(ucfirst($this->_mediaType))
						)
					);
					$form->markAsError();
					$form->getElement('FileData')->addErrorMessage($errorMsg);
					$form->getElement('FileData')->markAsError();
					$media->hardDelete();
				}
			}
		} else

		if ($this->getRequest()->isXmlHttpRequest()) {
			$jsonSuccess = FALSE;
			$jsonReason = $this->view->translate('Submit of form doesn\'t work!');

			$fileInfo = NULL;

			if ($this->getRequest()->getParam(L8M_Form::getFormSubmittedIdentifier($form->getId()), NULL, FALSE) == L8M_Form::getFormSubmittedValue($form->getId())) {
				$jsonReason = $this->view->translate('Could not find role!');

				$roleValue = $this->getRequest()->getParam('role_short', NULL, FALSE);
				$fileName = $this->getRequest()->getParam('FileData', NULL, FALSE);

				/**
				 * check role
				 */
				$roleModel = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->where('r.short = ? ', array($roleValue))
					->execute()
					->getFirst()
				;

				if ($roleModel) {
					$jsonReason = $this->view->translate('Upload of data doesn\'t work!');

					if ($fileName) {

						/**
						 * do the media upload
						 */
						$input = fopen('php://input', 'r');
						$tempFileName = Default_Model_Media::getUploadPath() . DIRECTORY_SEPARATOR . $fileName;
						$tempFileHandle = fopen($tempFileName, 'w');
						$realSize = stream_copy_to_stream($input, $tempFileHandle);
						fclose($input);


						if ($realSize <= L8M_Library::getMaxUploadSize()) {
							if (isset($_SERVER['CONTENT_LENGTH'])) {
								if ($realSize == $_SERVER['CONTENT_LENGTH']) {
									if ($_SERVER['CONTENT_LENGTH'] > 0){
										$jsonReason = 'Upload of data doesn\'t work! Could not insert into L8M-Media.';
										$media = Default_Service_Media::fromFile($tempFileName, $this->_mediaFolderModel, $roleModel);
										if ($media instanceof Default_Model_Media) {

											/**
											 * check for allowed media type
											 */
											if ($media instanceof $this->_mediaTypeModel) {
												$jsonSuccess = TRUE;
												$jsonReason = NULL;
											} else {
												$jsonReason = vsprintf(
													$this->view->translate('Media is not type of %1s and was rejected.'),
													array(
														$this->view->translate(ucfirst($this->_mediaType))
													)
												);
												$media->hardDelete();
											}
										}
									} else {
										$jsonReason = $this->view->translate('Upload of data doesn\'t work! File-size must be greater then zero.');
									}
								} else {
									$jsonReason = $this->view->translate('Upload of data doesn\'t work! Stream-size is not equal file-size.');
								}
							} else {
								$jsonReason = $this->view->translate('Upload of data doesn\'t work! Could not match stream-size with file-size.');
							}
						} else {
							$jsonReason = $this->view->translate('All files in sum should have a maximum size of \'%max%\' but \'%size%\' were detected.');
							$jsonReason = str_replace('%max%', L8M_Library::getSizeString(L8M_Library::getMaxUploadSize()),  $jsonReason);
							$jsonReason = str_replace('%size%', L8M_Library::getSizeString($realSize),  $jsonReason);
						}
						unlink($tempFileName);
					}
				}
			}

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode(array('success'=>$jsonSuccess, 'reason'=>$jsonReason, 'fileInfo'=>$fileInfo));

			/**
			 * header
			 */
			$bodyContentHeader = 'application/json';

			Zend_Layout::getMvcInstance()->disableLayout();
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			$this->getResponse()
				->setHeader('Content-Type', $bodyContentHeader)
				->setBody($bodyData)
			;
		}
		$this->view->form = $form;
	}

	/**
	 * Replace action.
	 *
	 * @return void
	 */
	public function replaceAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Replace');

		/**
		 * add media only
		 */
		$renderAllFormElements = TRUE;

		/**
		 * no back-button
		 */
		$this->view->noBackButton = FALSE;

		/**
		 * upload form
		 */
		$backFormParamArray = array(
			'rp',
			'query',
			'qtype',
			'sortname',
			'sortorder',
			'page',
		);
		$backFormParamValues = '?';
		foreach ($backFormParamArray as $backFormParam) {
			$backFormParamValues .= $backFormParam . '=' . $this->getRequest()->getParam($backFormParam, NULL, FALSE) . '&';
		}
		$backFormUrlArray = array(
			'action'=>'list',
			'controller'=>'media',
			'module'=>'system',
			'type'=>$this->_mediaType,
			'jsObjRef'=>$this->_mediaJsObjRef,
			'mediaModel'=>$this->_mediaModelString,
			'mediaColumn'=>$this->_mediaColumn,
			'browserType'=>$this->_browserType,
			'popUpInputSrc'=>$this->_popUpInputSrc,
			'popUpInputAlt'=>$this->_popUpInputAlt,
			'popUpInputWidth'=>$this->_popUpInputWidth,
			'popUpInputHeight'=>$this->_popUpInputHeight,
			'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
			'mediaFolderID'=>$this->_mediaFolderID,
			'mediaRole'=>$this->_mediaRoleShort,
			'add-media-only'=>$this->_mediaAddMediaOnly,
			'modelColumnNameID'=>$this->_modelColumnNameID,
		);
		$this->view->backFormUrl = $this->view->url($backFormUrlArray , NULL, TRUE) . $backFormParamValues;

		/**
		 * check media
		 */
		$mediaID = $this->getRequest()->getParam('id', NULL, FALSE);
		$originalMediaModel = FALSE;
		if (is_numeric($mediaID)) {
			$originalMediaModel = Doctrine_Query::create()
				->from('Default_Model_Media m')
				->addWhere('m.id = ?', $mediaID)
				->limit(1)
				->execute()
				->getFirst()
			;
		}
		if (!$originalMediaModel ||
			!L8M_Acl_Media::checkMedia($originalMediaModel)) {

			$this->_redirect(
				$this->view->url(
					$backFormUrlArray
				) . $this->_mediaParamValues
			);
		} else {
			$roleModel = NULL;
			if ($originalMediaModel->role_id) {
				$roleModel = $originalMediaModel->Role;
			}

			$mediaFolderModel = NULL;
			if ($originalMediaModel->media_folder_id) {
				$mediaFolderModel = $originalMediaModel->MediaFolder;
			}

			$entityModel = NULL;
			if ($originalMediaModel->entity_id) {
				$entityModel = $originalMediaModel->Entity;
			}

			$mediaTypeShort = NULL;
			if ($originalMediaModel->media_type_id) {
				$mediaTypeShort = $originalMediaModel->MediaType->short;
			} else {
				throw new L8M_Exception('Media always need to have a type.');
			}

		}

		/**
		 * form
		 */
		$form = new System_Form_Media_Upload();
		$form->buildMeUp(TRUE, FALSE, FALSE);

		$form
			->addDecorators(array(
				new L8M_Form_Decorator_ModelListFormBack($this->view->backFormUrl),
				new L8M_Form_Decorator_FormHasRequiredElements(),
				new L8M_Form_Decorator(array(
					'boxClass'=>'small l8m-model-form-base',
					'appendJsFile'=>'/js/jquery/system/model-form-base.js',
				))
			))
		;
		$form
			->setAction(
				$this->view->url(
					array(
						'action'=>'replace',
						'type'=>$this->_mediaType,
						'jsObjRef'=>$this->_mediaJsObjRef,
						'mediaModel'=>$this->_mediaModelString,
						'mediaColumn'=>$this->_mediaColumn,
						'browserType'=>$this->_browserType,
						'popUpInputSrc'=>$this->_popUpInputSrc,
						'popUpInputAlt'=>$this->_popUpInputAlt,
						'popUpInputWidth'=>$this->_popUpInputWidth,
						'popUpInputHeight'=>$this->_popUpInputHeight,
						'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
						'mediaFolderID'=>$this->_mediaFolderID,
						'mediaRole'=>$this->_mediaRoleShort,
						'add-media-only'=>$this->_mediaAddMediaOnly,
						'modelColumnNameID'=>$this->_modelColumnNameID,
					)
				) . $this->_mediaParamValues . 'id=' . $mediaID
			)
		;

		/**
		 * form is submitted and valid
		 */
		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost()) &&
			!$this->getRequest()->isXmlHttpRequest()) {

			/**
			 * do the media upload
			 */
			$newMediaModel = Default_Service_Media::fromFormElementFile($form->getElement('FileData'), $mediaFolderModel, $roleModel, $entityModel);

			if ($newMediaModel instanceof Default_Model_Media) {
				$urlArray = array(
					'action'=>'list',
					'type'=>$this->_mediaType,
					'jsObjRef'=>$this->_mediaJsObjRef,
					'mediaModel'=>$this->_mediaModelString,
					'mediaColumn'=>$this->_mediaColumn,
					'mediaFolderID'=>$this->_mediaFolderID,
					'mediaRole'=>$this->_mediaRoleShort,
					'browserType'=>$this->_browserType,
					'popUpInputSrc'=>$this->_popUpInputSrc,
					'popUpInputAlt'=>$this->_popUpInputAlt,
					'popUpInputWidth'=>$this->_popUpInputWidth,
					'popUpInputHeight'=>$this->_popUpInputHeight,
					'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
					'modelColumnNameID'=>$this->_modelColumnNameID,
				);

				/**
				 * check for allowed media type
				 */
				if ($newMediaModel->MediaType->short == $mediaTypeShort) {
					/**
					 * delete children
					 */
					$tmpMediaCollection = Doctrine_Query::create()
						->from('Default_Model_Media m')
						->where('m.media_image_id = ?', $mediaID)
						->execute()
					;
					foreach ($tmpMediaCollection as $tmpMediaModel) {
						$tmpMediaModel->hardDelete();
					}

					/**
					 * copy new into original
					 */
					if (copy($newMediaModel->getStoredFilePath(), $originalMediaModel->getStoredFilePath())) {
						$possiblePublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $originalMediaModel->short;
						$errorDeleteingCache = FALSE;
						if (file_exists($possiblePublicFile)) {
							if (!unlink($possiblePublicFile)) {
								$errorDeleteingCache = TRUE;
							}
						} else {
							$possiblePublicFile = NULL;
						}
						if (!$errorDeleteingCache) {
							$tmpArray = $newMediaModel->toArray(TRUE);
							unset($tmpArray['id']);
							unset($tmpArray['short']);
							unset($tmpArray['filename']);
							unset($tmpArray['role_id']);
							unset($tmpArray['entity_id']);
							unset($tmpArray['media_folder_id']);
							unset($tmpArray['media_type_id']);
							unset($tmpArray['media_image_id']);
							unset($tmpArray['created_at']);
							unset($tmpArray['MediaType']);
							if (array_key_exists('Translation', $tmpArray)) {
								foreach (L8M_Locale::getSupported() as $lang) {
									if (array_key_exists($lang, $tmpArray['Translation'])) {
										unset($tmpArray['Translation'][$lang]['id']);
										unset($tmpArray['Translation'][$lang]['name']);
										unset($tmpArray['Translation'][$lang]['created_at']);
									}
								}
							}
							$originalMediaModel->merge($tmpArray);
							$newMediaModel->hardDelete();
							$originalMediaModel->save();
							if ($possiblePublicFile) {
								copy($originalMediaModel->getStoredFilePath(), $possiblePublicFile);
							}

							$this->_redirect(
								$this->view->url(
									$urlArray
								) . $this->_mediaParamValues
							);
						} else {
							/**
							 * Error: recopy cache-file into old one (deleting it)
							 */
							$errorMsg = vsprintf(
								$this->view->translate('Media can not be replaced. "%1s" is not writable.'),
								array(
									$possiblePublicFile
								)
							);
							$form->markAsError();
							$form->getElement('FileData')->addErrorMessage($errorMsg);
							$form->getElement('FileData')->markAsError();
							$newMediaModel->hardDelete();
						}
					} else {
						/**
						 * Error: recopy file into old one
						 */
						$errorMsg = vsprintf(
							$this->view->translate('Media can not be replaced. "%1s" is not writable.'),
							array(
								$originalMediaModel->getStoredFilePath()
							)
						);
						$form->markAsError();
						$form->getElement('FileData')->addErrorMessage($errorMsg);
						$form->getElement('FileData')->markAsError();
						$newMediaModel->hardDelete();
					}
				} else {
					$errorMsg = vsprintf(
						$this->view->translate('Media is not type of %1s and was rejected.'),
						array(
							$this->view->translate(ucfirst($this->_mediaType))
						)
					);
					$form->markAsError();
					$form->getElement('FileData')->addErrorMessage($errorMsg);
					$form->getElement('FileData')->markAsError();
					$newMediaModel->hardDelete();
				}
			}
		}
		$this->view->form = $form;
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
				'L8M_Media_Edit_BeforeSave',
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
				'L8M_Media_Edit_AfterSave',
			),
		)));
	}

	/**
	 * Select File for Media
	 *
	 * @return void
	 */
	public function selectAction()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Select');

		/**
		 * retrieve datas
		 */
		/**
		 * contains media id
		 */
		$mediaID = $this->getRequest()->getParam('id', NULL, FALSE);
		if (!is_numeric($mediaID)) {
			$mediaID = NULL;
		}

		$paramArray = array(
			'rp',
			'query',
			'qtype',
			'sortname',
			'sortorder',
			'page',
		);
		$paramValues = '?id=' . $mediaID . '&';
		foreach ($paramArray as $param) {
			$paramValues .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
		}

		if ($mediaID) {

			/**
			 * retrive media
			 */
			/* @var $actionMedia Default_Model_MediaImage */
			$mediaModel = Doctrine_Query::create()
				->from('Default_Model_Media m')
				->where('m.id = ?', $mediaID)
				->fetchOne()
			;

			$imageOptions = L8M_Config::getOption('mediabrowser.images.aspectRatio');

			if ($mediaModel instanceof Default_Model_MediaImage &&
				$imageOptions &&
				is_array($imageOptions) &&
				array_key_exists($this->_mediaModelString, $imageOptions) &&
				is_array($imageOptions[$this->_mediaModelString]) &&
				array_key_exists($this->_mediaColumn, $imageOptions[$this->_mediaModelString]) &&
				(float) round($imageOptions[$this->_mediaModelString][$this->_mediaColumn], 1) != (float) round(($mediaModel->width / $mediaModel->height), 1)) {

				$this->_redirect($this->view->url(
					array(
						'action'=>'crop',
						'type'=>$this->_mediaType,
						'jsObjRef'=>$this->_mediaJsObjRef,
						'mediaModel'=>$this->_mediaModelString,
						'mediaColumn'=>$this->_mediaColumn,
						'browserType'=>$this->_browserType,
						'popUpInputSrc'=>$this->_popUpInputSrc,
						'popUpInputAlt'=>$this->_popUpInputAlt,
						'popUpInputWidth'=>$this->_popUpInputWidth,
						'popUpInputHeight'=>$this->_popUpInputHeight,
						'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
						'mediaFolderID'=>$this->_mediaFolderID,
						'mediaRole'=>$this->_mediaRoleShort,
						'add-media-only'=>$this->_mediaAddMediaOnly,
						'modelColumnNameID'=>$this->_modelColumnNameID,
					)) .
					$paramValues .
					'pleaseCrop=' . round($imageOptions[$this->_mediaModelString][$this->_mediaColumn], 1)
				);
			} else
			if ($mediaModel) {
				$this->view->mediaModel = $mediaModel;
			}
		}

		$this->view->jsObjRef = $this->_mediaJsObjRef;
	}


	/**
	 * Crop File for Media
	 *
	 * @return void
	 */
	public function cropAction()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Crop');

		/**
		 * add javascript and css for jcrop
		 */
		$this->view->jQuery()->addJavascriptFile('/js/jquery/plugins/jcrop/js/jquery.Jcrop.min.js');
		$this->view->headLink()->appendStylesheet('/js/jquery/plugins/jcrop/css/jquery.Jcrop.css', 'screen');

		/**
		 * retrieve datas
		 */
		/**
		 * do we respect an aspect ratio?
		 */
		$aspectRatio = $this->getRequest()->getParam('pleaseCrop', NULL, FALSE);

		/**
		 * contains media id
		 */
		$mediaID = $this->getRequest()->getParam('id', NULL, FALSE);
		if (!is_numeric($mediaID)) {
			$mediaID = NULL;
		}

		$paramArray = array(
			'rp',
			'query',
			'qtype',
			'sortname',
			'sortorder',
			'page',
			'mediaFolderID',
		);
		$paramValues = '?id=' . $mediaID . '&';
		$paramValuesWithoutID = '?';
		foreach ($paramArray as $param) {
			$paramValues .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
			$paramValuesWithoutID .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
		}

		/**
		 * form param values for back button
		 */
		$this->view->formParamValues = $paramValuesWithoutID;

		/**
		 * do we need to check or is it a fake request?
		 */
		$maxBoxImageModel = FALSE;
		$imageModel = FALSE;
		if ($mediaID !== NULL) {

			/**
			 * retrive media
			 */
			/* @var $actionMedia Default_Model_MediaImage */
			$imageModel = Doctrine_Query::create()
				->from('Default_Model_MediaImage m')
				->where('m.id = ?', $mediaID)
				->fetchOne()
			;

			/**
			 * do we have a media image to work with?
			 */
			if ($imageModel) {
				$maxBoxImageModel = $imageModel->maxBox(640, 480, TRUE);
			}
		}

		$form = new System_Form_Media_Crop();
		$form
			->addDecorator(new L8M_Form_Decorator_ModelListFormBack())
			->setAction(
				$this->view->url(
					array(
						'action'=>'crop',
						'type'=>$this->_mediaType,
						'jsObjRef'=>$this->_mediaJsObjRef,
						'mediaModel'=>$this->_mediaModelString,
						'mediaColumn'=>$this->_mediaColumn,
						'browserType'=>$this->_browserType,
						'popUpInputSrc'=>$this->_popUpInputSrc,
						'popUpInputAlt'=>$this->_popUpInputAlt,
						'popUpInputWidth'=>$this->_popUpInputWidth,
						'popUpInputHeight'=>$this->_popUpInputHeight,
						'popUpInputAbsUrl'=>$this->_popUpInputAbsUrl,
						'mediaFolderID'=>$this->_mediaFolderID,
						'mediaRole'=>$this->_mediaRoleShort,
						'add-media-only'=>$this->_mediaAddMediaOnly,
						'modelColumnNameID'=>$this->_modelColumnNameID,
					)
				) . $paramValues . 'pleaseCrop=' . $aspectRatio)
		;

		/**
		 * form is submitted and valid
		 */
		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost())) {

			/**
			 * contains media x start
			 */
			$mediaX = $form->getValue('mediax');

			/**
			 * contains media y start
			 */
			$mediaY = $form->getValue('mediay');

			/**
			 * contains media width
			 */
			$mediaWidth = $form->getValue('mediaw');

			/**
			 * contains media height
			 */
			$mediaHeight = $form->getValue('mediah');

			/**
			 * do we have to crop the image?
			 */
			if ($mediaX != NULL &&
				$mediaY != NULL &&
				$mediaWidth != NULL &&
				$mediaWidth != 0 &&
				$mediaHeight != NULL &&
				$mediaHeight != 0 &&
				$imageModel !== FALSE &&
				$maxBoxImageModel !== FALSE) {

				/**
				 * calculate the rigth dimensions
				 */
				$newMediaX = intval($mediaX);
				if ($newMediaX < 0) {
					$newMediaX = 0;
				}

				$newMediaY = intval($mediaY);
				if ($newMediaY < 0) {
					$newMediaY = 0;
				}

				$newMediaWidth = intval($mediaWidth);
				if ($newMediaWidth > $imageModel->width) {
					$newMediaWidth = intval($imageModel->width);
				}

				$newMediaHeight = intval($mediaHeight);
				if ($newMediaHeight > $imageModel->height) {
					$newMediaHeight = intval($imageModel->height);
				}

				/**
				 * crop
				 */
				$cropedImageModel = $imageModel->crop($newMediaX, $newMediaY, $newMediaWidth, $newMediaHeight);

				/**
				 * redirect
				 */
				if ($aspectRatio) {
					if ($cropedImageModel) {
						$paramValues = '?id=' . $cropedImageModel->id . '&';
						foreach ($paramArray as $param) {
							$paramValues .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
						}
					}
					$this->_redirect($this->view->url(array('action'=>'select', 'add-media-only'=>$this->_mediaAddMediaOnly)) . $paramValues);
				} else {
					$this->_redirect($this->view->url(array('action'=>'list')) . $paramValues);
				}
			}
		}

		/**
		 * push File-Infos through view
		 */
		$this->view->mediaID = $mediaID;
		$this->view->imageModel = $imageModel;
		$this->view->maxBoxImageModel = $maxBoxImageModel;
		$this->view->form = $form;
		$this->view->aspectRatio = $aspectRatio;
	}

	public function directoryAction()
	{

		if ($this->getRequest()->isXmlHttpRequest()) {
			$mediaFolderID = $this->_request->getParam('mediaFolderID');
			$targetID = $this->_request->getParam('targetID');
			$name = $this->_request->getParam('name');
			$function = $this->_request->getParam('func');

			$data = array();

			if ($function == 'new') {
				$name = 'new' . date('YmdHis');
				$targetMediaFolderID = NULL;
				if ($targetID) {
					$targetMediaFolderModel = Doctrine_Query::create()
						->from('Default_Model_MediaFolder m')
						->addWhere('m.id = ? ', array($targetID))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($targetMediaFolderModel) {
						$targetMediaFolderID = $targetMediaFolderModel->id;
					}
				}
				$mediaFolderModel = new Default_Model_MediaFolder();
				$mediaFolderModel->merge(array(
					'media_folder_id'=>$targetMediaFolderID,
					'name'=>$name,
				));
				$mediaFolderModel->save();
				$data = array(
					'mediaFolderID'=>$mediaFolderModel->id,
					'mediaFolderName'=>$name,
					'mediaFolderTargetID'=>$targetMediaFolderID,
				);
			} else
			if ($function == 'rename') {
				$useName = trim(L8M_Library::getUsableUrlStringOnly($name, '', array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'é', 'É', 'è', 'È', 'ê', 'Ê', 'í', 'Í', 'ì', 'Ì', 'î', 'Î', 'ñ', 'Ñ', 'ó', 'Ó', 'ò', 'Ò', 'ô', 'Ô', 'ß', 'ú', 'Ù', 'ù', 'Ù', 'û', 'Û', '-', '_', ' '), array(), FALSE));
				if ($useName &&
					$name == $useName) {

					$mediaFolderModel = Doctrine_Query::create()
						->from('Default_Model_MediaFolder m')
						->addWhere('m.id = ? ', array($mediaFolderID))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($mediaFolderModel) {
						$mediaFolderModel->name = $useName;
						$mediaFolderModel->save();
						$data = array(
							'action'=>'ok',
							'mediaFolderID'=>$mediaFolderModel->id,
							'mediaFolderName'=>$useName,
						);
					}
				} else {
					$mediaFolderModel = Doctrine_Query::create()
						->from('Default_Model_MediaFolder m')
						->addWhere('m.id = ? ', array($mediaFolderID))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($mediaFolderModel) {
						$oldName = $mediaFolderModel->name;
					} else {
						$oldName;
					}
					$data = array(
						'action'=>'error',
						'message'=>$this->view->translate('Sorry, only letters (a-z, A-Z and ä, Ä, ö, Ö, ü, Ü, é, É, è, È, ê, Ê, í, Í, ì, Ì, î, Î, ñ, Ñ, ó, Ó, ò, Ò, ô, Ô, ß, ú, Ù, ù, Ù, û, Û), numbers (0-9), signs (-, _, \') and whitespaces in combination with words are allowed.'),
						'mediaFolderID'=>$mediaFolderID,
						'mediaFolderName'=>$oldName,
					);
				}
			} else
			if ($function == 'drop') {
				$targetMediaFolderID = NULL;
				if ($targetID) {
					$targetMediaFolderModel = Doctrine_Query::create()
						->from('Default_Model_MediaFolder m')
						->addWhere('m.id = ? ', array($targetID))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($targetMediaFolderModel) {
						$targetMediaFolderID = $targetMediaFolderModel->id;
					}
				}
				$mediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_MediaFolder m')
					->addWhere('m.id = ? ', array($mediaFolderID))
					->limit(1)
					->execute()
					->getFirst()
				;
				if ($mediaFolderModel) {
					$mediaFolderModel->media_folder_id = $targetMediaFolderID;
					$mediaFolderModel->save();
					$data = array(
						'mediaFolderID'=>$mediaFolderModel->id,
						'mediaFolderName'=>$mediaFolderModel->name,
					);
				}
			} else
			if ($function == 'delete') {
				$subMediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_MediaFolder m')
					->where('m.media_folder_id = ?', $mediaFolderID)
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$subMediaFolderModel) {
					$mediaFolderModel = Doctrine_Query::create()
						->from('Default_Model_MediaFolder m')
						->where('m.id = ?', $this->_mediaFolderID)
						->limit(1)
						->execute()
						->getFirst()
					;
					$parentFolderID = $mediaFolderModel->media_folder_id;

					$mediaCollection = Doctrine_Query::create()
						->from('Default_Model_Media m')
						->where('m.media_folder_id = ?', $this->_mediaFolderID)
						->execute()
					;

					foreach ($mediaCollection as $mediaModel) {
						$mediaModel->media_folder_id = $parentFolderID;
						$mediaModel->save();
					}

					$mediaFolderModel->hardDelete();
					$data = array(
						'mediaFolderID'=>$this->_mediaFolderID,
						'action'=>'ok',
					);
				} else {
					$data = array(
						'action'=>'error',
						'message'=>$this->view->translate('Subdirectories have to be deleted first.'),
					);
				}
			}

			if (count($data) == 0) {
				$data['error'] = array(
					'mediaFolderID'=>$mediaFolderID,
					'targetID'=>$targetID,
					'name'=>$name,
					'func'=>$function,
				);
			}

			/**
			 * json
			 */
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			$this->getResponse()
				->setHeader('Content-Type', 'application/json')
				->setBody(Zend_Json_Encoder::encode($data))
			;
		}
	}

	public function dataAction ()
	{

		if ($this->getRequest()->isXmlHttpRequest()) {

			/**
			 * returnValue
			 */
			$data = array();

			/**
			 * params
			 */
			$ID = $this->_request->getParam('id');
			$pId = $ID;
			$onLoadMediaFolder = $this->_request->getParam('onLoadMediaFolder');

			if (!$ID &&
				$onLoadMediaFolder) {

				$pId = $onLoadMediaFolder;
			}

			/**
			 * create data
			 */
			$mediaFolderQuery = Doctrine_Query::create()
				->from('Default_Model_MediaFolder m')
			;

			if ($pId) {
				$mediaFolderQuery = $mediaFolderQuery
					->addWhere('m.media_folder_id = ? ', array($pId))
				;
			} else {
				$mediaFolderQuery = $mediaFolderQuery
					->addWhere('m.media_folder_id IS NULL ', array())
				;
			}

			$mediaFolderCollection = $mediaFolderQuery
				->orderBy('m.name ASC')
				->execute()
			;

			foreach ($mediaFolderCollection as $mediaFolderModel) {
				$nId = $mediaFolderModel->id;

				$isParent = 'false';

				$mediaFolderCount = Doctrine_Query::create()
					->from('Default_Model_MediaFolder m')
					->select('COUNT(m.id)')
					->addWhere('m.media_folder_id = ? ', array($nId))
					->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)
					->execute()
				;

				if ($mediaFolderCount) {
					$isParent = 'true';
				}

				$data[] = array(
					'id'=>$nId,
					'name'=>$mediaFolderModel->name,
					'isParent'=>$isParent,
				);
			}

			if (!$ID &&
				$onLoadMediaFolder) {

				$mediaFolderModel = Doctrine_Query::create()
					->from('Default_Model_MediaFolder m')
					->addWhere('m.id = ? ', array($onLoadMediaFolder))
					->limit(1)
					->execute()
					->getFirst()
				;

				while ($mediaFolderModel) {
					$tmpData = array();
					$mediaFolderQuery = Doctrine_Query::create()
						->from('Default_Model_MediaFolder m')
					;

					if ($mediaFolderModel->media_folder_id) {
						$mediaFolderQuery = $mediaFolderQuery
							->addWhere('m.media_folder_id = ? ', array($mediaFolderModel->media_folder_id))
						;
					} else {
						$mediaFolderQuery = $mediaFolderQuery
							->addWhere('m.media_folder_id IS NULL ', array())
						;
					}

					$mediaFolderCollection = $mediaFolderQuery
						->orderBy('m.name ASC')
						->execute()
					;
					if ($mediaFolderCollection->count() > 0) {
						foreach ($mediaFolderCollection as $tmpMediaFolderModel) {
							$nId = $tmpMediaFolderModel->id;

							$isParent = 'false';

							$mediaFolderCount = Doctrine_Query::create()
								->from('Default_Model_MediaFolder m')
								->select('COUNT(m.id)')
								->addWhere('m.media_folder_id = ? ', array($nId))
								->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)
								->execute()
							;

							if ($mediaFolderCount) {
								$isParent = 'true';
							}

							$myData = array(
								'id'=>$nId,
								'name'=>$tmpMediaFolderModel->name,
								'isParent'=>$isParent,
							);

							if ($nId == $mediaFolderModel->id) {
								$myData['children'] = $data;
							}

							$tmpData[] = $myData;
						}

						$data = $tmpData;

						$mediaFolderModel = Doctrine_Query::create()
							->from('Default_Model_MediaFolder m')
							->addWhere('m.id = ? ', array($mediaFolderModel->media_folder_id))
							->limit(1)
							->execute()
							->getFirst()
						;
//					} else {
//						$data = array(
//							'id'=>'',
//							'name'=>'root',
//							'isParent'=>'true',
//							'open'=>'true',
//							'children'=>$data,
//						);
//						$mediaFolderModel = FALSE;
//					}
//
//					if (!$mediaFolderModel) {
//						$data = array(
//							'id'=>'',
//							'name'=>'root',
//							'isParent'=>'true',
//							'open'=>'true',
//							'children'=>$data,
//						);
					}
				}
			}

			if (!$pId ||
				!$ID && $onLoadMediaFolder) {

				$data = array(
					'id'=>'',
					'name'=>'root',
					'isParent'=>'true',
					'open'=>'true',
					'children'=>$data,
				);
			}

			/**
			 * json
			 */
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			$this->getResponse()
				->setHeader('Content-Type', 'application/json')
				->setBody(Zend_Json_Encoder::encode($data))
			;
		}
	}

	public function fixMediasAction ()
	{
		if (Zend_Auth::getInstance()->getIdentity()->Role->short != 'admin') {
			$this->_redirect($this->_helper->url('index', 'media', 'system'));
		}

		$recoverID = $this->_request->getParam('recoverID');
		$deleteID = $this->_request->getParam('deleteID');
		$createCacheID = $this->_request->getParam('createCacheID');
		$deleteDataID = $this->_request->getParam('deleteDataID');
		$deleteFileID = $this->_request->getParam('deleteFileID');
		$deleteAllLostFiles = $this->_request->getParam('deleteAllLostFiles');

		if ($deleteAllLostFiles) {
			$deleteAllLostFiles = TRUE;
		} else {
			$deleteAllLostFiles = FALSE;
		}

		$mediaType = array(
			1=>'f',
			2=>'i',
			3=>'ii',
			4=>'s',
		);

		$errors = array();

		if ($recoverID) {
			try {
				$mediaSqlCollection = L8M_Sql::factory('Default_Model_Media')
					->addWhere('id = ?', array($recoverID))
					->limit(1)
					->execute()
				;
				if ($mediaSqlCollection->count() > 0) {
					$mediaSqlModel = $mediaSqlCollection->first();

					if (file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $mediaSqlModel->short)) {
						$destFile = BASE_PATH . DIRECTORY_SEPARATOR . Default_Model_Media::MEDIA_PATH . DIRECTORY_SEPARATOR . $mediaType[$mediaSqlModel->media_type_id] . $mediaSqlModel->id;
						$sourceFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $mediaSqlModel->short;
						if (!copy($sourceFile, $destFile)) {
							$errors[] = 'Error while copying the file: "' . $mediaSqlModel->short . '".';
						}
					}
				}
			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
		}

		if ($deleteID) {
			try {
				$mediaSqlCollection = L8M_Sql::factory('Default_Model_Media')
					->addWhere('id = ?', array($deleteID))
					->limit(1)
					->execute()
				;
				if ($mediaSqlCollection->count() > 0) {
					$mediaSqlModel = $mediaSqlCollection->first();

					$subMediaSqlCollection = L8M_Sql::factory('Default_Model_Media')
						->addWhere('media_image_id = ?', array($deleteID))
						->execute()
					;
					if ($subMediaSqlCollection->count() > 0) {
						for ($i = 0; $i < $subMediaSqlCollection->count(); $i++) {
							$mediaModel = Doctrine_Query::create()
								->from('Default_Model_Media m')
								->addWhere('m.id = ?', array($subMediaSqlCollection[$i]->id))
								->execute()
								->getFirst()
							;
							if ($mediaModel) {
								$mediaModel->hardDelete();
							}
						}
					}
					$mediaModel = Doctrine_Query::create()
						->from('Default_Model_Media m')
						->addWhere('m.id = ?', array($mediaSqlModel->id))
						->execute()
						->getFirst()
					;
					if ($mediaModel) {
						$mediaModel->hardDelete();
					}
				}
			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
		}

		if ($createCacheID) {
			try {
				$mediaSqlCollection = L8M_Sql::factory('Default_Model_Media')
					->addWhere('id = ?', array($createCacheID))
					->limit(1)
					->execute()
				;
				if ($mediaSqlCollection->count() > 0) {
					$mediaSqlModel = $mediaSqlCollection->first();

					if ($mediaSqlModel->role_id == L8M_Acl_Role::getRoleIdByShort('guest')) {
						$sourceFile = BASE_PATH . DIRECTORY_SEPARATOR . Default_Model_Media::MEDIA_PATH . DIRECTORY_SEPARATOR . $mediaType[$mediaSqlModel->media_type_id] . $mediaSqlModel->id;
						$destFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $mediaSqlModel->short;
						if (!copy($sourceFile, $destFile)) {
							$errors[] = 'Error while copying the file: "' . $mediaSqlModel->short . '".';
						}
					} else {
						$errors[] = 'Media is not of type "guest" file: "' . $mediaSqlModel->short . '".';
					}
				}
			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
		}

		if ($deleteDataID) {
			$file = BASE_PATH . DIRECTORY_SEPARATOR . Default_Model_Media::MEDIA_PATH . DIRECTORY_SEPARATOR . $deleteDataID;
			if (file_exists($file)) {
				if (!@unlink($file)) {
					$errors[] = 'Error while deleting the file: "' . $file . '".';
				}
			}
		}

		if ($deleteFileID) {
			$file = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $deleteFileID;
			if (file_exists($file)) {
				if (!@unlink($file)) {
					$errors[] = 'Error while deleting the file: "' . $file . '".';
				}
			}
		}

		$this->view->deleteAllLostFiles = $deleteAllLostFiles;
		$this->view->mediaType = $mediaType;
		$this->view->fixMediaErrors = $errors;
	}

	/**
     * Image Edit action.
     *
     * @return void
     */
    public function editImageAction ()
    {
		if($this->getRequest()->isXmlHttpRequest()) {
			$editData = $this->getRequest()->getParam('editData', NULL, FALSE);
			$originalImageId = $this->getRequest()->getParam('originalImageId', NULL, FALSE);
			$imgData = $this->getRequest()->getParam('imgData', NULL, FALSE);
			$imageResource = $this->getRequest()->getParam('imageResource', NULL, FALSE);
			$mediaImageModel = Doctrine_Query::create()
				->from('Default_Model_MediaImageEdit  me')
				->addWhere('me.image_resource = ? ', array($imageResource))
				->execute()
				->getFirst()
			;

			$storedFilePath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'edit' . '.png';
			list($type, $imgData) = explode(';', $imgData);
			list(, $imgData) = explode(',', $imgData);
			$imgData = base64_decode($imgData);

			file_put_contents($storedFilePath, $imgData);
			$editImageId = Default_Service_Media::fromFileToMediaID($storedFilePath, 'user');
			unlink($storedFilePath);

			if(!empty($mediaImageModel)){
				$existingEditImageId = $mediaImageModel['edited_image_id'];

				$mediaImageModel->original_image_id = $originalImageId;
				$mediaImageModel->edited_image_id = $editImageId;
				$mediaImageModel->edit_data = $editData;
				$mediaImageModel->save();

				$getImageResourceParams = explode('.',$imageResource);
				$currentModelName = 'Default_Model_'.join('', array_map('ucfirst', explode('_', $getImageResourceParams[0])));
				$currentModelId = $getImageResourceParams[1];
				$currentModelColumn = $getImageResourceParams[2];

				$editedModel = $currentModelName::getModelByID($currentModelId);

				/**
				 * delete old media, if it exists
				 */
				if(!$editedModel ||
					($editedModel->$currentModelColumn != $existingEditImageId)) {

					$oldMediaModel = Default_Model_Media::getModelByID($existingEditImageId);
					$oldMediaModel->hardDelete();
				}
			} else {
				$mediaImageEdit = new Default_Model_MediaImageEdit();
				$mediaImageEdit->original_image_id = $originalImageId;
				$mediaImageEdit->edited_image_id = $editImageId;
				$mediaImageEdit->edit_data = $editData;
				$mediaImageEdit->image_resource = $imageResource;

				$mediaImageEdit->save();
			}
			/**
			 * json
			 */
			$mediaModelValue = Default_Model_Media::getModelByID($editImageId);
			$previewLink  = $mediaModelValue->getLink();
			$bodyData = Zend_Json_Encoder::encode(array('previewLink'=>$previewLink,'editImageId'=>$editImageId));

			/**
			 * header
			 */
			$bodyContentHeader = 'application/json';

			Zend_Layout::getMvcInstance()->disableLayout();
			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			$this->getResponse()
				->setHeader('Content-Type', $bodyContentHeader)
				->setBody($bodyData);
		} else {
			$imageToEdit = $this->getRequest()->getParam('mediaImageId');
			$imageResource = str_replace('.create.','.edit.',$this->getRequest()->getParam('imageResource'));
			$getImageResourceParams = explode('.',$imageResource);
			$currentModelName = 'Default_Model_'.join('', array_map('ucfirst', explode('_', $getImageResourceParams[1])));

			$mediaImageModel = Doctrine_Query::create()
				->from('Default_Model_MediaImageEdit  me')
				->addWhere('me.image_resource = ?', array($imageResource))
				->execute()
				->getFirst()
			;
			$editData = '';
			if(!empty($mediaImageModel)){
				if($mediaImageModel['edited_image_id'] != $imageToEdit) {
					$this->view->flag = FALSE;

					$mediaModelValue = Default_Model_Media::getModelByID($imageToEdit);
					$this->view->mediaImageId = $imageToEdit;
				} else {
					$this->view->flag = TRUE;
					$mediaModelValue = Default_Model_Media::getModelByID($mediaImageModel['original_image_id']);
					$this->view->mediaImageId = $mediaImageModel['original_image_id'];
					$editData = $mediaImageModel['edit_data'];
				}
			} else {
				$this->view->flag = FALSE;
				$mediaModelValue = Default_Model_Media::getModelByID($imageToEdit);
				$this->view->mediaImageId = $imageToEdit;
			}
			$this->view->imageResource = $imageResource;
			$this->view->JsObjRef = $this->_mediaJsObjRef;

			if($mediaModelValue) {
				$this->view->imageLink = $mediaModelValue->getLink();
				$this->view->editData = $editData;
			}
		}
	}

}