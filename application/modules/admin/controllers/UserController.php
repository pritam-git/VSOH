<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/UserController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: UserController.php 201 2014-10-14 14:19:03Z nm $
 */

/**
 *
 *
 * Admin_UserController
 *
 *
 */
class Admin_UserController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Entity';
	private $_modelListShort = 'user';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'User';

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
	 * Initializes Admin_UserController.
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
			->setDefault('button_export', FALSE)
			->setButtonSeperator()
			->setButton($this->view->translate('Export to Excel'), array('action'=>'export-to-excel'), 'export', FALSE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
			->showListColumn('ch_code', 'Ch code', 80, FALSE, FALSE)
			->showListColumn('cadc', 'Cadc', 80, FALSE, FALSE)
		;

		if (Zend_Auth::getInstance()->getIdentity()->Role->short == 'admin') {
			$this->_modelList
				->addWhereDqlString('role.short != ? OR user.id = ? ', array('admin', Zend_Auth::getInstance()->getIdentity()->id), 'user', 'Role', 'role')
			;
		} else {
			$this->_modelList
				->addWhereDqlString('(role.short != ? AND role.short != ? ) OR user.id = ? ', array('admin', 'supervisor', Zend_Auth::getInstance()->getIdentity()->id), 'user', 'Role', 'role')
			;
		}

		$this->_modelList
			->setButtonSeperator()
			->setButton($this->view->translate('Import'), array('action'=>'import'), 'pdf', FALSE)
			->setButtonSeperator()
			->setButton($this->view->translate('Set Password'), array('action'=>'update'), 'update', FALSE)
			->setButtonSeperator()
			->setButton($this->view->translate('Edit Request'), array('action'=>'edit-request'), 'edit-request', TRUE)
			->setButtonSeperator()
			->setButton($this->view->translate('Send Email'), array('action'=>'send-email'), 'send-email', TRUE,FALSE,TRUE)
		;

//		$this->_modelList
//			->showAjax()
//			->doNotRedirect()
//			->setDeleteOldList()
//		;

		$multiRelationCondition = array();
		if (Zend_Auth::getInstance()->getIdentity()->Role->short != 'admin') {
			$multiRelationCondition = array(
				'Role'=>array(
					'short'=>array(
						'like'=>FALSE,
						'value'=>'admin',
					),
				),
			);
		}

		$addIgnoredColumns = array();
		if (Zend_Auth::getInstance()->getIdentity()->id == $this->_request->getParam('id', NULL, FALSE)) {
			$addIgnoredColumns = array(
				'disabled',
				'role_id',
				'activated_at',
			);
		}

		$this->_modelListConfig = array(
			'order'=>array(
				'salutation_id',
				'firstname',
				'lastname',
				'disabled',
				'role_id',
				'email',
				'password',
				'activated_at',
				'phone',
				'fax',
				'www',
				'media_image_id',
				'company',//
				'street',
				'address_line_1',
				'zip',
				'city',
				'country_id',
			),
			'addIgnoredColumns'=>array_merge($addIgnoredColumns, array(
				'disabled_reset_hash',
				'password_attempt',
				'latitude',
				'longitude',
				'login_with_http_user_agent',
				'login_with_http_accept',
				'login_with_http_accept_charset',
				'login_with_http_accept_encoding',
				'login_with_http_accept_language',
				'login_with_remote_addr',
				'login',
//				'password',
				'password_reset_hash',
//				'role_id',
				'last_login',
				'last_logout',
//				'activated_at',
				'billing_company',
				'billing_firstname',
				'billing_lastname',
				'billing_street',
				'billing_street_number',
				'billing_address_line_1',
				'billing_address_line_2',
				'billing_zip',
				'billing_city',
				'billing_country_id',
				'use_all_paymentservices',
				'address_line_2',
				'street_number',
				'mobile',
				'kanton_id',
				'manager_phone',
				'mobile',
				'vat_tax_id',
				'managing_director',
				'company_name',
				'edit_request_data',
				'branch',
				'contact_position',
				'contact_name',
				'commercial_register_id',
				'title',
				'opel_contract_pw',
				'opel_contract_nf',
				'opel_contract_type',
				'chevrolet_contract',
				'us_contract',
				'chevrolet_sp_fr',
				'chevrolet_sp_de',
				'gm_us_dealer',
				'gm_us_sp',
//				'sales_2017',
//				'annual_contribution_status',
//				'annual_contribution_volume',
//				'annual_contribution_sport',
//				'annual_contribution_opel',
//				'annual_contribution_chevrolet',
//				'annual_contribution_us',
//				'annual_contribution_total',
				'district',
			)),
			'addIgnoredM2nRelations'=>array(
			),
			'ignoreColumnRelation'=>array(
			),
			'ignoreColumnInMultiRelation'=>array(
				'Country'=>array('TerritoryIsoNr'),
				'BillingCountry'=>array('TerritoryIsoNr'),
			),
			'relationM2nValuesDefinition'=>array(
			),
			'mediaDirectory'=>array(
				'media_image_id'=>'/images/user',
			),
			'mediaRole'=>array(
				'media_image_id'=>'guest',
			),
			'columnLabels'=>array(
				'entity_media_image_id'=>'Image',
				'relation_m2n_entitym2nbrandoptionmodel'=>'Brands'
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'street'=>'text',
				'billing_street'=>'text',
				'country'=>'text',
				'email'=>'text',
				'www'=>'text',
				'company_name'=>'text',
				'branch'=>'text',
				'contact_name'=>'text',
				'contact_position'=>'text',
			),
			'addStaticFormElements'=>array(
			),
			'M2NRelations'=>array(
			),
			'replaceColumnValuesInMultiRelation'=>array(
			),
			'relationColumnInMultiRelation'=>array(
			),
			'multiRelationCondition'=>$multiRelationCondition,
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

		// $this->_modelList->addWhereDqlString('user.parent_user_id IS NULL AND user.super_parent_id IS NULL');
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
				'PRJ_Entity_Create_BeforeSave',
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
				'PRJ_Entity_Delete_BeforePreDelete',
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
		$entityId = $this->getRequest()->getParam('id');
		$entityModel = Default_Model_Entity::getModelById($entityId);
		$brandList = $entityModel->EntityM2nBrand->toArray();
		$brandOptionModel = Doctrine_Query::create()
			->from('Default_Model_BrandOptionModel')
			->execute()
		;
		$brandOptionList = $brandOptionModel->toArray();
		$brands = array();
		if(count($brandList)>0){
			foreach($brandList as $brand){
				$brandDetail = Default_Model_Brand::getModelById($brand['brand_id']);
				foreach($brandOptionList as $brandOption){
					if($brandOption['short'] == $brandDetail['short']){
						$brands[$brandDetail['id']] = $brandOption['id'];
					}
				}
			}
		}
		$this->view->brands = $brands;
		/**
		 * start model list
		 */
		$this->_modelList->editModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeFormOutput'=>array(
			),
			'doBeforeSave'=>array(
				'PRJ_Entity_Edit_BeforeSave',
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
                'PRJ_Entity_Edit_AfterSave',
			),
		)));
	}

	/**
	 * export action.
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
	 * import action.
	 *
	 * @return void
	 */
	public function importAction()
	{
		ini_set('memory_limit', '-1');

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Import');

		$resourceParam = $this->_request->getParam('resource', NULL, FALSE);
		$doParam = $this->_request->getParam('do', NULL, FALSE);

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
			'controller'=>'user',
			'module'=>'admin',
		);
		$this->view->backFormUrl = $this->view->url($backFormUrlArray , NULL, TRUE) . $backFormParamValues;

		/**
		 * form
		 */
		$form = new Admin_Form_User_Import();
		$form->buildMeUp(TRUE, FALSE, FALSE);

		$form
			->addDecorators(array(
				new L8M_Form_Decorator_ModelListFormBack($this->view->backFormUrl),
				new L8M_Form_Decorator_FormHasRequiredElements(),
				new L8M_Form_Decorator(array(
					'boxClass'=>'small l8m-model-form-base',
					'appendJsFile'=>'/js/jquery/admin/model-form-base.js',
				))
			))
		;
		$form
			->setAction(
				$this->view->url(
					array(
						'action'=>'import'
					)
				)
			)
		;

		/**
		 * form is submitted and valid
		 */
		if (($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost()) &&
			!$this->getRequest()->isXmlHttpRequest()) ||
			($resourceParam && $doParam)) {

			set_time_limit(0);

			/**
			 * List of Users imported from Excel
			 */
			$this->view->listOfNewEntities = array();
			$this->view->listOfFailedEntities = array();
			$this->view->listOfExistingEntities = array();
			$this->view->listOfIncompleteEntities = array();

			$tempFilePath = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR;

			/**
			 * Save uploaded Excel in temp upload
			 */
			if(isset($_FILES['EntityImportData']) && isset($_FILES['CadcImportFile'])) {
				$tempFile = $_FILES['EntityImportData'];
				if(file_exists($tempFilePath . 'EntityImportData.*')){
					$existingTempFile = glob($tempFilePath . 'EntityImportData.*');
					unlink($existingTempFile[0]);
				}

				$targetFileType = explode('.', $_FILES['EntityImportData']['name'])[count(explode('.', $_FILES['EntityImportData']['name'])) - 1];
				$targetFile = $tempFilePath . 'EntityImportData.' . $targetFileType;
				move_uploaded_file($tempFile['tmp_name'], $targetFile);

				//temp CADC import file
				$tempCadcFile = $_FILES['CadcImportFile'];
				if(file_exists($tempFilePath . 'CadcImportFile.*')){
					$existingCadcTempFile = glob($tempFilePath . 'CadcImportFile.*');
					unlink($existingCadcTempFile[0]);
				}

				$targetCadcFileType = explode('.', $_FILES['CadcImportFile']['name'])[count(explode('.', $_FILES['CadcImportFile']['name'])) - 1];
				$targetCadcFile = $tempFilePath . 'CadcImportFile.' . $targetCadcFileType;
				move_uploaded_file($tempCadcFile['tmp_name'], $targetCadcFile);
			} else {
				$existingTempFile = glob($tempFilePath . 'EntityImportData.*');
				$targetFile = $existingTempFile[0];

				$existingCadcTempFile = glob($tempFilePath . 'CadcImportFile.*');
				$targetCadcFile = $existingCadcTempFile[0];
			}

			/**
			 * Include PHPExcel
			 */
			require_once BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';

			/**
			 * Excel sheet obj and initialise current worksheet
			 * For Import data file
			 */
			$excelObj = new PHPExcel();
			$excelObj = PHPExcel_IOFactory::load($targetFile);
			$currentSheet = 0;
			$excelObj->setActiveSheetIndex($currentSheet);
			$worksheet = $excelObj->getActiveSheet();

			$currentRow = 2;
			$rowCount = $worksheet->getHighestDataRow();
			$currentCol = 0;
			$colCount = 29;

			/**
			 * Excel sheet obj and initialise current worksheet
			 * For CADC data file
			 */
			$cadcExcelObj = new PHPExcel();
			$cadcExcelObj = PHPExcel_IOFactory::load($targetCadcFile);
			$cadcExcelObj->setActiveSheetIndex(0);
			$cadcWorksheet = $cadcExcelObj->getActiveSheet();

			$cadcRowCount = $cadcWorksheet->getHighestDataRow();

			if ($currentRow < $rowCount) {
				for(; $currentRow <= $rowCount; $currentRow++) {
					$brand_ids = array();

					/**
					 * Column structure for worksheet
					 */
					$worksheetColStructure = array(
						0  => 'ch_code',
						1  => 'ch_code_dara',
						2  => 'district',
						3  => 'company',
						4  => 'street',
						5  => 'address_line_1',
						6  => 'zip',
						7  => 'city',
						8  => 'spoken_language',
						9  => 'opel_contract_pw',
						10 => 'opel_contract_nf',
						11 => 'opel_contract_type',
						12 => 'chevrolet_contract',
						13 => 'us_contract',
						14 => '',//dealer-west
						15 => '',//agent-west
						16 => '',//partner-west
						17 => '',//dealer-mitte
						18 => '',//agent-mitte
						19 => '',//partner-mitte
						20 => '',//dealer-ost
						21 => '',//agent-ost
						22 => '',//partner-ost
						23 => 'chevrolet_sp_fr-boolean',
						24 => 'chevrolet_sp_de-boolean',
						25 => 'gm_us_dealer-boolean',
						26 => 'gm_us_sp-boolean',
						27 => '',//if AJ is empty
						28 => 'www',
						29 => '',//if AK is empty
						30 => 'fax',
						31 => 'gl',
						32 => 'salutation_id',
						33 => 'lastname',
						34 => 'firstname',
						35 => 'email',
						36 => 'phone',
						37 => 'sales_2017',
						38 => 'brand',//opel
						39 => 'brand',//chevrolet
						40 => 'brand',//us
						41 => 'is_member',
						42 => 'annual_contribution_status',
						43 => 'annual_contribution_volume',
						44 => 'annual_contribution_sport',
						45 => 'annual_contribution_opel',
						46 => 'annual_contribution_chevrolet',
						47 => 'annual_contribution_us',
						48 => 'annual_contribution_total',
						49 => '',
						50 => '',
						51 => '',
						52 => '',
						53 => '',
						54 => '',
						55 => '',
						56 => '',
						57 => ''
					);

					/**
					 * Check if CH code exists for Excel row, CH Code is mandatory
					 */
					$chCode = trim($worksheet->getCellByColumnAndRow($currentCol, $currentRow)->getValue());
					if($chCode == '') {
// 						if(!array_key_exists($currentRow, $this->view->listOfFailedEntities))
// 							$this->view->listOfFailedEntities[$currentRow] = 'CH_CODE is missing';

// 						continue;

						$chCode = 'FE_' . md5(trim($worksheet->getCellByColumnAndRow(35, $currentRow)->getValue())); // generate from email
						//remove row from failed entity list if ch_code found
						if(array_key_exists($currentRow, $this->view->listOfFailedEntities)) {
							unset($this->view->listOfFailedEntities[$currentRow]);
						}
					} else {
						//remove row from failed entity list if ch_code found
						if(array_key_exists($currentRow, $this->view->listOfFailedEntities)) {
							unset($this->view->listOfFailedEntities[$currentRow]);
						}
					}

					/**
					 * Find for matching CH_CODE and CADC code in second imported file (i.e. CADC import file)
					 * check if user is parent user (.01 postfix) or sub_parent user (other post fixes)
					 */
					$super_parent_id = NULL;
					$cadc_code = NULL;
					$is_parent = FALSE;
					$cadcCurrentRow = 2;
					if ($cadcCurrentRow < $cadcRowCount) {
						$cadcCheck = FALSE;
						for(; $cadcCurrentRow <= $cadcRowCount; $cadcCurrentRow++) {
							//get current row ch_code
							$cadcCHCode = trim($cadcWorksheet->getCell('A' . $cadcCurrentRow)->getValue());
							if($cadcCHCode == $chCode){
								//if ch_code found in CADC import file then set as TRUE.
								$cadcCheck = TRUE;

								//get CADC code from matching row.
								$cadcCellValue = trim($cadcWorksheet->getCell('D' . $cadcCurrentRow)->getValue());
								$cadcSplitArray = explode('.',$cadcCellValue);
								$cadc_code = $cadcCellValue;
								if($cadcSplitArray[1] == '01'){
									//set user is parent
									$is_parent = TRUE;
								} else {
									/*
									 * user is sub-parent
									 * so checking for his parent user in DB
									 */
									$superParentModel = Default_Model_Entity::getModelByColumn('ch_code', $cadcSplitArray[0]);
									if($superParentModel){
										//parent user found so continue with child user entry
										$super_parent_id = $superParentModel->id;
									} else {
										//parent user not found in DB so chCOde is added in array listOfIncompleteEntities
										$this->view->listOfIncompleteEntities[$chCode] = 'Missing super_parent_id';
									}
								}

								break;
							}
						}

						if(!$cadcCheck){
// 							$this->view->listOfIncompleteEntities[$chCode] = 'Missing in CADC import file';
// 							continue;
							$cadc_code = 'FE_' . md5(trim($worksheet->getCellByColumnAndRow(35, $currentRow)->getValue())) . '.01'; // generate from email
							$is_parent = TRUE;
						}
					}

					/**
					 * Check if user with CH Code exists
					 * If not, Create a new user
					 */
					$entityModel = Default_Model_Entity::getModelByColumn('ch_code', $chCode);

					if($entityModel) {
						if(!in_array($chCode, $this->view->listOfExistingEntities))
							$this->view->listOfExistingEntities[] = $chCode;
						//set new_flag to false cz it is already in db
						$newModelFlag = FALSE;
					} else {
						//new model for insert query
						$entityModel = new Default_Model_Entity();

						//set new_flag to true
						$newModelFlag = TRUE;
					}

					//assign cadc code to entityModel if found from the opel sheet
					if(!empty($cadc_code))
						$entityModel->cadc = $cadc_code;

					//assign super parent id to the entityModel if it exists
					if($super_parent_id)
						$entityModel->super_parent_id = $super_parent_id;

					/**
					 * Loop through column structure of worksheet
					 * If existing user, do not change wrong values from Excel
					 * Else update values and add new values
					 */
					foreach($worksheetColStructure as $index=>$worksheetCol) {
						//if ch_code found in incomplete list then break the array.
						if(array_key_exists($chCode, $this->view->listOfIncompleteEntities)) {
							break;
						}

						$colString = PHPExcel_Cell::stringFromColumnIndex($index);
						/**
						 * Check if current column have calculation formula in excel sheet
						 * Then check and get the calculated cell value for that
						 */
						$cellValue = trim($worksheet->getCell($colString . $currentRow)->getValue());
						if(substr($cellValue, 0, 1) == '=') {
							try {
								$cellValue = trim($worksheet->getCell($colString . $currentRow)->getCalculatedValue());
							} catch (Exception $e) {
								$cellValue = 0;
							}
						}

						//get conditional data
						//contract_type,region and email and phone
						switch ($colString){
							case 'O':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('dealer');
									$regionModel = Default_Model_Region::getModelByShort('west');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'P':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('agent');
									$regionModel = Default_Model_Region::getModelByShort('west');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'Q':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('partner');
									$regionModel = Default_Model_Region::getModelByShort('west');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'R':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('dealer');
									$regionModel = Default_Model_Region::getModelByShort('mitte');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'S':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('agent');
									$regionModel = Default_Model_Region::getModelByShort('mitte');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'T':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('partner');
									$regionModel = Default_Model_Region::getModelByShort('mitte');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'U':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('dealer');
									$regionModel = Default_Model_Region::getModelByShort('ost');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'V':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('agent');
									$regionModel = Default_Model_Region::getModelByShort('ost');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'W':
								if(!empty($cellValue)){
									$contractModel = Default_Model_ContractType::getModelByShort('partner');
									$regionModel = Default_Model_Region::getModelByShort('ost');
									if($contractModel)
										$entityModel->contract_type_id = $contractModel->id;
									if($regionModel)
										$entityModel->region_id = $regionModel->id;
								}
								break;
							case 'AJ':
								if(empty($cellValue))
									$cellValue = trim($worksheet->getCell('AB' . $currentRow)->getValue());
								break;
							case 'AK':
								if(empty($cellValue))
									$cellValue = trim($worksheet->getCell('AD' . $currentRow)->getValue());
								break;
							default:
								break;
						}

						if(($worksheetCol == '')) {
							continue;
						}

						if($cellValue == '') {
							/**
							 * Check if email id is missing
							 */
							if(!in_array($chCode, $this->view->listOfExistingEntities)) {
								if($worksheetCol == 'email') {
									$this->view->listOfIncompleteEntities[$chCode] = 'Missing email/login';
									$newModelFlag = FALSE;
								}
							}
						} else {
							//get salutation id from salutation text
							if($worksheetCol == 'salutation_id'){
								$salutationModel = Doctrine_Query::create()
									->from('Default_Model_SalutationTranslation')
									->where('name = ?', array($cellValue))
									->limit(1)
									->execute()
									->getFirst()
								;

								//check if get salutation id
								if($salutationModel){
									$cellValue = $salutationModel->id;
								} else {
									//salutation id not found - add in incomplete list
									if($newModelFlag)
										$newModelFlag = !$newModelFlag;

									if(!in_array($chCode, $this->view->listOfExistingEntities)) {
										$this->view->listOfIncompleteEntities[$chCode] = 'Missing salutation_id (' . $cellValue . ')';
										continue;
									}
								}
							}

							//check for is user allowed for login or get any information via mail from the system
							if($worksheetCol == 'is_member'){
								$entityModel->disabled = FALSE;
								if($is_parent && !$super_parent_id){
									//CADC == .01
									if(strtoupper($cellValue) == 'JA')
										$cellValue = TRUE;
									else
										$cellValue = FALSE;
								} else {
									//CADC > .01
									$cellValue = FALSE;
									$entityModel->disabled = TRUE;
								}
							}

							/**
							 * Check if value having brand
							 * Then store values in respective m2n relation table
							 */
							if($worksheetCol == 'brand') {
								if(!empty($cellValue)){
									//get brand_id
									switch ($colString){
										case 'AM':
											$brandModel = Default_Model_Brand::getModelByShort('opel');
											if($brandModel && !in_array($brandModel->id,$brand_ids))
												array_push($brand_ids,$brandModel->id);
											break;
										case 'AN':
											$brandModel = Default_Model_Brand::getModelByShort('chevrolet');
											if($brandModel && !in_array($brandModel->id,$brand_ids))
												array_push($brand_ids,$brandModel->id);
											break;
										case 'AO':
											$brandModel = Default_Model_Brand::getModelByShort('us');
											if($brandModel && !in_array($brandModel->id,$brand_ids))
												array_push($brand_ids,$brandModel->id);
											break;
										default:
											break;
									}
								}
								continue;
							}

							/**
							 * Check if current column is integer data type
							 * Then check and convert the value in proper integer number
							 */
							if(substr($worksheetCol, -8) == '-boolean') {
								$worksheetCol = str_replace('-boolean', '', $worksheetCol);
								if(!empty($cellValue)){
									$cellValue = TRUE;
								} else {
									$cellValue = FALSE;
								}
							}

							//check and convert cell value integer data type field
							if($worksheetCol == 'annual_contribution_total'){
								$cellValue = (int)$cellValue;
							}

							//set sheet cell value to the entity model to save in DB.
							$entityModel->$worksheetCol = $cellValue;

							//if sheet column 'email', set that value to `login` field of `entity` table.
							if($worksheetCol == 'email') {
								$entityModel->login = $cellValue;
							}
						}
					}

					/**
					 * Define role as user
					 */
					if(!$entityModel->role_id)
						$entityModel->role_id = 5;

					/**
					 * Define department as Management
					 */
					if(!$entityModel->department_id)
						$entityModel->department_id = 5;

					/**
					 * If all data accurate for adding new user allow user to be added
					 */
					if($newModelFlag)
						$this->view->listOfNewEntities[] = $chCode;

					/**
					 * If data has been requested to be saved or updated
					 */
					if (isset($doParam) &&
						isset($resourceParam)) {

						if ((($doParam == 'add-new' && $resourceParam == $chCode) ||
							($doParam == 'add-new-all' && $resourceParam == 'all') &&
							in_array($chCode, $this->view->listOfNewEntities)) ||
							(($doParam == 'update-existing' && $resourceParam == $chCode) ||
							($doParam == 'update-existing-all' && $resourceParam == 'all') &&
							in_array($chCode, $this->view->listOfExistingEntities))
							) {

							try {
								$entityModel->activated_at = date('Y-m-d H:i:s');
								$entityModel->save();
								$entity_id = $entityModel->id;

								//if ch_code is in existing entity list?
								if(!empty($brand_ids) && in_array($chCode, $this->view->listOfExistingEntities)) {
									//get existing brand-entity relations
									//change brand_ids array as per data got from EntityM2nBrand
									foreach ($entityModel->EntityM2nBrand as $brandEntityModel) {
										if (!in_array($brandEntityModel->brand_id, $brand_ids)) {
											//delete from DB
											$brandEntityModel->hardDelete();
										} else {
											//remove from brand_ids array
											$position = array_search($brandEntityModel->brand_id, $brand_ids);
											unset($brand_ids[$position]);
										}
									}
								}

								//insert new relations in m2n table db
								if(!empty($brand_ids)) {
									foreach ($brand_ids as $brand_id){
										//new model for insert query
										$entityM2nBrandModel = new Default_Model_EntityM2nBrand();
										$entityM2nBrandModel->entity_id = $entity_id;
										$entityM2nBrandModel->brand_id = $brand_id;
										$entityM2nBrandModel->save();
									}
								}

								//remove inserted CH_CODE from "listOfNewEntities" array
								if(in_array($chCode, $this->view->listOfNewEntities)) {
									array_splice($this->view->listOfNewEntities, array_search($chCode, $this->view->listOfNewEntities), 1);
								}

								//add inserted CH_CODE in "listOfExistingEntities" array
								if(!in_array($chCode, $this->view->listOfExistingEntities)) {
									$this->view->listOfExistingEntities[] = $chCode;
								}

								//remove current row from failed entity list if no error  found for that entry.
								if(array_key_exists($currentRow, $this->view->listOfFailedEntities)) {
									unset($this->view->listOfFailedEntities[$currentRow]);
								}
							} catch(Exception $e) {
								//remove the ch_code from new entity list or existing entity list
								if(in_array($chCode, $this->view->listOfNewEntities)) {
									array_splice($this->view->listOfNewEntities, array_search($chCode, $this->view->listOfNewEntities), 1);
								} else
								if(in_array($chCode, $this->view->listOfExistingEntities)) {
									array_splice($this->view->listOfExistingEntities, array_search($chCode, $this->view->listOfExistingEntities), 1);
								}

								$this->view->listOfFailedEntities[$currentRow] = $e->getMessage();
							}
						}
					}
				}
			}

		} else {
			$this->view->form = $form;
		}
	}

	/**
	 * export users in excel sheet action.
	 *
	 * @return void
	 */
	public function exportToExcelAction(){
		/**
		 * Get list of all entities
		 */
		$entityCollection = Doctrine_Query::create()
			->from('Default_Model_Entity')
			->execute()
		;

		/**
		 * Spreadsheet Column headers, sequence and alignment
		 */
		$spreadsheetColumns = array(
			0  => array(
				'name' => 'CH-Code',
				'align' => 'center'
			),
			1  => array(
				'name' => 'CH-Code DARA',
				'align' => ''
			),
			2  => array(
				'name' => 'District',
				'align' => ''
			),
			3  => array(
				'name' => 'Firma',
				'align' => 'left'
			),
			4  => array(
				'name' => 'Adresse 1',
				'align' => 'left'
			),
			5  => array(
				'name' => 'Adresse 2 Postfach',
				'align' => ''
			),
			6  => array(
				'name' => 'PLZ',
				'align' => 'left'
			),
			7  => array(
				'name' => 'Ort',
				'align' => 'left'
			),
			8  => array(
				'name' => 'Sparche',
				'align' => 'left'
			),
			9  => array(
				'name' => 'Opel-Vertrag PW',
				'align' => ''
			),
			10 => array(
				'name' => 'Opel-Vertrag NF',
				'align' => ''
			),
			11 => array(
				'name' => 'Opel-Contract Type',
				'align' => ''
			),
			12 => array(
				'name' => 'Chevrolet-Vertrag',
				'align' => ''
			),
			13 => array(
				'name' => 'US-Vertrag',
				'align' => ''
			),
			14 => array(
				'name' => 'Gruppe West Handler',
				'align' => 'center'
			),
			15 => array(
				'name' => 'Gruppe West Agent',
				'align' => 'center'
			),
			16 => array(
				'name' => 'Gruppe West SP',
				'align' => 'center'
			),
			17 => array(
				'name' => 'Gruppe Mitte Handler',
				'align' => 'center'
			),
			18 => array(
				'name' => 'Gruppe Mitte Agent',
				'align' => 'center'
			),
			19 => array(
				'name' => 'Gruppe Mitte SP',
				'align' => 'center'
			),
			20 => array(
				'name' => 'Gruppe Ost Handler',
				'align' => 'center'
			),
			21 => array(
				'name' => 'Gruppe Ost Agent',
				'align' => 'center'
			),
			22 => array(
				'name' => 'Gruppe Ost SP',
				'align' => 'center'
			),
			23 => array(
				'name' => 'Chevrolet SP franz',
				'align' => 'center'
			),
			24 => array(
				'name' => 'Chevrolet SP deutsch',
				'align' => 'center'
			),
			25 => array(
				'name' => 'GM US Handler',
				'align' => 'center'
			),
			26 => array(
				'name' => 'GM US SP',
				'align' => 'center'
			),
			27 => array(
				'name' => 'E-Mail',
				'align' => 'left'
			),
			28 => array(
				'name' => 'URL',
				'align' => 'left'
			),
			29 => array(
				'name' => 'Telefon Zentrale',
				'align' => 'left'
			),
			30 => array(
				'name' => 'Fax Zentrale',
				'align' => 'left'
			),
			31 => array(
				'name' => 'GL',
				'align' => ''
			),
			32 => array(
				'name' => 'Anrede Gl',
				'align' => 'left'
			),
			33 => array(
				'name' => 'Nachname GL',
				'align' => 'left'
			),
			34 => array(
				'name' => 'Vorname GL',
				'align' => 'left'
			),
			35 => array(
				'name' => 'E-Mail GL',
				'align' => 'left'
			),
			36 => array(
				'name' => 'Telefone GL',
				'align' => 'left'
			),
			37 => array(
				'name' => 'Verkaufe 2017',
				'align' => '',
			),
			38 => array(
				'name' => 'OPEL',
				'align' => 'center',
			),
			39 => array(
				'name' => 'CHEVROLET',
				'align' => 'center',
			),
			40 => array(
				'name' => 'US',
				'align' => 'center',
			),
			41 => array(
				'name' => 'VSGMH',
				'align' => '',
			),
			42 => array(
				'name' => 'Jahres-Beitrag Status',
				'align' => '',
			),
			43 => array(
				'name' => 'Jahres-Beitrag Volumen',
				'align' => '',
			),
			44 => array(
				'name' => 'Jahres-Beitrag Sport',
				'align' => '',
			),
			45 => array(
				'name' => 'Jahres-Beitrag Opel',
				'align' => '',
			),
			46 => array(
				'name' => 'Jahres-Beitrag Chevrolet',
				'align' => '',
			),
			47 => array(
				'name' => 'Jahres-Beitrag US',
				'align' => '',
			),
			48 => array(
				'name' => 'Jahres-Beitrag TOTAL',
				'align' => '',
			),
			49 => array(
				'name' => 'Jahres-Beitrag pauschal',
				'align' => '',
			),
			50 => array(
				'name' => 'Benutzername Opel',
				'align' => '',
			),
			51 => array(
				'name' => 'Kennwort Opel',
				'align' => '',
			),
			52 => array(
				'name' => 'Benutzername Chevi',
				'align' => '',
			),
			53 => array(
				'name' => 'Kennwort Chevi',
				'align' => '',
			),
			54 => array(
				'name' => 'Benutzername US',
				'align' => '',
			),
			55 => array(
				'name' => 'Kennwort US',
				'align' => '',
			),
			56 => array(
				'name' => '',
				'align' => '',
			),
			57 => array(
				'name' => 'Anderungs-datum',
				'align' => '',
			)
		);

		/**
		 * Sequence and column names in table corresponding to spreadsheet
		 */
		$tableColumns = array(
			0 => 'ch_code',
			1 => 'ch_code_dara',
			2 => 'district',
			3 => 'company',
			4 => 'street',
			5 => 'address_line_1',
			6 => 'zip',
			7 => 'city',
			8 => 'spoken_language',
			9  => 'opel_contract_pw',
			10 => 'opel_contract_nf',
			11 => 'opel_contract_type',
			12 => 'chevrolet_contract',
			13 => 'us_contract',
			14 => '',//dealer-west
			15 => '',//agent-west
			16 => '',//partner-west
			17 => '',//dealer-mitte
			18 => '',//agent-mitte
			19 => '',//partner-mitte
			20 => '',//dealer-ost
			21 => '',//agent-ost
			22 => '',//partner-ost
			23 => 'chevrolet_sp_fr-boolean',
			24 => 'chevrolet_sp_de-boolean',
			25 => 'gm_us_dealer-boolean',
			26 => 'gm_us_sp-boolean',
			27 => '',//if AJ is empty
			28 => 'www',
			29 => '',//if AK is empty
			30 => 'fax',
			31 => 'gl',
			32 => 'salutation_id',
			33 => 'lastname',
			34 => 'firstname',
			35 => 'email',
			36 => 'phone',
			37 => 'sales_2017',
			38 => 'brand',//opel
			39 => 'brand',//chevrolet
			40 => 'brand',//us
			41 => 'is_member',
			42 => 'annual_contribution_status',
			43 => 'annual_contribution_volume',
			44 => 'annual_contribution_sport',
			45 => 'annual_contribution_opel',
			46 => 'annual_contribution_chevrolet',
			47 => 'annual_contribution_us',
			48 => 'annual_contribution_total',
			49 => '',
			50 => '',
			51 => '',
			52 => '',
			53 => '',
			54 => '',
			55 => '',
			56 => '',
			57 => ''
		);

		/**
		 * Create excel object
		 */
		require_once BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';
		$excelObj = new PHPExcel();

		$excelObj->getProperties()
			->setCreator("VSOH Admin")
			->setTitle("EntityData_" . date("Y-m-d-H-i-s"))
			->setDescription("Entity data backup " . date("Y-m-d-H-i-s") . ".")
		;

		/**
		 * Define styles for border, header and content
		 */
		$defaultBorder = array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb'=>'000000')
		);
		$headerStyle = array(
			'borders' => array(
				'allborders' => $defaultBorder
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'b8cb00'),
			),
			'font' => array(
				'bold' => true,
				'size' => 10
			)
		);
		$contentStyle = array(
			'borders' => array(
				'allborders' => $defaultBorder
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb'=>'ffffff'),
			),
			'font' => array(
				'size' => 10
			)
		);

		$worksheet = $excelObj->setActiveSheetIndex(0);

		$currentRow = 1;

		/**
		 * Add Column headers and set column alignments
		 */
		foreach($spreadsheetColumns as $index=>$columnHeader) {
			$colString = PHPExcel_Cell::stringFromColumnIndex($index);
			$worksheet->setCellValue($colString . $currentRow, $columnHeader['name']);

			$columnAlignment = $excelObj->getActiveSheet()
				->getStyle($colString)
				->getAlignment()
			;

			if($columnHeader['align'] == 'center') {
				$columnAlignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			} else
				if($columnHeader['align'] == 'left') {
					$columnAlignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				} else
					if($columnHeader['align'] == 'right') {
						$columnAlignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					}
			$excelObj->getActiveSheet()
				->getColumnDimension($colString)
				->setAutoSize(true)
			;
		}
		$excelObj->getActiveSheet()->getStyle('A1:' . PHPExcel_Cell::stringFromColumnIndex(57) . '1')->applyFromArray($headerStyle);
		$excelObj->getActiveSheet()->getStyle('A1:' . PHPExcel_Cell::stringFromColumnIndex(57) . '1')
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		;

		$brandOptionModelIdForOpel = Default_Model_BrandOptionModel::getModelByShort('opel')->id;

		/**
		 * Add Users to excel
		 */
		foreach($entityCollection as $entityModel) {
			++$currentRow;
			$entityLanguage = '';

			foreach($tableColumns as $index=>$tableColumn) {
				$colString = PHPExcel_Cell::stringFromColumnIndex($index);

				//skip for column 'O' to 'W' - region and contract_type
				if(14 <= $index && $index <= 22){
					continue;
				}

				/**
				 * Check if current column is integer data type
				 * Then check and convert the value in proper integer number
				 */
				if(substr($tableColumn, -8) == '-boolean') {
					$tableColumn = str_replace('-boolean', '', $tableColumn);
					if(!empty($entityModel->$tableColumn)){
						$worksheet->setCellValue($colString . $currentRow, 'X');
					}
					continue;
				}

				/**
				 * Check if current column is integer data type
				 * Then check and convert the value in proper integer number
				 */
				if($tableColumn == 'is_member') {
					if(!$brandOptionModelIdForOpel) {
						$worksheet->setCellValue($colString . $currentRow, 'NEIN');
						continue;
					}

					$entityM2nBrandOptionModel = Default_Model_EntityM2nBrandOptionModel::createQuery()
						->addWhere('entity_id = ? AND brand_option_model_id = ?', array($entityModel->id, $brandOptionModelIdForOpel))
						->execute()
						->getFirst()
					;

					if(!$entityM2nBrandOptionModel) {
						$worksheet->setCellValue($colString . $currentRow, 'NEIN');
						continue;
					}

					$brandOptionModelValue = Default_Model_EntityM2nBrandOptionModelValues::getModelByColumn('entity_m2n_brand_option_model_id', $entityM2nBrandOptionModel->id);

					if(!empty($brandOptionModelValue->is_member) && $brandOptionModelValue->is_member == 1){
						$worksheet->setCellValue($colString . $currentRow, 'JA');
					} else {
						$worksheet->setCellValue($colString . $currentRow, 'NEIN');
					}
					continue;
				}

				if(($tableColumn == '') ||
					($tableColumn == 'brand') ||
					($entityModel->$tableColumn == '')  ||
					($tableColumn == 'salutation_id')) {

					continue;
				}

				//set column value in excel from entity model
				$worksheet->setCellValue($colString . $currentRow, $entityModel->$tableColumn);
			}

			/**
			 * Set salutation by gender and country
			 * If not found use default language 'de'
			 */
			if(isset($entityModel->salutation_id) &&
				($entityModel->salutation_id != '')) {

				$salutationModel = Doctrine_Query::create()
					->from('Default_Model_SalutationTranslation')
					->where('id = ? AND lang = ?', array($entityModel->salutation_id, $entityLanguage))
					->limit(1)
					->execute()
				;
				if(count($salutationModel) == 0) {
					$salutationModel = Doctrine_Query::create()
						->from('Default_Model_SalutationTranslation')
						->where('id = ? AND lang = ?', array($entityModel->salutation_id, 'de'))
						->limit(1)
						->execute()
					;
				}

				if(isset($salutationModel->name))
					$worksheet->setCellValue(PHPExcel_Cell::stringFromColumnIndex(32) . $currentRow, $salutationModel->name);
			}

			/**
			 * check for contract type and region id.
			 * Display X for matching column in sheet.
			 */
			if($entityModel->contract_type_id && $entityModel->region_id) {
				if($entityModel->ContractType->short == 'dealer' && $entityModel->Region->short == 'west'){
					$worksheet->setCellValue('O' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'agent' && $entityModel->Region->short == 'west'){
					$worksheet->setCellValue('P' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'partner' && $entityModel->Region->short == 'west') {
					$worksheet->setCellValue('Q' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'dealer' && $entityModel->Region->short == 'mitte') {
					$worksheet->setCellValue('R' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'agent' && $entityModel->Region->short == 'mitte') {
					$worksheet->setCellValue('S' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'partner' && $entityModel->Region->short == 'mitte') {
					$worksheet->setCellValue('T' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'dealer' && $entityModel->Region->short == 'ost') {
					$worksheet->setCellValue('U' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'agent' && $entityModel->Region->short == 'ost') {
					$worksheet->setCellValue('V' . $currentRow, 'X');
				} else
				if($entityModel->ContractType->short == 'partner' && $entityModel->Region->short == 'ost') {
					$worksheet->setCellValue('W' . $currentRow, 'X');
				} else {

				}
			}

			/**
			 * get all brand_id for entity
			 * display X matching column.
			 */
			foreach ($entityModel->EntityM2nBrand as $brandEntityModel) {
				if($brandEntityModel->Brand->short == 'opel'){
					$worksheet->setCellValue('AM' . $currentRow, 'X');
				} else
				if($brandEntityModel->Brand->short == 'chevrolet') {
					$worksheet->setCellValue('AN' . $currentRow, 'X');
				} else
				if($brandEntityModel->Brand->short == 'us') {
					$worksheet->setCellValue('AO' . $currentRow, 'X');
				} else{}
			}
		}

		/**
		 * Add Style to content
		 */
		$excelObj->getActiveSheet()->getStyle('A2:' . PHPExcel_Cell::stringFromColumnIndex(57) . $currentRow)->applyFromArray($contentStyle);

		/**
		 * Produce output and force download at browser
		 */
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="EntityData_' . date("Y-m-d-H-i-s") . '.xlsx"');

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
		$objWriter->save('php://output');

		exit();
	}

	/**
	 * Display list of users for setting their password.
	 *
	 * @return void
	 */
	public function updateAction ()
	{
		/**
		 * Get list of all entities
		 */
		$entityCollection = Doctrine_Query::create()
			->from('Default_Model_Entity')
			->execute()
		;

		/**
		 * prepare back button
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

		$this->view->backBtnUrl = $this->view->url(array('action'=>'list','controller'=>'user','module'=>'admin') , NULL, TRUE) . $paramValues;
		$this->view->entityCollection = $entityCollection;

		if ($this->_request->getParam('do')) {
			$postData = $this->_request->getPost();
			if(isset($postData['entity']) && !empty(count($postData['entity']))){
				/**
				 * Spreadsheet Column headers, sequence and alignment
				 */
				$spreadsheetColumns = array(
					0  => array(
						'name' => 'SalutationID',
						'align' => 'center'
					),
					1  => array(
						'name' => 'First name',
						'align' => ''
					),
					2  => array(
						'name' => 'Last name',
						'align' => ''
					),
					3  => array(
						'name' => 'Password',
						'align' => ''
					),
					4  => array(
						'name' => 'Company',
						'align' => ''
					),
					5  => array(
						'name' => 'Street',
						'align' => ''
					),
					6  => array(
						'name' => 'PLZ',
						'align' => ''
					),
					7  => array(
						'name' => 'City',
						'align' => ''
					),
					8  => array(
						'name' => 'CH-Code',
						'align' => ''
					),
					9  => array(
						'name' => 'Sprache',
						'align' => ''
					),
					10  => array(
						'name' => 'Login',
						'align' => ''
					),
					11  => array(
						'name' => 'Is Member',
						'align' => ''
					)
				);

				/**
				 * Sequence and column names in table corresponding to spreadsheet
				 */
				$tableColumns = array(
					0 => 'salutation_id',
					1 => 'firstname',
					2 => 'lastname',
					3 => 'password',
					4 => 'company',
					5 => 'street',
					6 => 'zip',
					7 => 'city',
					8 => 'ch_code',
					9 => 'spoken_language',
					10 => 'login',
					11 => 'is_member'
				);

				/**
				 * Create excel object
				 */
				require_once BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'PHPExcel' . DIRECTORY_SEPARATOR . 'IOFactory.php';
				$excelObj = new PHPExcel();

				$excelObj->getProperties()
					->setCreator("VSOH Admin")
					->setTitle("EntityPassword_" . date("Y-m-d-H-i-s"))
					->setDescription("Entity password " . date("Y-m-d-H-i-s") . ".")
				;

				/**
				 * Define styles for border, header and content
				 */
				$defaultBorder = array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb'=>'000000')
				);
				$headerStyle = array(
					'borders' => array(
						'allborders' => $defaultBorder
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb'=>'b8cb00'),
					),
					'font' => array(
						'bold' => true,
						'size' => 10
					)
				);
				$contentStyle = array(
					'borders' => array(
						'allborders' => $defaultBorder
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb'=>'ffffff'),
					),
					'font' => array(
						'size' => 10
					)
				);

				$worksheet = $excelObj->setActiveSheetIndex(0);

				$currentRow = 1;

				/**
				 * Add Column headers and set column alignments
				 */
				foreach ($spreadsheetColumns as $index=>$columnHeader) {
					$colString = PHPExcel_Cell::stringFromColumnIndex($index);
					$worksheet->setCellValue($colString . $currentRow, $columnHeader['name']);

					$columnAlignment = $excelObj->getActiveSheet()
						->getStyle($colString)
						->getAlignment()
					;

					/**
					 * alignment
					 */
					if ($columnHeader['align'] == 'center') {
						$columnAlignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					} else
					if ($columnHeader['align'] == 'left') {
						$columnAlignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					} else
					if ($columnHeader['align'] == 'right') {
						$columnAlignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					}
					$excelObj->getActiveSheet()
						->getColumnDimension($colString)
						->setAutoSize(true)
					;
				}
				$excelObj->getActiveSheet()->getStyle('A1:' . PHPExcel_Cell::stringFromColumnIndex(count($spreadsheetColumns) - 1) . '1')->applyFromArray($headerStyle);
				$excelObj->getActiveSheet()->getStyle('A1:' . PHPExcel_Cell::stringFromColumnIndex(count($spreadsheetColumns) - 1) . '1')
					->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				;

				/**
				 * Add Users to excel
				 */
				foreach ($postData['entity'] as $entityId) {
					++$currentRow;
					$entityLanguage = '';

					//get entity model from entity_id
					$entityModel = Default_Model_Entity::getModelByID($entityId);

					//generate random password.
					$newPassword = L8M_Library::generatePassword(12);
					$entityModel->password = L8M_Library::generateDBPasswordHash($newPassword);
					$entityModel->save();

					foreach ($tableColumns as $index=>$tableColumn) {
						$colString = PHPExcel_Cell::stringFromColumnIndex($index);

						if (($tableColumn == '') ||
							($entityModel->$tableColumn == '')) {

							continue;
						}

						if ($tableColumn == 'salutation_id'){
						    /**
						     * Set salutation by gender and country
						     * If not found use default language 'de'
						     */
						    if (isset($entityModel->salutation_id) &&
						        ($entityModel->salutation_id != '')) {

						            $salutationModel = Doctrine_Query::create()
							            ->from('Default_Model_SalutationTranslation')
							            ->where('id = ? AND lang = ?', array($entityModel->salutation_id, $entityLanguage))
							            ->limit(1)
							            ->execute()
						            ;
						            if (count($salutationModel) == 0) {
						                $salutationModel = Doctrine_Query::create()
							                ->from('Default_Model_SalutationTranslation')
							                ->where('id = ? AND lang = ?', array($entityModel->salutation_id, 'de'))
							                ->limit(1)
							                ->execute()
						                ;
						            }
						            foreach($salutationModel as $salutationModel) {
						                break;
						            }

						            if(isset($salutationModel->name))
						                $worksheet->setCellValue($colString . $currentRow, $salutationModel->name);
						        }
						} else
					    if ($tableColumn == 'password'){
					        //Set password value
					        $worksheet->setCellValue($colString . $currentRow, $newPassword);
						} else {
						    //set column value in excel from entity model
						    $worksheet->setCellValue($colString . $currentRow, $entityModel->$tableColumn);
						}
					}
				}
				/**
				 * Add Style to content
				 */
				$excelObj->getActiveSheet()->getStyle('A2:' . PHPExcel_Cell::stringFromColumnIndex(count($spreadsheetColumns) - 1) . $currentRow)->applyFromArray($contentStyle);

				/**
				 * Produce output and force download at browser
				 */
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="EntityData_' . date("Y-m-d-H-i-s") . '.xlsx"');

				$objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
				$objWriter->save('php://output');

				exit();
			}
		}
	}

	/**
	 * display all edited value and update value if supervisor wants to update
	 *
	 * @return void
	 */
	public function editRequestAction ()
	{
		$userId = $this->_request->getParam('id');

		/**
		 * prepare back button url
		 */
		$queryStringArray = array(
			'page',
			'rp',
			'query',
			'qtype',
			'sortorder',
			'sortname',
		);
		$queryString = '?';
		foreach ($queryStringArray as $param) {
			$queryString .= $param . '=' . $this->getRequest()->getParam($param, NULL, FALSE) . '&';
		}
		$backUrl = $this->view->url(array('action'=>'list','controller'=>'user','module'=>'admin'), NULL, TRUE) . $queryString;

		/**
		 * Get entity data from id.
		 */
		$entityModel = Default_Model_Entity::getModelByID($userId);

		if(!empty($entityModel->edit_request_data)){
			$this->view->entityModel = $entityModel;
			$this->view->backBtnUrl = $backUrl;
			$this->view->queryString = $queryString . 'id=' . $userId . '&';

			//do updates as per supervisor needs
			if ($this->_request->getParam('do')) {
				$do = $this->_request->getParam('do');
				$request = explode('-',$do)[0];
				$request_data = unserialize($entityModel->edit_request_data);

				try {
					if ($request == 'update') {
						if (($do == 'update-all' && isset($request_data['email'])) || ($do == 'update-email')) {
							//check if email-id exists in db?
							$checkEntityEmail = Default_Model_Entity::createQuery()
								->addWhere('login = ?', array($request_data['email']))
								->orWhere('email = ?', array($request_data['email']))
								->limit(1)
								->execute()
								->getFirst();

							if ($checkEntityEmail && $checkEntityEmail->count() > 0 && $checkEntityEmail->id != $userId) {
								throw new Exception(str_replace('%value%', $request_data['email'], L8M_Translate::string('A record matching \'%value%\' was found')));
							}
						}

						if ($do == 'update-all') {
							$entityModel->merge($request_data);
							if (isset($request_data['email'])) {
								$entityModel->login = $request_data['email'];
							}

							//reset request data
							$entityModel->edit_request_data = NULL;

							//save the changed data values
							$entityModel->save();

							//send email to inform user that request is accepted.
							PRJ_Email::send('request_accepted', $entityModel, array());

							//return to index page after update
							$this->_redirect($backUrl);
						} else {
							$do = explode('-', $do);
							$updateField = $do[1];

							$entityModel->$updateField = $request_data[$updateField];

							//update login also, if supervisor update 'email' field.
							if ($updateField == 'email') {
								$entityModel->login = $request_data['email'];
							}

							//remove updated key from request data
							unset($request_data[$updateField]);
							$entityModel->edit_request_data = serialize($request_data);

							//save the changed data values
							$entityModel->save();

							//send email to inform user that request is accepted.
							PRJ_Email::send('request_accepted', $entityModel, array());
						}
					} else
						if ($request == 'decline') {
							if ($do == 'decline-all') {
								//reset request data
								$entityModel->edit_request_data = NULL;

								//save the changed data values
								$entityModel->save();

								//send email to inform user that request is rejected.
								PRJ_Email::send('request_rejected', $entityModel, array());

								//return to index page after update
								$this->_redirect($backUrl);
							} else {
								$do = explode('-', $do);
								$updateField = $do[1];

								//remove updated key from request data
								unset($request_data[$updateField]);
								$entityModel->edit_request_data = serialize($request_data);

								//save the changed data values
								$entityModel->save();

								//send email to inform user that request is rejected.
								PRJ_Email::send('request_rejected', $entityModel, array());
							}
						} else {
						}
				} catch (Exception $e) {
					$this->view->error = $e->getMessage();
				}
			}
		} else {
			$this->_redirect($backUrl);
		}
	}

	/**
	 * display all User list and sent email to selected users.
	 *
	 * @return void
	 */

	public function sendEmailAction ()
	{
		$usersId = !empty($this->_request->getParam('ids')) ? $this->_request->getParam('ids') : array($this->_request->getParam('id'));

		$entityData = Doctrine_Query::create()
		 	->from('Default_Model_Entity')
		 	->whereIn('id', $usersId)
		 	->execute()
			 ;
		/**
		 * prepare back button
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

		$this->view->backBtnUrl = $this->view->url(array('action'=>'list','controller'=>'user','module'=>'admin') , NULL, TRUE) . $paramValues;
		$this->view->entityCollection = $entityData;
		if ($this->_request->getParam('do')) {
			$postData = $this->_request->getPost();
			if(isset($postData['entity']) && !empty(count($postData['entity']))){

				foreach ($postData['entity'] as $entityId) {

					//get entity model from entity_id
					$entityModel = Default_Model_Entity::getModelByID($entityId);

					//generate random password.
					$newPassword = L8M_Library::generatePassword(12);
					$entityModel->password = L8M_Library::generateDBPasswordHash($newPassword);
					$entityModel->save();
					if($postData['selectTemplate'] == "newRegistration")
					{
						//send login data via email.
						$dynamicVars = array(
							'USER_THANKS' => $this->view->translate()->getTranslator()->translate("Thanks for your registration. These are your login credentials.",'de',$entityModel->spoken_language),
							'USER_EMAIL' => $entityModel->login,
							'PASSWORD' => $newPassword,
						);
					}
					if($postData['selectTemplate'] == "newCredentials")
					{
						//send login data  via email.
						$dynamicVars = array(
							'USER_THANKS' => $this->view->translate()->getTranslator()->translate("These are your new login credentials.",'de',$entityModel->spoken_language),
							'USER_EMAIL' => $entityModel->login,
							'PASSWORD' => $newPassword,
						);
					}

					PRJ_Email::send('user_credentials', $entityModel,$dynamicVars);

				}
				$backUrl = $this->view->url(array('action'=>'list','controller'=>'user','module'=>'admin'), NULL, TRUE) . $paramValues;
				$this->_redirect($backUrl);
			}
		}
	}
}
