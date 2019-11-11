<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/forms/Configuration/Application.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Application.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Configuration_Application
 *
 *
 */
class System_Form_Configuration_Application extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_Form_Configuration_Application instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formConfiguration');


	}

	/**
	 * builds up the form
	 *
	 * @param array $formValues
	 * @return System_Form_Configuration_Application
	 */
	public function buildMeUp($formValues, $order)
	{

		foreach ($formValues as $environment => $configValues) {

//			echo '<h3>' . $environment . '</h3>';
			$environmentShort = str_replace('-', '_', L8M_Library::getUsableUrlStringOnly($environment));
			$formElementNames = array();
			foreach ($configValues as $key => $valueArray) {
				$formElement = NULL;
				$formElementName = $environmentShort . '_' . strtolower(str_replace('.', '_', $key));
				if (isset($valueArray['label'])) {
					$label = $valueArray['label'];
				} else {
					$label = $key;
				}

				if ($valueArray['isArray']) {
					$arrCounter = 0;
					$label .= ' []';
					foreach ($valueArray['values'] as $value) {
						if ($valueArray['type'] == 'text') {
							$formElement = new Zend_Form_Element_Text($formElementName . '_' . $arrCounter);
							$formElement
								->setDisableTranslator(TRUE)
							;
						} else
						if ($valueArray['type'] == 'boolean') {
							$formElement = new Zend_Form_Element_Select($formElementName . '_' . $arrCounter);
							$formElement
								->setDisableTranslator(TRUE)
								->addMultiOptions(array(
									'FALSE'=>'FALSE',
									'TRUE'=>'TRUE',
								))
								->setRequired(TRUE)
								->setValidators(array(
									new Zend_Validate_NotEmpty(),
								))
							;
						}

						if ($formElement) {
							$formElement
								->setLabel($label)
								->setFilters(array(
									new Zend_Filter_StripTags(),
								))
								->setValue($value)
							;
							$this->addElement($formElement);
							$formElementNames[] = $formElementName . '_' . $arrCounter;
						}

						$arrCounter ++;
					}
				}

				if ($valueArray['type'] == 'text') {
					$formElement = new Zend_Form_Element_Text($formElementName);
					$formElement
						->setDisableTranslator(TRUE)
					;
				} else
				if ($valueArray['type'] == 'boolean') {
					$formElement = new Zend_Form_Element_Select($formElementName);
					$formElement
						->setDisableTranslator(TRUE)
						->addMultiOptions(array(
							'FALSE'=>'FALSE',
							'TRUE'=>'TRUE',
						))
						->setRequired(TRUE)
						->setValidators(array(
							new Zend_Validate_NotEmpty(),
						))
					;
				}

				if ($formElement) {
					$formElement
						->setLabel($label)
						->setFilters(array(
							new Zend_Filter_StripTags(),
						))
						->setValue($valueArray['value'])
					;
					$this->addElement($formElement);
					$formElementNames[] = $formElementName;
				}
			}

			$newOrderFormElementNames = array();
			foreach ($order as $orderFormElementName => $orderFormElementConfig) {
				$newOrderFormElementName = $environmentShort . '_' . strtolower(str_replace('.', '_', $orderFormElementName));
				if (in_array($newOrderFormElementName, $formElementNames)) {
					if ($orderFormElementConfig['isArray']) {
						$possibleElement = TRUE;
						$possibleX = 0;
						while ($possibleElement) {
							if (in_array($newOrderFormElementName . '_' . $possibleX, $formElementNames)) {
								$newOrderFormElementNames[] = $newOrderFormElementName . '_' . $possibleX;
							} else {
								$possibleElement = FALSE;
							}
							$possibleX++;
						}
					}
					$newOrderFormElementNames[] = $newOrderFormElementName;
				}
			}

			if (count($formElementNames) > 0) {
				$this->addDisplayGroup(
					$newOrderFormElementNames,
					$environmentShort, array(
//						'legend'=>$environment,
					)
				);
				$displayGroup = $this->getDisplayGroup($environmentShort);
				$displayGroup
					->setDisableTranslator(TRUE)
					->setLegend(ucwords($environment))
				;

			}
		}

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formConfigurationSubmit');
		$formSubmitButton
			->setLabel('Submit')
		;
		$this->addElement($formSubmitButton);

		return $this;
	}
}