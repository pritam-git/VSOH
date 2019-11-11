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
class PRJ_TeamMembers_Create_BeforeSave
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
		 * validate phone number
		 */
		$isErr = FALSE;
		$this->_goOn = TRUE;
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
				$nameElement = $form->getElement('team_members_phone');
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
