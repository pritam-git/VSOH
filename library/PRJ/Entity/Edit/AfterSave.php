<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/Entity/Edit/AfterSave.php
 * @author	    <nm@l8m.com>
 * @version    $Id: AfterSave.php 19 2019-09-30 10:25:42Z nm $
 */

/**
 *
 *
 * PRJ_Entity_Edit_AfterSave
 *
 *
 */
class PRJ_Entity_Edit_AfterSave
{

	private $_goOn = FALSE;
	private $_formValues = array();
	private $_exception = NULL;

	/**
	 * AfterSave
	 *
	 * @param integer $modelID
	 * @param String $modelName
	 * @param array $formValues
	 * @param L8M_ModelForm_Base $form
	 */
	public function afterSave($modelID, $modelName, $formValues)
	{
        $this->_formValues = $formValues;

        $entity_id = $modelID;
        // Select only parent users

		$entityModel = Default_Model_Entity::createQuery()
            ->addWhere('id = ?', array($entity_id))
            ->limit(1)
            ->execute()
            ->getFirst()
        ;
        if($entityModel) {
            // Get all sub users
            $subEntityCollection = Default_Model_Entity::createQuery()
                ->addWhere('parent_user_id = ?', array($entity_id))
                ->execute()
            ;

            if($subEntityCollection){

                $brandModel = Default_Model_Brand::createQuery()
                    ->execute()
                ;
                $brandModelId = array_column($brandModel->toArray(), 'id');


                $brandModelDeleteId = array_diff($brandModelId, $formValues['relation_m2n_entitym2nbrand']);

                foreach ($subEntityCollection as $subUserValues){
                    $entityM2nBrandModelUpdate = Default_Model_Entity::createQuery()
                        ->update()
                        ->addWhere('id = ?', array($subUserValues->id))
                        ->set('department_id', $formValues["department_id"])
                        ->set('contract_type_id', $formValues["contract_type_id"])
                        ->set('region_id', $formValues["region_id"])
                        ->execute()
                    ;
                    foreach ($brandModelDeleteId as $brandDeleteId) {

                        $entityM2nBrandModelCount = Default_Model_EntityM2nBrand::createQuery()
                            ->addWhere('entity_id = ?', array($subUserValues->id))
                            ->addWhere('brand_id = ?', array($brandDeleteId))
                            ->count()
                        ;

                        if($entityM2nBrandModelCount) {

                            $entityM2nBrandModelDelete = Default_Model_EntityM2nBrand::createQuery()
                                ->addWhere('entity_id = ?', array($subUserValues->id))
                                ->addWhere('brand_id = ?', array($brandDeleteId))
                                ->limit(1)
                                ->execute()
                                ->getFirst()
                            ;

                            if($entityM2nBrandModelDelete) {
                                $entityM2nBrandModelDelete->hardDelete();
                            }
                        }

                        $entityM2nBrandOptionModelOptionCount = Default_Model_EntityM2nBrandOptionModel::createQuery()
                            ->addWhere('entity_id = ?', array($subUserValues->id))
                            ->addWhere('brand_option_model_id = ?', array($brandDeleteId))
                            ->count()
                        ;
                        if($entityM2nBrandOptionModelOptionCount) {
                            $entityM2nBrandModelOptionDelete = Default_Model_EntityM2nBrandOptionModel::createQuery()
                                ->addWhere('entity_id = ?', array($subUserValues->id))
                                ->addWhere('brand_option_model_id = ?', array($brandDeleteId))
                                ->limit(1)
                                ->execute()
                                ->getFirst()
                            ;

                            if($entityM2nBrandModelOptionDelete) {

                                $entityM2nBrandModelOptionValuesDelete = Default_Model_EntityM2nBrandOptionModelValues::createQuery()
                                    ->addWhere('entity_m2n_brand_option_model_id = ?', array($entityM2nBrandModelOptionDelete->id))
                                    ->limit(1)
                                    ->execute()
                                    ->getFirst()
                                ;

                                if($entityM2nBrandModelOptionValuesDelete) {
                                    $entityM2nBrandModelOptionValuesDelete->hardDelete();
                                }

                                $entityM2nBrandModelOptionDelete->hardDelete();
                            }
                        }
                    }
                    $positionCount = 0;
                    foreach ($formValues['relation_m2n_entitym2nbrand'] as $brandEntityModel) {
                        $positionCount++;
                        $entityM2nBrandModel = Default_Model_EntityM2nBrand::createQuery()
                            ->addWhere('entity_id = ?', array($subUserValues->id))
                            ->addWhere('brand_id = ?', array($brandEntityModel))
                            ->count()
                        ;
                        if(!$entityM2nBrandModel) {
							$newEntityM2nBrandRelation = new Default_Model_EntityM2nBrand();
                            $newEntityM2nBrandRelation->entity_id = $subUserValues->id;
                            $newEntityM2nBrandRelation->brand_id = $brandEntityModel;
                            $newEntityM2nBrandRelation->save();
                        }

                            $brandOptionModelId = $brandEntityModel;
                            $subEntityM2nBrandOptionModelRelationCount = Default_Model_EntityM2nBrandOptionModel::createQuery()
                                ->addWhere('entity_id = ?', array($subUserValues->id))
                                ->addWhere('brand_option_model_id = ?', array($brandOptionModelId))
                                ->count()
                            ;

                            if(!$subEntityM2nBrandOptionModelRelationCount) {
                                $newEntityM2nBrandOptionModelInstance = new Default_Model_EntityM2nBrandOptionModel();
                                $newEntityM2nBrandOptionModelInstance->entity_id = $subUserValues->id;
                                $newEntityM2nBrandOptionModelInstance->brand_option_model_id = $brandOptionModelId;
                                $newEntityM2nBrandOptionModelInstance->position = $positionCount;
                                $newEntityM2nBrandOptionModelInstance->save();

                                $entityM2nBrandOptionModelId = $newEntityM2nBrandOptionModelInstance->id;

                                $newEntityM2nBrandOptionModelValueInstance = new Default_Model_EntityM2nBrandOptionModelValues();
                                $newEntityM2nBrandOptionModelValueInstance->entity_m2n_brand_option_model_id = $entityM2nBrandOptionModelId;
                                $newEntityM2nBrandOptionModelValueInstance->is_member = 1;
                                $newEntityM2nBrandOptionModelValueInstance->save();
                            }


                    }
                }
                $this->_goOn = TRUE;
            }

        }
    }


	/**
	 * Returns new from values.
	 *
	 * @return array
	 */
	public function replaceFormValues()
	{
		return $this->_formValues;
	}

	/**
	 * Flags whether to go on or not.
	 *
	 * @return boolean
	 */
	public function goOn()
	{
		return $this->_goOn;
	}

	/**
	 * Returns internal error.
	 *
	 * @return Exception
	 */
	public function getException()
	{
		return $this->_exception;
	}
}