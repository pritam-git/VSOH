<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/NewsletterController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: NewsletterController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * NewsletterController
 *
 *
 */
class NewsletterController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */
	public function init()
	{
		if ($this->getOption('l8m.newsletter.enabled') == FALSE) {
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		/**
		 * init parent
		 */
		parent::init();
	}

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
		$this->_redirect($this->_helper->url('subscribe', 'newsletter', 'default'));
	}

	public function subscribeAction ()
	{
		$this->view->subscribed = FALSE;

		$form = new Default_Form_Newsletter_Subscribe();
		$form->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements());

		$form
			->setAction($this->_helper->url('subscribe', 'newsletter', 'default'))
		;

		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getParams())) {

			$formValues = $form->getValues();

			$newsletterEmailModel = Doctrine_Query::create()
				->from('Default_Model_NewsletterSubscriber c')
				->addWhere('c.email = ? ', array($formValues['email']))
				->execute()
				->getFirst()
			;

			if (!$newsletterEmailModel) {

				/**
				 * create model
				 */
				$model = new Default_Model_NewsletterSubscriber();

				/**
				 * add data to model
				 */
				$model->merge($formValues);
				$model->lang = L8M_Locale::getLang();

				/**
				 * save model
				 */
				$model->save();
			}

			$this->view->subscribed = TRUE;
		}

		$this->view->form = $form;
	}

	public function unsubscribeAction ()
	{
		$this->view->unsubscribed = FALSE;

		$emailParam = $this->_request->getParam('email');

		$form = new Default_Form_Newsletter_Unsubscribe();
		$form->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements());

		$form
			->setAction($this->_helper->url('unsubscribe', 'newsletter', 'default'))
			->getElement('email')->setValue($emailParam)
		;

		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getParams())) {

			$formValues = $form->getValues();

			$modelCollection = Doctrine_Query::create()
				->from('Default_Model_NewsletterSubscriber c')
				->addWhere('c.email = ? ', array($formValues['email']))
				->execute()
			;

			if ($modelCollection->count() > 0) {
				foreach ($modelCollection as $model) {
					$model->hardDelete();
				}
			}
			$this->view->unsubscribed = TRUE;
		}

		$this->view->form = $form;
	}
}