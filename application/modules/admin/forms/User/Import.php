<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/form/User/Import.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: Import.php 7 2014-03-11 18:00:40Z dp $
 */

/**
 *
 *
 * Admin_Form_User_Import
 *
 *
 */
class Admin_Form_User_Import extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_Form_User_Import instance.
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
			->setAttrib('id', 'formAdminUserImport')
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
			 * formImport
			 *
			 * @todo do not hard-code upload path
			 */
			$formImport = new Zend_Form_Element_File('EntityImportData');
			$formImport
				->setLabel($this->getView()->translate('Adressliste', 'de'))
				->setDescription($this->getView()->translate('Select a file you would like to upload.'))
				->setRequired(TRUE)
				->setValueDisabled(TRUE)
				->setDisableTranslator(TRUE)
				->addValidators(array(
					new Zend_Validate_File_Extension(array('xls', 'xlsx')),
					new Zend_Validate_File_FilesSize(L8M_Library::getMaxUploadSize())
				))
				->setFilters(array(
					new Zend_Filter_File_Rename(array(
						'overwrite'=>FALSE,
					)),
				))
			;
			$this->addElement($formImport);

			/**
			 * CRDC formImport
			 *
			 * @todo do not hard-code upload path
			 */
			$formCadcImport = new Zend_Form_Element_File('CadcImportFile');
			$formCadcImport
				->setLabel($this->getView()->translate('Opel-Partner - Liste (mit CADC-Code)', 'de'))
				->setDescription($this->getView()->translate('Select a file you would like to upload.'))
				->setRequired(TRUE)
				->setValueDisabled(TRUE)
				->setDisableTranslator(TRUE)
				->addValidators(array(
					new Zend_Validate_File_Extension(array('xls', 'xlsx')),
					new Zend_Validate_File_FilesSize(L8M_Library::getMaxUploadSize())
				))
				->setFilters(array(
					new Zend_Filter_File_Rename(array(
						'overwrite'=>FALSE,
					)),
				))
			;
			$this->addElement($formCadcImport);

			/**
			 * formSubmitButton
			 */
			$formSubmit = new Zend_Form_Element_Submit('formAdminUserImportSubmit');
			$formSubmit
				->setLabel('Import File')
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