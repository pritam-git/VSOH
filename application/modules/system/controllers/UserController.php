<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/UserController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: UserController.php 201 2014-10-14 14:19:03Z nm $
 */

/**
 *
 *
 * System_UserController
 *
 *
 */
class System_UserController extends L8M_Controller_Action
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
	 * Initializes System_UserController.
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

//		$this->_modelList
//			->setButton('Update', array('action'=>'update', 'controller'=>'action', 'module'=>'system'), 'update', FALSE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
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
				'mobile',
				'www',
				'media_image_id',
				'company',
				'street',
				'street_number',
				'address_line_1',
				'address_line_2',
				'zip',
				'city',
				'country_id',
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
				'company_name',
				'branch',
				'contact_position',
				'contact_name',
				'commercial_register_id',
				'vat_tax_id',
				'managing_director',
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
				'sales_2017',
				'annual_contribution_status',
				'annual_contribution_volume',
				'annual_contribution_sport',
				'annual_contribution_opel',
				'annual_contribution_chevrolet',
				'annual_contribution_us',
				'annual_contribution_total',
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
				'PRJ_Entity_Create_AfterSave',
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