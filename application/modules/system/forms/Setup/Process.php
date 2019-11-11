<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/forms/Setup/Process.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Process.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_Form_Setup_Process
 *
 *
 */
class System_Form_Setup_Process extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes System_Form_Setup_Process instance.
	 *
	 * @return void
	 */
	public function init()
	{

		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formSystemSetupProcess');

		/**
		 * formId
		 */
		$formId = new Zend_Form_Element_Hidden('id');
		$formId->setDecorators(array(
			new Zend_Form_Decorator_ViewHelper(),
			new Zend_Form_Decorator_HtmlTag(array(
				'tag'=>'dd',
			)),
		));
		$this->addElement($formId);

		/**
		 * formProcess
		 */
		$formProcess = new Zend_Form_Element_Checkbox('process');
		$formProcess
			->setLabel('Set Up this application')
			->setDescription('You should only tick this box if you have fully understood what\'s going to happen once you\'ve clicked the button below, you can\'t stop it.')
			->setRequired(TRUE)
			->setUncheckedValue(FALSE)
		;
		$this->addElement($formProcess);

		/**
		 * formDeleteTempImages
		 */
		$formDeleteTempImages = new Zend_Form_Element_Checkbox('delete_temp_images');
		$formDeleteTempImages
			->setLabel('Delete temporary Images')
			->setDescription('You agree, that setup will delete all files in "/data/media", "/public/img/default/captcha" and "/public/mediafile".')
			->setUncheckedValue(FALSE)
		;
		$this->addElement($formDeleteTempImages);

		/**
		 * formSubmit
		 */
		$formSubmit = new Zend_Form_Element_Submit('formSystemSetupProcessSubmit');
		$formSubmit
			->setLabel('Yes, setup now!')
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