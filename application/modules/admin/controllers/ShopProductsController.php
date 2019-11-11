<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/ShopProductsController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ShopProductsController.php 299 2015-03-31 10:59:38Z nm $
 */

/**
 *
 *
 * Admin_ShopProductsController
 *
 *
 */
class Admin_ShopProductsController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Product';
	private $_modelListShort = 'p';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Shop Products';

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
	 * Initializes Admin_ShopProductsController.
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
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
			->showListColumn('product_number', 'Product number', 85, FALSE, FALSE)
			->showListColumnAsAlignRight('product_number')
			->showListColumn('price', 'Price', 50, FALSE, FALSE)
			->showListColumn('hidden', 'Hidden', 45, FALSE, FALSE)
			->showListColumn('sold_out', 'Sold out', 45, FALSE, FALSE)
		;

		if (L8M_Config::getOption('shop.product.new') == TRUE) {
			$this->_modelList->showListColumn('new', 'New', 45, FALSE, FALSE);
		}

		if (L8M_Config::getOption('shop.product.tip') == TRUE) {
			$this->_modelList->showListColumn('tip', 'Tip', 45, FALSE, FALSE);
		}

		if (L8M_Config::getOption('shop.product.top_product') == TRUE) {
			$this->_modelList->showListColumn('top_product', 'Top product', 45, FALSE, FALSE);
		}

		if (L8M_Config::getOption('shop.unit') == FALSE) {
			$this->_modelList->hideListColumn('product_unit_id', FALSE);
		}

		if (L8M_Config::getOption('shop.producer') == FALSE) {
			$this->_modelList->hideListColumn('producer_id', FALSE);
		}

		if (L8M_Config::getOption('shop.refund') == FALSE) {
			$this->_modelList->hideListColumn('refund_id', FALSE);
		}

		$this->_modelList
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$ignoredColumns = array();

		if (L8M_Config::getOption('shop.product.subtitle') == FALSE) {
			$ignoredColumns[] = 'subtitle';
		}

		if (L8M_Config::getOption('shop.unit') == FALSE) {
			$ignoredColumns[] = 'product_unit_id';
		}

		if (L8M_Config::getOption('shop.producer') == FALSE) {
			$ignoredColumns[] = 'producer_id';
		}

		if (L8M_Config::getOption('shop.refund') == FALSE) {
			$ignoredColumns[] = 'refund_id';
		}

		if (L8M_Config::getOption('shop.product.tip') == FALSE) {
			$ignoredColumns[] = 'tip';
		}

		if (L8M_Config::getOption('shop.product.new') == FALSE) {
			$ignoredColumns[] = 'new';
		}

		if (L8M_Config::getOption('shop.product.top_product') == FALSE) {
			$ignoredColumns[] = 'top_product';
		}

		$m2nRelations = array();
		if ($this->_request->getActionName() == 'edit') {
			$productsOptionsModel = Doctrine_Query::create()
				->from('Default_Model_ProductsOptions m')
				->addWhere('m.product_id = ? ', array($this->_request->getParam('id', NULL, FALSE)))
				->limit(1)
				->execute()
				->getFirst()
			;
			if ($productsOptionsModel) {
				$m2nRelations = array(
					'ProductsOptions',
				);
			}
		}

		$this->_modelListConfig = array(
			'order'=>array(
				'name',
				'title',
				'subtitle',
				'hidden',
				'new',
				'sold_out',
				'product_status_id',
				'tip',
				'top_product',
				'product_number',
				'producer_id',
				'relation_m2n_productm2nproductgroup',
				'media_id',
				'media_image_id',
				'relation_m2n_productm2nmediaimage',
				'description',
				'product_unit_id',
				'unit_count',
				'relation_m2n_productsoptions',
				'relation_m2n_productm2nproductoptionmodel',
				'shipping_cost_factor',
				'price',
				'refund_id',
				'taxes_id',
				'meta_title',
				'meta_keywords',
				'meta_description',
				'relation_m2n_productm2ntag',
				'need_media_upload',
				'in_stock',
				'no_stock_change',
			),
			'addIgnoredColumns'=>$ignoredColumns,
			'addIgnoredM2nRelations'=>array(
			),
			'ignoreColumnRelation'=>array(
			),
			'ignoreColumnInMultiRelation'=>array(
			),
			'relationM2nValuesDefinition'=>array(
				'ProductM2nProductOptionModel'=>array(
					'columnLabels'=>array(
						'price'=>'Price',
						'disabled'=>'Disabled',
					),
					'allowMultipleRows'=>TRUE,
				),
			),
			'mediaDirectory'=>array(
				'media_id'=>'/medias/products',
				'media_image_id'=>'/images/products',
			),
			'mediaRole'=>array(
				'media_id'=>'guest',
				'media_image_id'=>'guest',
			),
			'columnLabels'=>array(
				'unit_count'=>'Menge der Einheiten',
				'no_stock_change'=>'Do not change stock on placed order.',
				'product_media_image_id'=>'Bild',
				'product_media_id'=>'Datei',
				'relation_m2n_productm2nmediaimage'=>'Produkt-Bilder',
				'relation_m2n_productsoptions'=>'Produkt-Optionen',
				'relation_m2n_productm2nproductoptionmodel'=>'Produkt-Optionen',
				'relation_m2n_productm2nproductgroup'=>'Productgroups',
				'relation_m2n_productm2ntag'=>'Produkt-Tag',
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'name'=>'text',
				'meta_title'=>'text',
				'meta_keywords'=>'text',
				'title'=>'text',
				'subtitle'=>'text',
			),
			'addStaticFormElements'=>array(
			),
			'M2NRelations'=>$m2nRelations,
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
}