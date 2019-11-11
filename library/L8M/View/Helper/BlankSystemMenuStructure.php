<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BlankSystemMenuStructure.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemMenuStructure.php 418 2015-09-17 12:26:49Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BlankSystemMenuStructure
 *
 *
 */
class L8M_View_Helper_BlankSystemMenuStructure extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private static $_generatedMenuStructure = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Return Menu Structure
	 *
	 * @return array
	 */
	public function blankSystemMenuStructure()
	{
		$returnValue = array();
		if (self::$_generatedMenuStructure === NULL &&
			Zend_Auth::getInstance()->hasIdentity()) {

			if ($this->view->layout()->isException) {
				$rememberOldCalledForModuleName = $this->view->layout()->rememberOldCalledForModuleName;
				$rememberOldCalledForControllerName = $this->view->layout()->rememberOldCalledForControllerName;

				if (count($rememberOldCalledForModuleName) > 0) {
					if (count($rememberOldCalledForControllerName) > 0) {
						$activeController = array_shift($rememberOldCalledForControllerName);
					} else {
						$activeController = $this->view->layout()->rememberCalledForControllerName;
					}
					$moduleName = array_shift($rememberOldCalledForModuleName);
				} else {
					$moduleName = $this->view->layout()->rememberCalledForModuleName;
					$activeController = $this->view->layout()->rememberCalledForControllerName;
				}

				if ($activeController == 'media' &&
					Zend_Auth::getInstance()->hasIdentity()) {

					$moduleName = L8M_Acl_Resource::getModuleNameFromResource(Zend_Auth::getInstance()->getIdentity()->Role->default_action_resource);
				}
			} else {
				if ($this->view->layout()->calledForControllerName != 'media') {
					$moduleName = $this->view->layout()->calledForModuleName;
				} else {
					if (Zend_Auth::getInstance()->hasIdentity()) {
						$moduleName = L8M_Acl_Resource::getModuleNameFromResource(Zend_Auth::getInstance()->getIdentity()->Role->default_action_resource);
					}
				}
			}

			$filter = new Zend_Filter();
			$filter
				->addFilter(new Zend_Filter_Word_DashToCamelCase())
			;
			$classModuleName = $filter->filter($moduleName);

			if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Helper' . DIRECTORY_SEPARATOR . 'BlankSystemMenuStructure' . $classModuleName . '.php') &&
				class_exists('PRJ_View_Helper_BlankSystemMenuStructure' . $classModuleName)) {

				$structureViewhelper = 'blankSystemMenuStructure' . $classModuleName;
				self::$_generatedMenuStructure = $this->view->$structureViewhelper();
			} else {
				$methodName = '_generate' . $classModuleName . 'MenuStructure';
				if (method_exists($this, $methodName)) {
					self::$_generatedMenuStructure = $this->$methodName();
				} else {
					self::$_generatedMenuStructure = array();
				}
			}
		}

		if (is_array(self::$_generatedMenuStructure)) {
			$returnValue = self::$_generatedMenuStructure;
		}

		return $returnValue;
	}

	/**
	 * Generate Menu Structure System
	 *
	 * @return array
	 */
	private function _generateSystemMenuStructure()
	{
		/**
		 * return value
		 */
		$returnValue = array();
		$rememberMenu = array();
		$menuCounter = 0;

		/**
		 * controllers
		 */
		$standardControllerNames = array(
			'Action',
			'AdminBoxes',
			'AdminBoxesAction',
			'Cache',
			'Configuration',
			'Controller',
			'Css',
			'Media',
			'Model',
			'Module',
			'Navigation',
			'Role',
			'Plugins',
			'User',
			'ServerUpdate',
			'Session',
			'Setup',
			'Translator',
			'TranslatorModelColumn',
			'TranslatorModelList',
			'TranslatorResource',
			'TranslatorParam',
			'User',
		);
		$unusedControllerNames = array(
			'AutoComplete',
			'MarkForEditor',
			'DashBoard',
			'Index',
			'Login',
		);
		$specialStandardControllerNames = array(
		);
		$markUsedAdminController = array();
		$standardControllerPrefixes = array(
		);

		/**
		 * view
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		/**
		 * merge array
		 */
		$standardControllerNames = array_merge($standardControllerNames, $unusedControllerNames);

		$returnValue[$menuCounter] = array(
			'short'=>'config',
			'name'=>$viewFromMVC->translate('Configuration'),
			'title'=>$viewFromMVC->translate('Setup and Configuration'),
			'description'=>$viewFromMVC->translate('Using these options may lead to malfunctions. So make sure you really know what you are doing!'),
			'links'=>array(
				array(
					'css'=>'cog',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'setup'), NULL, TRUE),
					'controller'=>'setup',
					'name'=>$viewFromMVC->translate('Set Up this application'),
					'showOnlyInEnvironment'=>L8M_Environment::ENVIRONMENT_DEVELOPMENT,
				),
				array(
					'css'=>'script',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'configuration'), NULL, TRUE),
					'controller'=>'configuration',
					'name'=>$viewFromMVC->translate('Configure this application'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-go',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'admin-boxes-action'), NULL, TRUE),
					'controller'=>'admin-boxes-action',
					'name'=>$viewFromMVC->translate('Backend - AdminBoxesAction'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'admin-boxes'), NULL, TRUE),
					'controller'=>'admin-boxes',
					'name'=>$viewFromMVC->translate('Backend - AdminBoxes'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['config'] = $menuCounter++;

		$returnValue[$menuCounter] = array(
			'short'=>'temporary',
			'name'=>$viewFromMVC->translate('Temporary Datas'),
			'title'=>$viewFromMVC->translate('Manage Temporary Datas'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'lightning',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'cache', 'action'=>'clear'), NULL, TRUE),
					'controller'=>'cache',
					'name'=>$viewFromMVC->translate('Clear all Caches'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'key',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'session', 'action'=>'clear'), NULL, TRUE),
					'controller'=>'session',
					'name'=>$viewFromMVC->translate('Clear all Sessions'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['temporary'] = $menuCounter++;

		$returnValue[$menuCounter] = array(
			'short'=>'user',
			'name'=>$viewFromMVC->translate('Roles & Users'),
			'title'=>$viewFromMVC->translate('Manage Roles and Users'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'group',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'role'), NULL, TRUE),
					'controller'=>'role',
					'name'=>$viewFromMVC->translate('Manage roles'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'folder-user',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'user'), NULL, TRUE),
					'controller'=>'user',
					'name'=>$viewFromMVC->translate('Manage users'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['user'] = $menuCounter++;

		$returnValue[$menuCounter] = array(
			'short'=>'translations',
			'name'=>$viewFromMVC->translate('Translations'),
			'title'=>$viewFromMVC->translate('Manage Translations'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'comment-edit',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'translator'), NULL, TRUE),
					'controller'=>'translator',
					'name'=>$viewFromMVC->translate('Translations'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'comment-edit',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'translator-model-list'), NULL, TRUE),
					'controller'=>'translator-model-list',
					'name'=>'ModelList-' . $viewFromMVC->translate('Translations'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'comment-edit',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'translator-model-column'), NULL, TRUE),
					'controller'=>'translator-model-column',
					'name'=>'ModelColumn-' . $viewFromMVC->translate('Translations'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'comment-edit',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'translator-resource'), NULL, TRUE),
					'controller'=>'translator-resource',
					'name'=>$viewFromMVC->translate('Translate Resource'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'comment-edit',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'translator-param'), NULL, TRUE),
					'controller'=>'translator-param',
					'name'=>$viewFromMVC->translate('Translate Param'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['translations'] = $menuCounter++;

		$returnValue[$menuCounter] = array(
			'short'=>'media',
			'name'=>$viewFromMVC->translate('Medias & CSS'),
			'title'=>$viewFromMVC->translate('Manage Media and CSS'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'images',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'media'), NULL, TRUE),
					'controller'=>'media',
					'name'=>$viewFromMVC->translate('Manage media'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'css',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'css'), NULL, TRUE),
					'controller'=>'css',
					'name'=>$viewFromMVC->translate('Generate iconized list CSS'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['media'] = $menuCounter++;

		$returnValue[$menuCounter] = array(
			'short'=>'resources',
			'name'=>$viewFromMVC->translate('Resources & Navigation'),
			'title'=>$viewFromMVC->translate('Manage Resources & Navigation'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'brick',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'model'), NULL, TRUE),
					'controller'=>'model',
					'name'=>$viewFromMVC->translate('Manage models'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'bricks',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'module'), NULL, TRUE),
					'controller'=>'module',
					'name'=>$viewFromMVC->translate('Manage modules'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-cascade',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'controller'), NULL, TRUE),
					'controller'=>'controller',
					'name'=>$viewFromMVC->translate('Manage controllers'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'xhtml-go',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'action'), NULL, TRUE),
					'controller'=>'action',
					'name'=>$viewFromMVC->translate('Manage actions'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'plugin',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'plugins'), NULL, TRUE),
					'controller'=>'plugins',
					'name'=>$viewFromMVC->translate('Manage Plugins'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'chart-organisation',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'navigation'), NULL, TRUE),
					'controller'=>'navigation',
					'name'=>$viewFromMVC->translate('Manage Navigation'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'server-lightning',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'server-update'), NULL, TRUE),
					'controller'=>'server-update',
					'name'=>$viewFromMVC->translate('Update Server'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['resources'] = $menuCounter++;

		/**
		 * administrate your dynamic content
		 *
		 * walk through controller-directory
		 */
		$returnValue[$menuCounter] = array(
			'short'=>'dynamic-content',
			'name'=>$viewFromMVC->translate('Dynamic Content'),
			'title'=>$viewFromMVC->translate('Configurate your Dynamic Content'),
			'description'=>'',
			'links'=>array(),
		);
		$rememberMenu['dynamic-content'] = $menuCounter++;

		$walkTroughReturnArray = $this->_walkThroughModuleControllers($standardControllerNames, $markUsedAdminController, $standardControllerPrefixes, $specialStandardControllerNames, $returnValue, $rememberMenu, $viewFromMVC, 'system');

		$returnValue = $walkTroughReturnArray['returnValue'];
		$rememberMenu = $walkTroughReturnArray['rememberMenu'];
		$standardControllerNames = $walkTroughReturnArray['standardControllerNames'];

		return $returnValue;

	}


	/**
	 * Generate Menu Structure Admin
	 *
	 * @return array
	 */
	private function _generateAdminMenuStructure()
	{
		/**
		 * return value
		 */
		$returnValue = array();
		$rememberMenu = array();
		$menuCounter = 0;

		/**
		 * controllers
		 */
		$standardControllerNames = array(
			'BackgroundImage',
			'Content',
			'ContentBox',
			'ContentSiteImages',
			'EmailTemplate',
			'EmailTemplatePart',
			'EmailTemplateReplacement',
			'ImageConfig',
			'InfoPage',
			'MediaConfig',
			'Newsletter',
			'NewsletterUser',
			'NewsletterSubscriberType',
			'MetaConfiguration',
			'SiteConfig',
			'Sitemap',
			'TinymceTemplate',
			'Translator',
			'TranslatorResource',
			'TranslatorParam',
			'User',
		);
		$unusedControllerNames = array(
			'Index',
			'Login',
			'Old',
		);
		$specialStandardControllerNames = array(
			'Blog',
			'Faq',
			'News',
			'Team',
		);
		$markUsedAdminController = array();
		$standardControllerPrefixes = array(
			'Shop',
			'Spo',
		);

		/**
		 * view
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		/**
		 * merge array
		 */
		$standardControllerNames = array_merge($standardControllerNames, $unusedControllerNames);

		$returnValue[$menuCounter] = array(
			'short'=>'general',
			'name'=>$viewFromMVC->translate('General'),
			'title'=>$viewFromMVC->translate('Configurate your Application'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'vcard-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'content'), NULL, TRUE),
					'controller'=>'content',
					'name'=>$viewFromMVC->translate('Content-Pages'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'info-page'), NULL, TRUE),
					'controller'=>'info-page',
					'name'=>$viewFromMVC->translate('InfoPage'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-view-list',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'meta-configuration'), NULL, TRUE),
					'controller'=>'meta-configuration',
					'name'=>$viewFromMVC->translate('MetaConfiguration'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'images',
					'link'=>$this->view->url(array('module'=>'system', 'controller'=>'media'), NULL, TRUE),
					'controller'=>'media',
					'name'=>$viewFromMVC->translate('Manage Media'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['general'] = $menuCounter++;

		/**
		 * do we have to administrate sitemap?
		 */
		if (L8M_Config::getOption('google.enabled')) {
			$returnValue[$rememberMenu['general']]['links'][] = array(
				'css'=>'sitemap-color',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'sitemap'), NULL, TRUE),
				'controller'=>'sitemap',
				'name'=>$viewFromMVC->translate('Create Sitemap'),
			);
		}

		/**
		 * do we have to administrate users?
		 */
		if (L8M_Config::getOption('authentication.user_management.enabled')) {
			$returnValue[$rememberMenu['general']]['links'][] = array(
				'css'=>'folder-user',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'user'), NULL, TRUE),
				'controller'=>'user',
				'name'=>$viewFromMVC->translate('Users'),
				'showOnlyInEnvironment'=>NULL,
			);
		}

		/**
		 * do we have something to translate
		 */
		if (count(L8M_Locale::getSupported()) > 0) {
			$returnValue[$menuCounter] = array(
				'short'=>'translations',
				'name'=>$viewFromMVC->translate('Translations'),
				'title'=>$viewFromMVC->translate('Manage Translations'),
				'description'=>'',
				'links'=>array(
					array(
						'css'=>'comment-edit',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'translator'), NULL, TRUE),
						'controller'=>'translator',
						'name'=>$viewFromMVC->translate('Translations'),
						'showOnlyInEnvironment'=>NULL,
					),
					array(
						'css'=>'comment-edit',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'translator-resource'), NULL, TRUE),
						'controller'=>'translator-resource',
						'name'=>$viewFromMVC->translate('Translate Resource'),
						'showOnlyInEnvironment'=>NULL,
					),
					array(
						'css'=>'comment-edit',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'translator-param'), NULL, TRUE),
						'controller'=>'translator-param',
						'name'=>$viewFromMVC->translate('Translate Param'),
						'showOnlyInEnvironment'=>NULL,
					),
				),
			);
			$rememberMenu['translations'] = $menuCounter++;
		}

		/**
		 * get backend admin boxes from db
		 */
		if (L8M_Doctrine_Database::databaseExists() &&
			L8M_Doctrine::isEnabled() &&
			L8M_Doctrine_Table::tableExists('backend_admin_boxes')) {

			$filterControllerReverse = new Zend_Filter_Word_DashToCamelCase();
			$boxesCollection = Doctrine_Query::create()
				->from('Default_Model_BackendAdminBoxes m')
				->orderBy('m.position ASC')
				->execute()
			;

			foreach ($boxesCollection as $boxesModel) {
				$returnValue[$menuCounter] = array(
					'short'=>$boxesModel->short,
					'name'=>$boxesModel->name,
					'title'=>$boxesModel->title,
					'description'=>$boxesModel->description,
					'links'=>array(),
				);
				$rememberMenu[$boxesModel->short] = $menuCounter++;

				$boxActionsCollection = Doctrine_Query::create()
					->from('Default_Model_BackendAdminBoxesActionM2nBackendAdminBoxes m')
					->addWhere('m.backend_admin_boxes_id = ? ', array($boxesModel->id))
					->orderBy('m.position ASC')
					->execute()
				;
				if ($boxActionsCollection->count() > 0) {
					foreach ($boxActionsCollection as $boxActionsModel) {
						$linkAddOn = NULL;
						$backendAdminBoxesActionModel = $boxActionsModel->BackendAdminBoxesAction;
						if ($backendAdminBoxesActionModel->model_name_id) {
							$boxAddonModelName = $backendAdminBoxesActionModel->ModelName->name;
							$boxAddonModel = new $boxAddonModelName();
							$boxAddonModelColumnDefinitions = $boxAddonModel->getTable()->getColumns();
							if (array_key_exists('new', $boxAddonModelColumnDefinitions)) {
								$boxActionsAddOnQuerry = Doctrine_Query::create()
									->from($boxAddonModelName . ' m')
									->select('COUNT(m.id)')
								;
								/**
								 * count reverse?
								 */
								if (isset($backendAdminBoxesActionModel['count_not_new']) &&
									$backendAdminBoxesActionModel['count_not_new']) {

									$boxActionsAddOnQuerry = $boxActionsAddOnQuerry
										->addWhere('m.new = ? OR m.new IS NULL', array(FALSE))
									;
								} else {
									$boxActionsAddOnQuerry = $boxActionsAddOnQuerry
										->addWhere('m.new = ? ', array(TRUE))
									;
								}

								$boxActionsAddOnArray = $boxActionsAddOnQuerry
									->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
									->execute()
								;
								if (is_array($boxActionsAddOnArray) &&
									count($boxActionsAddOnArray) > 0 &&
									isset($boxActionsAddOnArray[0]) &&
									is_array($boxActionsAddOnArray[0]) &&
									isset($boxActionsAddOnArray[0]['m_COUNT']) &&
									$boxActionsAddOnArray[0]['m_COUNT'] > 0) {

									$linkAddOn = ' (' . $boxActionsAddOnArray[0]['m_COUNT'] . ')';
								}
							}

						}
						$linkAction = $backendAdminBoxesActionModel->Action->name;
						$linkController = $backendAdminBoxesActionModel->Action->Controller->name;
						$linkModule = $backendAdminBoxesActionModel->Action->Controller->Module->name;
						if ($linkModule == 'admin') {
							$markUsedAdminController[] = $filterControllerReverse->filter($linkController);
						}

						$returnValue[$rememberMenu[$boxesModel->short]]['links'][] = array(
							'css'=>'application-form-edit',
							'link'=>$viewFromMVC->url(array('module'=>$linkModule, 'controller'=>$linkController, 'action'=>$linkAction), NULL, TRUE),
							'controller'=>$linkController,
							'name'=>$backendAdminBoxesActionModel->title . $linkAddOn,
							'showOnlyInEnvironment'=>NULL,
						);
					}
				}
			}
		}

		/**
		 * administrate your shop
		 */
		/**
		 * do we have a shop?
		 */
		if (L8M_Config::getOption('shop.admin.box.enabled')) {

			/**
			 * Shop General
			 */
			$returnValue[$menuCounter] = array(
				'short'=>'shop',
				'name'=>$viewFromMVC->translate('Shop'),
				'title'=>$viewFromMVC->translate('Configurate your Shop'),
				'description'=>'',
				'links'=>array(
					array(
						'css'=>'application-form-edit',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-products'), NULL, TRUE),
						'controller'=>'shop-products',
						'name'=>$viewFromMVC->translate('Products'),
						'showOnlyInEnvironment'=>NULL,
					),
				),
			);
			$rememberMenu['shop'] = $menuCounter++;

			if (L8M_Config::getOption('shop.temporary_offer') == TRUE) {
				$returnValue[$rememberMenu['shop']]['links'][] = array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-temporary-offer'), NULL, TRUE),
					'controller'=>'shop-temporary-offer',
					'name'=>$viewFromMVC->translate('TemporaryOffer'),
					'showOnlyInEnvironment'=>NULL,
				);
			}

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-groups'), NULL, TRUE),
				'controller'=>'shop-groups',
				'name'=>$viewFromMVC->translate('Productgroups'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-taxes'), NULL, TRUE),
				'controller'=>'shop-taxes',
				'name'=>$viewFromMVC->translate('Taxes'),
				'showOnlyInEnvironment'=>NULL,
			);

			/**
			 * should be killed in some versions
			 * [x] ProductOptions
			 * [x] ProductOptionItmes
			 */
//			$returnValue[$rememberMenu['shop']]['links'][] = array(
//				'css'=>'application-form-edit',
//				'link'=>'/admin/shop-product-options',
//				'controller'=>'shop-product-options',
//				'name'=>$viewFromMVC->translate('ProductOptions'),
//				'showOnlyInEnvironment'=>NULL,
//			);
//
//			$returnValue[$rememberMenu['shop']]['links'][] = array(
//				'css'=>'application-form-edit',
//				'link'=>'/admin/shop-product-option-items',
//				'controller'=>'shop-product-option-items',
//				'name'=>$viewFromMVC->translate('ProductOptionItems'),
//				'showOnlyInEnvironment'=>NULL,
//			);

			if (L8M_Config::getOption('shop.unit') == TRUE) {
				$returnValue[$rememberMenu['shop']]['links'][] = array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-product-unit'), NULL, TRUE),
					'controller'=>'shop-product-unit',
					'name'=>$viewFromMVC->translate('ProductUnit'),
					'showOnlyInEnvironment'=>NULL,
				);
			}

			if (L8M_Config::getOption('shop.producer') == TRUE) {
				$returnValue[$rememberMenu['shop']]['links'][] = array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-producer'), NULL, TRUE),
					'controller'=>'shop-producer',
					'name'=>$viewFromMVC->translate('Producer'),
					'showOnlyInEnvironment'=>NULL,
				);
			}

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-product-information'), NULL, TRUE),
				'controller'=>'shop-product-information',
				'name'=>$viewFromMVC->translate('ShopProductInformation'),
				'showOnlyInEnvironment'=>NULL,
			);

			if (L8M_Config::getOption('shop.refund') == TRUE) {
				$returnValue[$rememberMenu['shop']]['links'][] = array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-refund'), NULL, TRUE),
					'controller'=>'shop-refund',
					'name'=>$viewFromMVC->translate('Refund'),
					'showOnlyInEnvironment'=>NULL,
				);
			}

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-tag'), NULL, TRUE),
				'controller'=>'shop-tag',
				'name'=>$viewFromMVC->translate('ShopTag'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-product-status'), NULL, TRUE),
				'controller'=>'shop-product-status',
				'name'=>$viewFromMVC->translate('ProductStatus'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-holiday'), NULL, TRUE),
				'controller'=>'shop-holiday',
				'name'=>$viewFromMVC->translate('ShopHoliday'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-payment-services'), NULL, TRUE),
				'controller'=>'shop-payment-services',
				'name'=>$viewFromMVC->translate('PaymentServices'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-shipping-cost'), NULL, TRUE),
				'controller'=>'shop-shipping-cost',
				'name'=>$viewFromMVC->translate('ShippingCost'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-shipping-country'), NULL, TRUE),
				'controller'=>'shop-shipping-country',
				'name'=>$viewFromMVC->translate('ShippingCountry'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-coupon'), NULL, TRUE),
				'controller'=>'shop-coupon',
				'name'=>$viewFromMVC->translate('Coupon'),
				'showOnlyInEnvironment'=>NULL,
			);

			$returnValue[$rememberMenu['shop']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-order'), NULL, TRUE),
				'controller'=>'shop-order',
				'name'=>$viewFromMVC->translate('ShopOrder'),
				'showOnlyInEnvironment'=>NULL,
			);

			/**
			 * Shop Product Options
			 */
			$returnValue[$menuCounter] = array(
				'short'=>'shop',
				'name'=>$viewFromMVC->translate('ProductOptions'),
				'title'=>$viewFromMVC->translate('Configurate your Product Options'),
				'description'=>'',
				'links'=>array(),
			);
			$rememberMenu['spo'] = $menuCounter++;


			$returnValue[$rememberMenu['spo']]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'shop-manage-product-options'), NULL, TRUE),
				'controller'=>'shop-manage-product-options',
				'name'=>$viewFromMVC->translate('Manage ProductOptions'),
				'showOnlyInEnvironment'=>NULL,
			);

			$productOptionsCollection = Doctrine_Query::create()
				->from('Default_Model_ProductOptionModel m')
				->orderBy('m.position ASC')
				->execute()
			;

			foreach ($productOptionsCollection as $productOptionsModel) {
				$returnValue[$rememberMenu['spo']]['links'][] = array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'spo-only', 'modelListName'=>$productOptionsModel->ModelName->name), NULL, TRUE),
					'controller'=>'spo-only',
					'name'=>$productOptionsModel->title,
					'showOnlyInEnvironment'=>NULL,
					'hasToHaveParam'=>array(
						'modelListName'=>$productOptionsModel->ModelName->name,
					),
				);
			}

		}

		/**
		 * administrate config
		 */
		$returnValue[$menuCounter] = array(
			'short'=>'config',
			'name'=>$viewFromMVC->translate('Basic Configuration'),
			'title'=>$viewFromMVC->translate('Basic Configuration'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'site-config'), NULL, TRUE),
					'controller'=>'site-config',
					'name'=>$viewFromMVC->translate('SiteConfig'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'image-config'), NULL, TRUE),
					'controller'=>'image-config',
					'name'=>$viewFromMVC->translate('ImageConfig'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'media-config'), NULL, TRUE),
					'controller'=>'media-config',
					'name'=>$viewFromMVC->translate('MediaConfig'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'application-form-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'tinymce-template'), NULL, TRUE),
					'controller'=>'tinymce-template',
					'name'=>$viewFromMVC->translate('TinymceTemplate'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);

		if (L8M_Config::getOption('l8m.backEnd.BackgroundImage.enabled')) {
			$returnValue[$menuCounter]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'background-image'), NULL, TRUE),
				'controller'=>'background-image',
				'name'=>$viewFromMVC->translate('BackgroundImage'),
				'showOnlyInEnvironment'=>NULL,
			);
		}
		if (L8M_Config::getOption('l8m.backEnd.ContentBox.enabled')) {
			$returnValue[$menuCounter]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'content-box'), NULL, TRUE),
				'controller'=>'content-box',
				'name'=>$viewFromMVC->translate('ContentBox'),
				'showOnlyInEnvironment'=>NULL,
			);
		}
		if (L8M_Config::getOption('l8m.backEnd.ContentSiteImages.enabled')) {
			$returnValue[$menuCounter]['links'][] = array(
				'css'=>'application-form-edit',
				'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'content-site-images'), NULL, TRUE),
				'controller'=>'content-site-images',
				'name'=>$viewFromMVC->translate('ContentSiteImages'),
				'showOnlyInEnvironment'=>NULL,
			);
		}

		$rememberMenu['basic-config'] = $menuCounter++;

		/**
		 * email template
		 */
		$returnValue[$menuCounter] = array(
			'short'=>'email-template',
			'name'=>$viewFromMVC->translate('EmailTemplate'),
			'title'=>$viewFromMVC->translate('Configurate EmailTemplate'),
			'description'=>'',
			'links'=>array(
				array(
					'css'=>'layout-header',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'email-template'), NULL, TRUE),
					'controller'=>'email-template',
					'name'=>$viewFromMVC->translate('EmailTemplate'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'layout-content',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'email-template-part'), NULL, TRUE),
					'controller'=>'email-template-part',
					'name'=>$viewFromMVC->translate('EmailTemplatePart'),
					'showOnlyInEnvironment'=>NULL,
				),
				array(
					'css'=>'layout-edit',
					'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'email-template-replacement'), NULL, TRUE),
					'controller'=>'email-template-replacement',
					'name'=>$viewFromMVC->translate('EmailTemplateReplacement'),
					'showOnlyInEnvironment'=>NULL,
				),
			),
		);
		$rememberMenu['email-template'] = $menuCounter++;

		/**
		 * administrate your dynamic content
		 *
		 * walk through controller-directory
		 */
		$returnValue[$menuCounter] = array(
			'short'=>'dynamic-content',
			'name'=>$viewFromMVC->translate('Dynamic Content'),
			'title'=>$viewFromMVC->translate('Configurate your Dynamic Content'),
			'description'=>'',
			'links'=>array(),
		);
		$rememberMenu['dynamic-content'] = $menuCounter++;

		$walkTroughReturnArray = $this->_walkThroughModuleControllers($standardControllerNames, $markUsedAdminController, $standardControllerPrefixes, $specialStandardControllerNames, $returnValue, $rememberMenu, $viewFromMVC, 'admin');

		$returnValue = $walkTroughReturnArray['returnValue'];
		$rememberMenu = $walkTroughReturnArray['rememberMenu'];
		$standardControllerNames = $walkTroughReturnArray['standardControllerNames'];

		/**
		 * administrate newsletter
		 */
		if (L8M_Config::getOption('l8m.newsletter.enabled')) {
			$returnValue[$menuCounter] = array(
				'short'=>'newsletter',
				'name'=>$viewFromMVC->translate('Newsletter'),
				'title'=>$viewFromMVC->translate('Configurate the Newsletter'),
				'description'=>'',
				'links'=>array(
					array(
						'css'=>'report-user',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'newsletter-user'), NULL, TRUE),
						'controller'=>'newsletter-user',
						'name'=>$viewFromMVC->translate('Newsletter Subscriber'),
						'showOnlyInEnvironment'=>NULL,
					),
					array(
						'css'=>'report-key',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'newsletter-subscriber-type'), NULL, TRUE),
						'controller'=>'newsletter-subscriber-type',
						'name'=>$viewFromMVC->translate('NewsletterSubscriberType'),
						'showOnlyInEnvironment'=>NULL,
					),
					array(
						'css'=>'report',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'newsletter'), NULL, TRUE),
						'controller'=>'newsletter',
						'name'=>$viewFromMVC->translate('Newsletter'),
						'showOnlyInEnvironment'=>NULL,
					),
				),
			);
			$rememberMenu['newsletter'] = $menuCounter++;
		}

		return $returnValue;
	}

	private function _walkThroughModuleControllers($standardControllerNames, $markUsedAdminController, $standardControllerPrefixes, $specialStandardControllerNames, $menuBoxes, $rememberMenu, $viewFromMVC, $moduleName)
	{
		$returnValue = array(
			'returnValue'=>$menuBoxes,
			'rememberMenu'=>$rememberMenu,
			'standardControllerNames'=>array(),
		);

		$dynamicContentControllers = array();

		if (count($markUsedAdminController) > 0) {
			$standardControllerNames = array_merge($standardControllerNames, $markUsedAdminController);
		}

		$directoryIterator = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'controllers');
		foreach($directoryIterator as $file) {
			/* @var $file DirectoryIterator */
			if ($file->isFile() &&
				preg_match('/^(.+)Controller\.php$/', $file->getFilename(), $match)) {

				$hasStandardControllerPrefix = FALSE;
				foreach ($standardControllerPrefixes as $standardControllerPrefix) {
					if (substr($match[1], 0, strlen($standardControllerPrefix)) == $standardControllerPrefix) {
						$hasStandardControllerPrefix = TRUE;
					}
				}
				if (!in_array($match[1], $standardControllerNames) &&
					!$hasStandardControllerPrefix) {

					/**
					 * filter
					 */
					$filterController = new Zend_Filter_Word_CamelCaseToDash();
					$dynamicContentControllers[] = array(
						'shortName'=>strtolower($filterController->filter($match[1])),
						'longName'=>$match[1],
					);
				}
			}
		}

		if (count($dynamicContentControllers) > 0) {
			foreach ($dynamicContentControllers as $dynamicContentController) {
				if (in_array($dynamicContentController['longName'], $specialStandardControllerNames)) {

					/**
					 * do we have a Blog?
					 */
					if (L8M_Config::getOption('l8m.blog.enabled') &&
						$dynamicContentController['longName'] == 'Blog') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'feed-edit',
							'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'blog'), NULL, TRUE),
							'controller'=>'blog',
							'name'=>$viewFromMVC->translate('Blog'),
							'showOnlyInEnvironment'=>NULL,
						);
					}

					/**
					 * do we have a FAQ?
					 */
					if (L8M_Config::getOption('l8m.faq.enabled') &&
						$dynamicContentController['longName'] == 'Faq') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'help-edit',
							'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'faq'), NULL, TRUE),
							'controller'=>'faq',
							'name'=>$viewFromMVC->translate('FAQs'),
							'showOnlyInEnvironment'=>NULL,
						);
					}

					/**
					 * do we have News?
					 */
					if (L8M_Config::getOption('l8m.news.enabled') &&
						$dynamicContentController['longName'] == 'News') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'calendar-edit',
							'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'news'), NULL, TRUE),
							'controller'=>'news',
							'name'=>$viewFromMVC->translate('News'),
							'showOnlyInEnvironment'=>NULL,
						);
					}

					/**
					 * do we have a team?
					 */
					if (L8M_Config::getOption('l8m.team.enabled') &&
						$dynamicContentController['longName'] == 'Team') {

						$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
							'css'=>'user',
							'link'=>$this->view->url(array('module'=>'admin', 'controller'=>'team'), NULL, TRUE),
							'controller'=>'team',
							'name'=>$viewFromMVC->translate('Team'),
							'showOnlyInEnvironment'=>NULL,
						);
					}
				} else {

					$menuBoxes[$rememberMenu['dynamic-content']]['links'][] = array(
						'css'=>'application-form-edit',
						'link'=>$this->view->url(array('module'=>'admin', 'controller'=>$dynamicContentController['shortName']), NULL, TRUE),
						'controller'=>$dynamicContentController['shortName'],
						'name'=>$viewFromMVC->translate($dynamicContentController['longName']),
						'showOnlyInEnvironment'=>NULL,
					);

				}
			}
		}


		$returnValue = array(
			'returnValue'=>$menuBoxes,
			'rememberMenu'=>$rememberMenu,
			'standardControllerNames'=>$standardControllerNames,
		);

		return $returnValue;
	}
}