<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Plugin/Upload.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Upload.php 5 2014-02-10 10:17:08Z nm $
 */

/**
 *
 *
 * System_Form_Plugin_Upload
 *
 *
 */
class System_Form_Plugin_Upload extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_Form_Media_Upload instance.
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
			->setMethod(Zend_Form::METHOD_POST)
			->setAttrib('id', 'formAdminUpload')
			->setEnctype(Zend_Form::ENCTYPE_MULTIPART)
		;

		/**
		 * formMedia
		 *
		 * @todo do not hard-code upload path
		 */
		$formMedia = new Zend_Form_Element_File('FileData');
		$formMedia
			->setLabel($this->getView()->translate('Plugin'))
			->setDescription($this->getView()->translate('Select a file you would like to upload.'))
			->setDestination(Default_Model_Plugin::getUploadPath())
			->setValueDisabled(TRUE)
			->setDisableTranslator(TRUE)
			->addValidator(new Zend_Validate_File_FilesSize(Default_Model_Plugin::getMaxUploadSize()))
			->addValidator('Extension', false, 'zip')
			->setAttrib('accept', 'application/zip')
			->setRequired(TRUE)
			->setFilters(array(
				new Zend_Filter_File_Rename(array(
					'overwrite'=>FALSE,
				)),
			))
		;
		$this->addElement($formMedia);

		/**
		 * formSubmitButton
		 */
		$formSubmit = new Zend_Form_Element_Submit('formSystemMediaUploadSubmit');
		$formSubmit
			->setLabel('Upload & Install')
			->setDecorators(array(
				new Zend_Form_Decorator_ViewHelper(),
				new Zend_Form_Decorator_HtmlTag(array(
					'tag'=>'dd',
				)),
			))
		;
		$this->addElement($formSubmit);
	}
}