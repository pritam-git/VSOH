<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/Survey/Create/BeforeSave.php
 * @author	   Unnati Visani <unnati.visani@bcssarl.com>
 * @version    $Id: BeforeSave.php 7 2019-11-01 12:18:40Z nm $
 */

/**
 *
 *
 * PRJ_Survey_Create_BeforeSave
 *
 *
 */
class PRJ_Survey_Create_BeforeSave
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
		 * serialize the survey data
		 */

		if(isset($this->_formValues['survey_data'])) {
			$data = json_decode($this->_formValues['survey_data'], TRUE);
			if(isset($data['locale'])) {
				$this->_formValues['survey_language'] = $data['locale'];
			} else {
				$this->_formValues['survey_language'] = 'de';
			}

			$this->_formValues['survey_data'] = serialize($this->_formValues['survey_data']);
			$this->_goOn = TRUE;
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