<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/Entity/Create/BeforeSave.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeSave.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_Entity_Create_BeforeSave
 *
 *
 */
class PRJ_Entity_Create_BeforeSave
{

	private $_goOn = FALSE;
	private $_formValues = array();
	private $_exception = NULL;

	/**
	 * BeforeSave
	 *
	 * @param String $modelName
	 * @param array $formValues
	 * @param L8M_ModelForm_Base $form
	 */
	public function beforeSave($modelName, $formValues, $form)
	{
		$this->_formValues = $formValues;


		/**
		 * if brand option is member checked then add also its brand
		 */

        if(isset($_POST['entity_parent_user_id'])) {
            $entityModel = Default_Model_Entity::createQuery()
                ->addWhere('id = ?', array($_POST['entity_parent_user_id']))
                ->limit(1)
                ->execute()
                ->getFirst()
            ;
            if($entityModel) {
                $this->_formValues['department_id'] = $entityModel['department_id'];
                $this->_formValues['contract_type_id'] = $entityModel['contract_type_id'];
                $this->_formValues['region_id'] = $entityModel['region_id'];
                $this->_formValues['spoken_language'] = $entityModel['spoken_language'];
            }

            $entityM2nBrandModel = Default_Model_EntityM2nBrand::createQuery()
                ->addWhere('entity_id = ?', array($_POST['entity_parent_user_id']))
                ->execute()
            ;

            $i=0;
            foreach($entityM2nBrandModel as $brandOption) {

                $txt = 'entity_relation_m2n_entitym2nbrandoptionmodel_tabs_' . $brandOption->brand_id . '_rv';
                $_POST[$txt]["'is_member'"][0] = 1;
                $_POST[$txt]["'sell'"][0] = 0;
                $_POST[$txt]["'sport'"][0] = 0;
                $_POST[$txt]["'volume'"][0] = 0;
                $_POST[$txt]["'status'"][0] = 0;

                $_POST['entity_relation_m2n_entitym2nbrand'][$i] = $brandOption->brand_id;
                $this->_formValues['relation_m2n_entitym2nbrandoptionmodel'][$i] = $brandOption->brand_id;
                $this->_formValues['relation_m2n_entitym2nbrand'][$i] = $brandOption->brand_id;
                $i++;
            }
        } else {
            if(isset($_POST['entity_relation_m2n_entitym2nbrandoptionmodel'])){
                $i=0;
                foreach($_POST['entity_relation_m2n_entitym2nbrandoptionmodel'] as $brandOption){
                    $txt = 'entity_relation_m2n_entitym2nbrandoptionmodel_tabs_' . $brandOption . '_rv';
                    if($_POST[$txt]["'is_member'"][0] == 1){
                        $brandOptionModel = Default_Model_BrandOptionModel::getModelById($brandOption);
                        $brandOptionDetail = $brandOptionModel->toArray();
                        $brandDetail = Default_Model_Brand::getModelByShort($brandOptionDetail['short']);

                        $_POST['entity_relation_m2n_entitym2nbrand'][$i] = $brandDetail['id'];
                        $this->_formValues['relation_m2n_entitym2nbrand'][$i] = $brandDetail['id'];
                        $i++;
                    }
                }
            }
        }

        /**
		 * check if login already exists
		 */
		$entityModel = Doctrine_Query::create()
			->from('Default_Model_Entity m')
			->addWhere('m.login = ?', array($this->_formValues['email']))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * there is no entity model existing with the same login
		 */
		if (!$entityModel) {
			$this->_formValues['login'] = $this->_formValues['email'];
			$this->_goOn = TRUE;
		} else {

			/**
			 * add error to form element
			 *
			 * @var Zend_Form_Element
			 */
			$nameElement = $form->getElement('entity_email');
			$errorMsg = vsprintf(L8M_Translate::string('"%1s" is a value, that is not allowed.'),
				array(
					$this->_formValues['email'],
				)
			);
			$nameElement->addErrorMessage($errorMsg);
			$nameElement->markAsError();
		}

		/**
		 * validate phone number
		 */
		$isErr = FALSE;
		if($this->_formValues['phone'] != ""){
			$phone_string = str_replace (" ","", $this->_formValues['phone']);
			$phone = str_replace(['+', '-', ' '], '', $phone_string);
			if(is_numeric($phone)){
				$firstCharacter = $phone_string[0];
				$firstTwoCharacters = substr($phone_string, 0, 2);
				$nextTwoCharacters = substr($phone_string, 2, 2);
				if($firstCharacter == '+' && $nextTwoCharacters == '41'){
					$this->_goOn = TRUE;
				}else
				if($firstTwoCharacters == '00' && $nextTwoCharacters == '41'){
					$this->_formValues['phone'] = '+'.substr($phone_string, 2);
					$this->_goOn = TRUE;
				}else
				if($firstCharacter == 0 && $nextTwoCharacters != '41'){
					$this->_goOn = TRUE;
				}else
				if($firstTwoCharacters == '41'){
					$this->_formValues['phone'] = '+'.$this->_formValues['phone'];
				}else
				if($firstTwoCharacters != '41'){
					$this->_formValues['phone'] = '0'.$this->_formValues['phone'];
				}else{
					$isErr = TRUE;
				}
			}else{
				$isErr = TRUE;
			}

			if($isErr == TRUE){
				/**
				 * add error to form element
				 *
				 * @var Zend_Form_Element
				 */
				$nameElement = $form->getElement('entity_phone');
				$errorMsg = vsprintf(L8M_Translate::string('"%1s" is a value, that is not allowed.'),
					array(
						$this->_formValues['phone'],
					)
				);
				$nameElement->addErrorMessage($errorMsg);
				$nameElement->markAsError();
				$this->_goOn = FALSE;
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