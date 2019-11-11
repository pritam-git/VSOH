<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/CronJobController.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: CronJobController.php 93 2014-08-21 10:06:37Z nm $
 */

/**
 *
 *
 * CronJobController
 *
 *
 */
class CronJobController extends L8M_Controller_Action
{

	public function init() {
		parent::init();

		if (md5(L8M_Config::getOption('l8m.cron.token')) != $this->_request->getParam('token')) {
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
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
	 * exportTranslationsFromDataBase action
	 *
	 * Exports all translation data from dataBase to files according to language
	 *
	 */
	public function exportTranslationsFromDatabaseAction()
	{
		if(L8M_Config::getOption('l8m.translation_updater.disabled')) {
			throw new L8M_Exception('Translation Updater is disable. Admin or supervisor needs to enable Translation Updater support.');
		}

		$translationUpdater = L8M_TranslationUpdater::factory();
		$translationUpdater->exportTranslations();

		//disable layout
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
	}

	/**
	 * importTranslationsIntoDataBase action
	 *
	 * Imports all translation data from files to database
	 *
	 */
	public function importTranslationsIntoDatabaseAction()
	{
		if(L8M_Config::getOption('l8m.translation_updater.disabled')) {
			throw new L8M_Exception('Translation Updater is disable. Admin or supervisor needs to enable Translation Updater support.');
		}

		$translationUpdater = L8M_TranslationUpdater::factory();
		$translationUpdater->importTranslations();

		//disable layout
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
	}

	public function addBrandAction()
	{
		// Select only parent users
		$entityCollection = Default_Model_Entity::createQuery()
			->addWhere('parent_user_id IS NULL')
			->execute()
		;

		$brandModels = array();
		$brandOptionModels = array();

		foreach($entityCollection as $entityModel) {
			foreach($entityModel->EntityM2nBrandOptionModel as $brandOption) {
				$brandShort = $brandOption->BrandOptionModel->short;
				if(!isset($brandModels[$brandShort])) {
					$brandModels[$brandShort] = Default_Model_Brand::getModelByShort($brandShort)->id;
				}

				$brandOptionModelValue = Default_Model_EntityM2nBrandOptionModelValues::getModelByColumn('entity_m2n_brand_option_model_id', $brandOption->id);

				// This is set directly form the form, so check if this value has been set
				if($brandOptionModelValue->is_member == 1) {
					$entityM2nBrandRelationCount = Default_Model_EntityM2nBrand::createQuery()
						->addWhere('entity_id = ?', array($entityModel->id))
						->addWhere('brand_id = ?', array($brandModels[$brandShort]))
						->count()
					;

					// If the proper entry for entityM2nBrand has not been created, create it
					if(!$entityM2nBrandRelationCount) {
						$newEntityM2nBrandRelation = new Default_Model_EntityM2nBrand();
						$newEntityM2nBrandRelation->entity_id = $entityModel->id;
						$newEntityM2nBrandRelation->brand_id = $brandModels[$brandShort];
						$newEntityM2nBrandRelation->save();
					}

					// Get all sub users
					$subEntityCollection = Default_Model_Entity::createQuery()
						->addWhere('parent_user_id = ?', array($entityModel->id))
						->execute()
					;

					foreach($subEntityCollection as $subEntityModel) {
						$subEntityM2nBrandOptionModelRelationCount = Default_Model_EntityM2nBrandOptionModel::createQuery()
							->addWhere('entity_id = ?', array($subEntityModel->id))
							->addWhere('brand_option_model_id = ?', array($brandOption->brand_option_model_id))
							->count()
						;

						// If sub user does not have entry for brandOptionModel, create it, and add the is_memeber value as well
						if(!$subEntityM2nBrandOptionModelRelationCount) {
							$newSubEntityM2nBrandOptionModelRelation = new Default_Model_EntityM2nBrandOptionModel();
							$newSubEntityM2nBrandOptionModelRelation->entity_id = $subEntityModel->id;
							$newSubEntityM2nBrandOptionModelRelation->brand_option_model_id = $brandOption->brand_option_model_id;
							$newSubEntityM2nBrandOptionModelRelation->save();

							$newSubEntityM2nBrandOptionModelValue = new Default_Model_EntityM2nBrandOptionModelValues();
							$newSubEntityM2nBrandOptionModelValue->entity_m2n_brand_option_model_id = $newSubEntityM2nBrandOptionModelRelation->id;
							$newSubEntityM2nBrandOptionModelValue->is_member = 1;
							$newSubEntityM2nBrandOptionModelValue->save();
						}

						$subEntityM2nBrandRelationCount = Default_Model_EntityM2nBrand::createQuery()
							->addWhere('entity_id = ?', array($subEntityModel->id))
							->addWhere('brand_id = ?', array($brandModels[$brandShort]))
							->count()
						;

						// If the proper entry for entityM2nBrand for sub user has not been created, create it
						if(!$subEntityM2nBrandRelationCount) {
							$subEntityM2nBrandRelation = new Default_Model_EntityM2nBrand();
							$subEntityM2nBrandRelation->entity_id = $subEntityModel->id;
							$subEntityM2nBrandRelation->brand_id = $brandModels[$brandShort];
							$subEntityM2nBrandRelation->save();
                        }

                        $subEntityModel->contract_type_id = $entityModel->contract_type_id;
                        $subEntityModel->region_id = $entityModel->region_id;
                        $subEntityModel->department_id = $entityModel->department_id;
                        $subEntityModel->spoken_language = $entityModel->spoken_language;
                        $subEntityModel->save();
					}
				}
			}

			foreach($entityModel->EntityM2nBrand as $brand) {
				$brandOptionShort = $brand->Brand->short;
				if(!isset($brandOptionModels[$brandOptionShort])) {
					$brandOptionModels[$brandOptionShort] = Default_Model_BrandOptionModel::getModelByShort($brandOptionShort)->id;
				}

				$entityM2nBrandOptionRelationCount = Default_Model_EntityM2nBrandOptionModel::createQuery()
					->addWhere('entity_id = ?', array($entityModel->id))
					->addWhere('brand_option_model_id = ?', array($brandOptionModels[$brandOptionShort]))
					->count()
				;

				// If the proper entry for entityM2nBrandOptionModel has not been created, create it
				if(!$entityM2nBrandOptionRelationCount) {
					$newEntityM2nBrandOptionRelation = new Default_Model_EntityM2nBrandOptionModel();
					$newEntityM2nBrandOptionRelation->entity_id = $entityModel->id;
					$newEntityM2nBrandOptionRelation->brand_option_model_id = $brandOptionModels[$brandOptionShort];
					$newEntityM2nBrandOptionRelation->save();

					$newEntityM2nBrandOptionValue = new Default_Model_EntityM2nBrandOptionModelValues();
					$newEntityM2nBrandOptionValue->entity_m2n_brand_option_model_id = $newEntityM2nBrandOptionRelation->id;
					$newEntityM2nBrandOptionValue->is_member = 1;
					$newEntityM2nBrandOptionValue->save();
				}

				// Get all sub users
				$subEntityCollection = Default_Model_Entity::createQuery()
					->addWhere('parent_user_id = ?', array($entityModel->id))
					->execute()
				;

				foreach($subEntityCollection as $subEntityModel) {
					$subEntityM2nBrandOptionModelRelationCount = Default_Model_EntityM2nBrandOptionModel::createQuery()
						->addWhere('entity_id = ?', array($subEntityModel->id))
						->addWhere('brand_option_model_id = ?', array($brandOptionModels[$brandOptionShort]))
						->count()
					;

					// If sub user does not have entry for brandOptionModel, create it, and add the is_memeber value as well
					if(!$subEntityM2nBrandOptionModelRelationCount) {
						$newSubEntityM2nBrandOptionModelRelation = new Default_Model_EntityM2nBrandOptionModel();
						$newSubEntityM2nBrandOptionModelRelation->entity_id = $subEntityModel->id;
						$newSubEntityM2nBrandOptionModelRelation->brand_option_model_id = $brandOptionModels[$brandOptionShort];
						$newSubEntityM2nBrandOptionModelRelation->save();

						$newSubEntityM2nBrandOptionModelValue = new Default_Model_EntityM2nBrandOptionModelValues();
						$newSubEntityM2nBrandOptionModelValue->entity_m2n_brand_option_model_id = $newSubEntityM2nBrandOptionModelRelation->id;
						$newSubEntityM2nBrandOptionModelValue->is_member = 1;
						$newSubEntityM2nBrandOptionModelValue->save();
					}

					$subEntityM2nBrandRelationCount = Default_Model_EntityM2nBrand::createQuery()
						->addWhere('entity_id = ?', array($subEntityModel->id))
						->addWhere('brand_id = ?', array($brand->Brand->id))
						->count()
					;

					// If the proper entry for entityM2nBrand for sub user has not been created, create it
					if(!$subEntityM2nBrandRelationCount) {
						$subEntityM2nBrandRelation = new Default_Model_EntityM2nBrand();
						$subEntityM2nBrandRelation->entity_id = $subEntityModel->id;
						$subEntityM2nBrandRelation->brand_id = $brand->Brand->id;
						$subEntityM2nBrandRelation->save();
                    }
				}
			}
		}

		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
		Zend_Layout::getMvcInstance()->disableLayout();
	}
}
