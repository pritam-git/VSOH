<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Media/Crop.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Crop.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Media_Crop
 *
 *
 */
class System_Form_Media_Crop extends L8M_JQuery_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_Form_Media_Crop instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		/**
		 * form
		 */
		$this
			->setAttrib('id', 'formSystemMediaCrop')
		;

		/**
		 * mediax
		 */
		$formMediaX = new Zend_Form_Element_Text('mediax');
		$formMediaX
			->setLabel('X-Coordinate')
			->setRequired(TRUE)
			->setValue(0)
//			->setAttrib('disabled', TRUE)
			->setAttrib('readonly', TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Digits(),
			))
		;
		$this->addElement($formMediaX);

		/**
		 * mediay
		 */
		$formMediaY = new Zend_Form_Element_Text('mediay');
		$formMediaY
			->setLabel('Y-Coordinate')
			->setRequired(TRUE)
			->setValue(0)
//			->setAttrib('disabled', TRUE)
			->setAttrib('readonly', TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Digits(),
			))
		;
		$this->addElement($formMediaY);

		/**
		 * mediaw
		 */
		$formMediaW = new Zend_Form_Element_Text('mediaw');
		$formMediaW
			->setLabel('Width')
			->setRequired(TRUE)
			->setValue(0)
//			->setAttrib('disabled', TRUE)
			->setAttrib('readonly', TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Digits(),
			))
		;
		$this->addElement($formMediaW);

		/**
		 * mediah
		 */
		$formMediaH = new Zend_Form_Element_Text('mediah');
		$formMediaH
			->setLabel('Height')
			->setRequired(TRUE)
			->setValue(0)
//			->setAttrib('disabled', TRUE)
			->setAttrib('readonly', TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Digits(),
			))
		;
		$this->addElement($formMediaH);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Dojo_Form_Element_SubmitButton('formSystemMediaCropSubmit');
		$formSubmitButton
			->setLabel('Crop')
		;
		$this->addElement($formSubmitButton);
	}
}