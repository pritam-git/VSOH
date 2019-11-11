<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/NewsletterController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: NewsletterController.php 399 2015-09-02 09:23:38Z nm $
 */

/**
 *
 *
 * Admin_NewsletterController
 *
 *
 */
class Admin_NewsletterController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Newsletter';
	private $_modelListShort = 'nwsc';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Newsletter';

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	private $_modelList = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_NewsletterController.
	 *
	 * @return void
	 */
	public function init ()
	{

		/**
		 * set headline
		 */
		$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
		$this->_helper->layout()->headline .= ': ' . $this->view->translate($this->_modelListUntranslatedTitle);

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		/**
		 * start model list
		 */
		$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
		$this->_modelList
			->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
			->disableSubLinks()
//			->disableButtonAdd()
//			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
			->setButtonSeperator()
			->setButton('Teste Newsletter', array('action'=>'tester', 'controller'=>'newsletter', 'module'=>'admin'), 'email', TRUE)
			->setButton('Sende Newsletter', array('action'=>'sender', 'controller'=>'newsletter', 'module'=>'admin'), 'email', TRUE)
//			->disableSaveWhere()
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
				'name',
				'title',
				'media_id',
				'content_plain',
				'content_html',
			),
			'addIgnoredColumns'=>array(
			),
			'addIgnoredM2nRelations'=>array(
			),
			'ignoreColumnRelation'=>array(
			),
			'ignoreColumnInMultiRelation'=>array(
			),
			'relationM2nValuesDefinition'=>array(
			),
			'mediaDirectory'=>array(
				'media_id'=>'/medias/newsletter',
			),
			'mediaRole'=>array(
				'media_id'=>'guest',
			),
			'columnLabels'=>array(
				'newsletter_media_id'=>'Media',
				'lang'=>'Language',
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'content_plain'=>'textarea',
			),
			'addStaticFormElements'=>array(
			),
			'M2NRelations'=>array(
			),
			'replaceColumnValuesInMultiRelation'=>array(
			),
			'relationColumnInMultiRelation'=>array(
			),
			'multiRelationCondition'=>array(
			),
			'tinyMCE'=>array(
				'content_html'=>array(
					'relative_urls'=>FALSE,
					'remove_script_host'=>FALSE,
					'convert_urls'=>TRUE,
				),
			),
			'setFormLanguage'=>L8M_Locale::getDefaultSystem(),
			'action'=>$this->_request->getActionName(),
			//'debug'=>TRUE,
		);

		$this->view->modelFormListButtons = $this->_modelList->getButtons(NULL, $this->_modelListShort, $this->_modelListConfig);
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
		if ($this->_modelListName) {
			$this->_forward('list');
		}
	}

	/**
	 * List action.
	 *
	 * @return void
	 */
	public function listAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('List');

		/**
		 * start model list
		 */
		$this->_modelList->listCollection($this->_modelListShort);
	}

	/**
	 * Create action.
	 *
	 * @return void
	 */
	public function createAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Add');

		/**
		 * start model list
		 */
		$this->_modelList->createModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeSave'=>array(
			),
			'addStandardColumnValues'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function deleteAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Delete');

		/**
		 * start model list
		 */
		$this->_modelList->deleteModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforePreDelete'=>array(
			),
			'doBefore'=>array(
			),
		)));
	}

	/**
	 * Edit action.
	 *
	 * @return void
	 */
	public function editAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Edit');

		/**
		 * start model list
		 */
		$this->_modelList->editModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeFormOutput'=>array(
			),
			'doBeforeSave'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	public function senderAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Sending Newsletter');

		/**
		 * some vars
		 */
		$newsletterID = $this->_request->getParam('id', NULL, FALSE);
		$this->view->emailIsSend = FALSE;

		/**
		 * setup request
		 */
		$form = new Admin_Form_Newsletter_Process();
		$form->addDecorators(array(
			new L8M_Form_Decorator_Form_Small(),
			new L8M_Form_Decorator_FormHasRequiredElements(),
		));
		$form->setAction($this->_helper->url('sender', 'newsletter', 'admin', array('id'=>$newsletterID)));

		/**
		 * check for newsletter
		 */
		$newsletterModel = Doctrine_Query::create()
			->from('Default_Model_Newsletter m')
			->addWhere('m.id = ? ', array($newsletterID))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * form is submitted and valid
		 */
		if ($newsletterModel &&
			$form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost())) {

			$formValues = $form->getValues();

			/**
			 * check newsletter subscriber type
			 */
			$newsletterSubscriberTypeModel = Doctrine_Query::create()
				->from('Default_Model_NewsletterSubscriberType m')
				->addWhere('m.id = ? ', array($formValues['newsletter_subscriber_type']))
				->limit(1)
				->execute()
				->getFirst()
			;

			set_time_limit(0);

			foreach (L8M_Locale::getSupported() as $langShort) {
				$newsletterEmailQuery = Doctrine_Query::create()
					->from('Default_Model_NewsletterSubscriber m')
					->addWhere('m.lang = ? ', array($langShort))
				;

				if ($newsletterSubscriberTypeModel) {
					$newsletterEmailQuery = $newsletterEmailQuery
						->addWhere('m.newsletter_subscriber_type_id = ? ', array($newsletterSubscriberTypeModel->id))
					;
				}

				$newsletterEmailCollection = $newsletterEmailQuery
					->execute()
				;

				if ($newsletterEmailCollection->count() > 0) {

					for ($partCounter = 0; $partCounter < $newsletterEmailCollection->count(); $partCounter = $partCounter + 100) {

						if ($partCounter + 100 >= $newsletterEmailCollection->count()) {
							$innerPartEnd = $newsletterEmailCollection->count();
						} else {
							$innerPartEnd = $partCounter + 100;
						}

						for ($innerPartCounter = $partCounter; $innerPartCounter < $innerPartEnd; $innerPartCounter++) {

							/**
							 * email
							 */
							$email = L8M_MailV2::factory('admin_newsletter', $langShort);
							$email
								->setFrom($this->getOption('resources.mail.defaultFrom.email'), $this->getOption('resources.mail.defaultFrom.name'))
//								->addTo($this->getOption('resources.mail.defaultReplyTo.email'))
							;

							$email->setSubject($newsletterModel->Translation[$langShort]->title);
							$email->addTo($newsletterEmailCollection[$innerPartCounter]->email);

							$emailContent = L8M_MailV2_Part_Content::factory('admin_newsletter_content', $email);
							$emailContent
								->setDynamicVar('FIRSTNAME', $newsletterEmailCollection[$innerPartCounter]->firstname)
								->setDynamicVar('LASTNAME', $newsletterEmailCollection[$innerPartCounter]->lastname)
							;

							if ($newsletterEmailCollection[$innerPartCounter]->salutation_id) {
								$emailContent->setDynamicVar('SALUTATION', $newsletterEmailCollection[$innerPartCounter]->Salutation->Translation[$langShort]->name);
							}

							$emailContent
								->setDynamicVar('UNSUBSCRIBE', L8M_Library::getSchemeAndHttpHost() . $this->_helper->url('unsubscribe', 'newsletter', 'default', array('email'=>$newsletterEmailCollection[$innerPartCounter]->email)))
							;

							$contentPlain = $newsletterModel->Translation[$langShort]->content_plain;
							if (!$contentPlain) {
								$contentPlain = $this->view->translate('empty');
							}
							$contentHtml = $newsletterModel->Translation[$langShort]->content_html;
							if (!$contentHtml) {
								$contentHtml = $this->view->translate('empty');
							}
							$emailContent
								->setContent($contentPlain, L8M_MailV2_Part::RENDER_TEXT)
								->setContent($contentHtml, L8M_MailV2_Part::RENDER_HTML)
							;
							$email->addPart($emailContent);

							if ($newsletterModel->media_id) {
								$emailAttachment = L8M_MailV2_Part_Attachment::factory('email_attachment', $email);
								$emailAttachment->addItem($newsletterModel->Media->file_name, $newsletterModel->Media->getStoredFilePath());
								$email->addPart($emailAttachment);
							}

							/**
							 * footer
							 */
							$content = L8M_MailV2_Part::factory('footer', $email);
							$email->addPart($content);

							/**
							 * send email
							 */
							try {
								$email->send();
								$this->view->emailIsSend = TRUE;
							} catch (L8M_Mail_Exception $exception) {
								$this->view->emailIsSend = FALSE;
							}
						}
					}
				}
			}
		} else {
			$this->view->form = $form;
		}
	}

	public function testerAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Test');

		/**
		 * some vars
		 */
		$newsletterID = $this->_request->getParam('id', NULL, FALSE);
		$this->view->emailIsSend = FALSE;

		/**
		 * setup request
		 */
		$form = new Admin_Form_Newsletter_Tester();
		$form->addDecorators(array(
			new L8M_Form_Decorator_Form_Small(),
			new L8M_Form_Decorator_FormHasRequiredElements(),
		));
		$form->setAction($this->_helper->url('tester', 'newsletter', 'admin', array('id'=>$newsletterID)));

		/**
		 * check for newsletter
		 */
		$newsletterModel = Doctrine_Query::create()
			->from('Default_Model_Newsletter m')
			->addWhere('m.id = ? ', array($newsletterID))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * form is submitted and valid
		 */
		if ($newsletterModel &&
			$form->isSubmitted() &&
			$form->isValid($this->getRequest()->getPost())) {

			$formValues = $form->getValues();


			$salutationModel = Doctrine_Query::create()
				->from('Default_Model_Salutation m')
				->addWhere('m.id = ? ', array($formValues['salutation_id']))
				->limit(1)
				->execute()
				->getFirst()
			;

			set_time_limit(0);

			/**
			 * email
			 */
			$email = L8M_MailV2::factory('admin_newsletter', $formValues['language_short']);
			$email
				->setFrom($this->getOption('resources.mail.defaultFrom.email'), $this->getOption('resources.mail.defaultFrom.name'))
				->addTo($this->getOption('resources.mail.defaultReplyTo.email'))
			;

			$email->setSubject($newsletterModel->Translation[$formValues['language_short']]->title);

			$email->addBcc($formValues['email']);

			$emailContent = L8M_MailV2_Part_Content::factory('admin_newsletter_content', $email);
			$emailContent
				->setDynamicVar('FIRSTNAME', $formValues['firstname'])
				->setDynamicVar('LASTNAME', $formValues['lastname'])
				->setDynamicVar('SALUTATION', $salutationModel->Translation[$formValues['language_short']]->name)
				->setDynamicVar('COMPANY_NAME', PRJ_SiteConfig::getOption('company_name'))
				->setDynamicVar('COMPANY_ADDRESS', PRJ_SiteConfig::getOption('company_address'))
				->setDynamicVar('SERVICE_HOTLINE', PRJ_SiteConfig::getOption('service_hotline'))
				->setDynamicVar('UNSUBSCRIBE', L8M_Library::getSchemeAndHttpHost() . $this->_helper->url('unsubscribe', 'newsletter', 'default', array('email'=>$formValues['email'])))
			;
			$emailContent
				->setContent($newsletterModel->Translation[$formValues['language_short']]->content_plain, L8M_MailV2_Part::RENDER_TEXT)
				->setContent($newsletterModel->Translation[$formValues['language_short']]->content_html, L8M_MailV2_Part::RENDER_HTML)
			;
			$email->addPart($emailContent);

			if ($newsletterModel->media_id) {
				$emailAttachment = L8M_MailV2_Part_Attachment::factory('email_attachment', $email);
				$emailAttachment->addItem($newsletterModel->Media->file_name, $newsletterModel->Media->getStoredFilePath());
				$email->addPart($emailAttachment);
			}

			/**
			 * footer
			 */
			$content = L8M_MailV2_Part::factory('footer', $email);
			$email->addPart($content);

			/**
			 * send email
			 */
			try {
				$email->send();
				$this->view->emailIsSend = TRUE;
			} catch (L8M_Mail_Exception $exception) {
				$this->view->emailIsSend = FALSE;
			}
		} else {
			$this->view->form = $form;
		}
	}

	/**
	 * PDF action.
	 *
	 * @return void
	 */
	public function exportAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Export');

		/**
		 * this can go on for 5 minutes
		 */
		set_time_limit(300);

		/**
		 * start model list
		 */
		$this->_modelList->exportModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
		)));
	}
}