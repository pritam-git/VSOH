<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/ContactController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ContactController.php 163 2014-08-21 10:06:35Z nm $
 */

/**
 *
 *
 * ContactController
 *
 *
 */
class ContactController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		$form = new Default_Form_Contact_Form();
		$form
			->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements())
			->setAction($this->_helper->url('index', 'contact', 'default'))
		;

		$this->view->emailIsSend = FALSE;

		/**
		 * form is submitted and valid
		 */
		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost())) {

			$formValues = $form->getValues();

			/**
			 * email
			 */
			$email = L8M_MailV2::factory('contact');
			$email
				->setFrom($this->getOption('resources.mail.defaultFrom.email'), $this->getOption('resources.mail.defaultFrom.name'))
				->addTo($this->getOption('resources.mail.defaultReplyTo.email'))
			;

			/**
			 * content
			 */
			$formTime = time();
			$content = L8M_MailV2_Part_Content::factory('contact', $email);
			$content
				->setDynamicVar('CURRENT_DATE', date('d.m.Y', $formTime))
				->setDynamicVar('CURRENT_TIME', date('H:i:s', $formTime))
			;
			$email->addPart($content);

			/**
			 * data
			 */
			$data = L8M_MailV2_Part_Data::fromForm(
				$form,
				array(
					'exclude'=>array(
						'captcha',
					),
				)
			);

			$data->setEmailTemplatePartShort('contact_data');

			$data
				->setHeadline($this->view->translate('The request data'))
				->setContent($this->view->translate('Please note the following dates that were entered in the contact form.'))
			;
			$email->addPart($data);

			/**
			 * send email
			 */
			try {
				$email->send();
				$this->view->emailIsSend = TRUE;
			} catch (L8M_Mail_Exception $exception) {

			}

		}

		$this->view->form = $form;
	}
}