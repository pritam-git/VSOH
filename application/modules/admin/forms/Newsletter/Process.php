<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/forms/Newsletter/Process.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Process.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Admin_Form_Newsletter_Process
 *
 *
 */
class Admin_Form_Newsletter_Process extends L8M_Form
{

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes Admin_Form_Newsletter_Process instance.
	 *
	 * @return void
	 */
	public function init()
	{

		parent::init();

		/**
		 * form
		 */
		$this->setAttrib('id', 'formAdminNewsletterProcess');

		/**
		 * view from MVC
		 */
		$viewFormMVC = Zend_Layout::getMvcInstance()->getView();

		/**
		 * newsletter type form
		 */
		$formNewsletterType = new L8M_JQuery_Form_Element_Select('newsletter_subscriber_type');
		$formNewsletterType
			->setLabel($viewFormMVC->translate('Newsletter Subscriber Type'))
			->setDescription($viewFormMVC->translate('Bitte wählen Sie aus, an welchen Typ der Newsletter-Angemeldeten der Newsletter verschickt werden sollen.', 'de'))
			->setDisableTranslator(TRUE)
		;

		$formNewsletterType->addMultiOption(
			'',
			'-'
		);

		/**
		 * newsletter type options
		 */
		$newsletterSubscriberTypeCollection = Doctrine_Query::create()
			->from('Default_Model_NewsletterSubscriberType m')
			->execute()
		;

		if ($newsletterSubscriberTypeCollection->count() > 0) {

			foreach ($newsletterSubscriberTypeCollection as $newsletterSubscriberTypeModel) {

				$formNewsletterType->addMultiOption(
					$newsletterSubscriberTypeModel->id,
					$newsletterSubscriberTypeModel->name
				);
			}
		}
		$this->addElement($formNewsletterType);

		/**
		 * formProcess
		 */
		$formProcess = new Zend_Form_Element_Checkbox('process');
		$formProcess
			->setLabel('Are you sure?')
			->setDescription('This may take some more minutes. Do not refresh the page after you have clicked: "Yes, this is my wish!"')
			->setRequired(TRUE)
			->setUncheckedValue(FALSE)
		;
		$this->addElement($formProcess);

		/**
		 * formSubmit
		 */
		$formSubmit = new Zend_Form_Element_Submit('formAdminNewsletterProcessSubmit');
		$formSubmit
			->setLabel('Yes, this is my wish!')
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