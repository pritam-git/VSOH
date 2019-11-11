<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/forms/ModelName/ProductOptions.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ProductOptions.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Admin_Form_ModelName_ProductOptions
 *
 *
 */
class Admin_Form_ModelName_ProductOptions extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_Form_ModelName_ProductOptions instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formModelName');

		/**
		 * formLogin
		 */
		$formElement = new Zend_Form_Element_Text('name');
		$formElement
			->setLabel('Name')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_Alpha(),
				new Zend_Validate_NotEmpty(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formModelNameSubmit');
		$formSubmitButton
			->setLabel('Create')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
		;
		$this->addElement($formSubmitButton);
	}
}