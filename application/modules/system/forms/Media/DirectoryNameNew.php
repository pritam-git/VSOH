<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Media/DirectoryNameNew.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: DirectoryNameNew.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Media_DirectoryNameNew
 *
 *
 */
class System_Form_Media_DirectoryNameNew extends L8M_JQuery_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_Form_Media_DirectoryNameNew instance.
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
			->setAttrib('id', 'formSystemMediaDirectoryNameNew')
		;

		/**
		 * mediax
		 */
		$formElement = new Zend_Form_Element_Text('name');
		$formElement
			->setLabel('Name')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_StripTags(),
			))
			->setValidators(array(
				new Zend_Validate_NotEmpty(),
				new Zend_Validate_Alnum(),
			))
		;
		$this->addElement($formElement);

		/**
		 * formSubmitButton
		 */
		$formSubmitButton = new Zend_Form_Element_Submit('formSystemMediaDirectoryNameNewSubmit');
		$formSubmitButton
			->setLabel('Submit')
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