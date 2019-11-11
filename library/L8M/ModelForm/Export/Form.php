<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/forms/Form.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Form.php 154 2014-08-13 08:27:52Z nm $
 */

/**
 *
 *
 * L8M_ModelForm_Export_Form
 *
 *
 */
class L8M_ModelForm_Export_Form extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_ModelForm_Export_Form instance.
	 *
	 * @return void
	 */
	public function init()
	{

		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formModelFormExport');
	}

	/**
	 * build form
	 *
	 * @param array $availableColumnNames
	 */
	public function buildMeUp($availableColumnNames = array(), $modelListID = NULL)
	{

		$viewFromMvc = Zend_Layout::getMvcInstance()->getView();

		/**
		 * export type
		 */
		$formElement = new L8M_JQuery_Form_Element_Select('export_type');
		$formElement
			->setDisableTranslator(TRUE)
			->setLabel($viewFromMvc->translate('Export as'))
			->setRequired(TRUE)
		;


		/**
		 * Option
		 */
		$formElement->addMultiOption(
			'',
			'-'
		);

		$directoryIterator = new DirectoryIterator(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'L8M' . DIRECTORY_SEPARATOR . 'ModelForm' . DIRECTORY_SEPARATOR . 'Export');
		foreach($directoryIterator as $file) {
			/* @var $file DirectoryIterator */
			if ($file->isFile() &&
				preg_match('/^(.+)\.php$/', $file->getFilename(), $match) &&
				$match[1] != 'Form') {

				$formElement->addMultiOption(
					$match[1],
					$match[1]
				);
			}
		}

		$modelListExportModel = Doctrine_Query::create()
			->from('Default_Model_ModelListExport m')
			->where('m.model_list_id = ? ', array($modelListID))
			->limit(1)
			->execute()
			->getFirst()
		;
		if ($modelListExportModel) {
			$formElement->setValue($modelListExportModel->export_type);
		}

		$this->addElement($formElement);

		$pdfDummy = new L8M_ModelForm_Export_Pdf_Base();
		$pdfDummy->SetFont('helvetica', '', 10);

		/**
		 * column size
		 */
		foreach ($availableColumnNames as $columnName => $columnOptions) {

			$modelListColumnExportModel = Doctrine_Query::create()
				->from('Default_Model_ModelListColumnExport m')
				->leftJoin('m.ModelListExport me')
				->where('me.model_list_id = ? AND m.column_name = ? ', array($modelListID, $columnName))
				->limit(1)
				->execute()
				->getFirst()
			;

			if ($modelListColumnExportModel) {
				$showValue = $modelListColumnExportModel->show_column;
			} else {
				$showValue = TRUE;
			}

			/**
			 * form element
			 */
			$formElement = new Zend_Form_Element_Checkbox('show_' . $columnName);
			$formElement
				->setDisableTranslator(TRUE)
				->setLabel(vsprintf($viewFromMvc->translate('Show %1s', 'en'), array($columnOptions['display'])))
				->setValue($showValue)
			;
			$this->addElement($formElement);

			/**
			 * form element
			 */
			if ($modelListColumnExportModel) {
				$widthValue = $modelListColumnExportModel->width;
			} else {
				$columnTitleWidth = $pdfDummy->GetStringWidth($columnOptions['display']) + 4;
				$calculatedWidth = round(intval($columnOptions['width']) / 860 * 100);
				$widthValue = max(array($columnTitleWidth, $calculatedWidth));
			}

			$formElement = new Zend_Form_Element_Text('num_' . $columnName);
			$formElement
				->setDisableTranslator(TRUE)
				->setLabel(vsprintf($viewFromMvc->translate('Width of %1s', 'en'), array($columnOptions['display'])))
				->setRequired(TRUE)
				->setFilters(array(
					new Zend_Filter_StripTags(),
					new Zend_Filter_StripNewlines(),
					new Zend_Filter_StringTrim(),
				))
				->setValue($widthValue)
			;
			$this->addElement($formElement);
		}

		/**
		 * formSubmit
		 */
		$formSubmit = new Zend_Form_Element_Submit('formModelFormExportSubmit');
		$formSubmit
			->setLabel('Download')
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