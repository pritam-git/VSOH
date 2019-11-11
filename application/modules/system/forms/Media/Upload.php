<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/form/Media/Upload.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Upload.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Media_Upload
 *
 *
 */
class System_Form_Media_Upload extends L8M_Form
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
			->setAttrib('id', 'formSystemMediaUpload')
			->setEnctype(Zend_Form::ENCTYPE_MULTIPART)
		;
	}

	public function buildMeUp($renderAll = TRUE, $isXmlRequest = FALSE, $renderRoleFormelement = TRUE)
	{

		if (!$isXmlRequest) {

			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

			if ($renderRoleFormelement) {

				/**
				 * role
				 */
				$formRole = new L8M_JQuery_Form_Element_Select('role_short');
				$formRole
					->setLabel($viewFromMVC->translate('Role'))
					->setRequired(TRUE)
					->setDisableTranslator(TRUE)
				;

				/**
				 * role Options
				 */
				$roleOptions = Doctrine_Query::create()
					->from('Default_Model_Role r')
					->select('r.id, r.short, rt.name')
					->leftJoin('r.Translation rt')
					->addWhere('rt.lang = ?', L8M_Locale::getLang())
					->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
					->execute()
				;

				if (is_array($roleOptions) &&
					count($roleOptions)>0) {
					foreach($roleOptions as $roleOption) {
						$formRole->addMultiOption(
							$roleOption['r_short'],
							$roleOption['rt_name']
						);
					}
				}
				$formRole->setValue('guest');
				$this->addElement($formRole);
			}
		}

		if ($renderAll) {

			/**
			 * formMedia
			 *
			 * @todo do not hard-code upload path
			 */
			$formMedia = new Zend_Form_Element_File('FileData');
			$formMedia
				->setLabel($this->getView()->translate('Media'))
				->setDescription($this->getView()->translate('Select a file you would like to upload.'))
				->setDestination(Default_Model_Media::getUploadPath())
				->setValueDisabled(TRUE)
				->setDisableTranslator(TRUE)
				->addValidator(new Zend_Validate_File_FilesSize(L8M_Library::getMaxUploadSize()))
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
				->setLabel('Upload Media')
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
}