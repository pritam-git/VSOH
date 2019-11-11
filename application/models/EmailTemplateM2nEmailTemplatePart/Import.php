<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/EmailTemplateM2nEmailTemplatePart/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 404 2015-09-08 14:44:40Z nm $
 */

/**
 *
 *
 * Default_Model_EmailTemplateM2nEmailTemplatePart_Import
 *
 *
 */
class Default_Model_EmailTemplateM2nEmailTemplatePart_Import extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	protected $_standsForClass = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		/**
		 * pass through to prevent failures
		 */
		parent::_init();

		/**
		 * retrieve class name
		 */
		$this->_retriveStandsForClassName();
		$modelName = $this->_standsForClass;

		/**
		 * retrieve last model
		 */
		$model = Doctrine_Query::create()
			->from($modelName . ' m')
			->limit(1)
			->orderBy('m.id DESC')
			->execute()
			->getFirst()
		;
		if ($model) {
			$i = $model->id + 1;
		} else {
			$i = 1;
		}

		$emailTemplateConfigArray = array(
			'retrieve_password'=>array(
				'header',
				'retrieve_password',
				'footer',
			),
			'reset_password'=>array(
				'header',
				'reset_password',
				'footer',
			),
			'register'=>array(
				'header',
				'register',
				'footer',
			),
			'register_buy'=>array(
				'header',
				'register_buy',
				'footer',
			),
			'order'=>array(
				'header',
				'order',
				'order_end',
				'footer',
			),
			'order-verify'=>array(
				'header',
				'order-verify',
				'order_end',
				'footer',
			),
			'contact'=>array(
				'header',
				'contact',
				'footer',
			),
			'admin_newsletter'=>array(
				'header',
				'admin_newsletter_content',
				'footer',
			),
			'order_confirm'=>array(
				'header',
				'order_confirm',
				'order_end',
				'footer',
			),
			'order_cancel'=>array(
				'header',
				'order_cancel',
				'order_end',
				'footer',
			),
			'order_payment'=>array(
				'header',
				'order_payment',
				'order_end',
				'footer',
			),
			'order_shipped'=>array(
				'header',
				'order_shipped',
				'order_end',
				'footer',
			),
			'account_disabled'=>array(
				'header',
				'account_disabled',
				'footer',
			),
			'push_email'=>array(
				'header',
				'push_email',
				'footer',
			),
			'event_registration'=>array(
				'header',
				'event_registration',
				'footer',
				'event_registration_attachment',
			),
			'change_event_registration_details'=>array(
				'header',
				'change_event_registration_details',
				'footer',
				'change_event_registration_details_attachment',
			),
			'event_unregistration'=>array(
				'header',
				'event_unregistration',
				'footer',
			),
			'membership_request'=>array(
				'header',
				'membership_request',
				'footer',
			),
			'user_edit_request'=>array(
				'header',
				'user_edit_request',
				'footer',
			),
			'request_sent'=>array(
				'header',
				'request_sent',
				'footer',
			),
			'request_accepted'=>array(
				'header',
				'request_accepted',
				'footer',
			),
			'request_rejected'=>array(
				'header',
				'request_rejected',
				'footer',
			),
			'create_subuser'=>array(
				'header',
				'create_subuser',
				'footer',
			),
			'user_credentials'=>array(
				'header',
				'user_credentials',
				'footer',
			),
		);

		$w = array();

		$tmpEmailTemplates = array();
		$tmpEmailTemplateParts = array();
		foreach ($emailTemplateConfigArray as $emailTemplate => $emailTemplateParts) {
			if (!array_key_exists($emailTemplate, $tmpEmailTemplates)) {
				$emailTemplateModel = Doctrine_Query::create()
					->from('Default_Model_EmailTemplate m')
					->addWhere('m.short = ? ', array($emailTemplate))
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$emailTemplateModel) {
					throw new L8M_Exception('EmailTemplate missing: ' . $emailTemplate);
				} else {
					$tmpEmailTemplates[$emailTemplate] = $emailTemplateModel->id;
				}
			}

			foreach ($emailTemplateParts as $emailTemplatePart) {
				if (!array_key_exists($emailTemplatePart, $tmpEmailTemplateParts)) {
					$emailTemplatePartModel = Doctrine_Query::create()
						->from('Default_Model_EmailTemplatePart m')
						->addWhere('m.short = ? ', array($emailTemplatePart))
						->limit(1)
						->execute()
						->getFirst()
					;

					if (!$emailTemplatePartModel) {
						throw new L8M_Exception('EmailTemplate missing: ' . $emailTemplatePart);
					} else {
						$tmpEmailTemplateParts[$emailTemplatePart] = $emailTemplatePartModel->id;
					}
				}
				$w[] = array(
					'id'=>$i++,
					'email_template_id'=>$tmpEmailTemplates[$emailTemplate],
					'email_template_part_id'=>$tmpEmailTemplateParts[$emailTemplatePart],
				);
			}
		}

		$this->setArray($w);
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		/**
		 * retrieve class name
		 */
		$modelName = $this->_standsForClass;

		/**
		 * check whether translatable or not
		 */
		$model = new $modelName();
		$modelRelations = $model->getTable()->getRelations();
		if (array_key_exists('Translation', $modelRelations)) {
			$transCols = $model->Translation->getTable()->getColumns();
			$transLangs = L8M_Locale::getSupported(TRUE);
			$translateable = TRUE;
		} else {
			$translateable = FALSE;
		}

		/**
		 * add data to collection
		 */
		$this->_dataCollection = new Doctrine_Collection($modelName);
		foreach($this->_data as $data) {
			$model = new $modelName();
			$model->merge($data);

			/**
			 * add translatables
			 */
			if ($translateable) {
				foreach ($transCols as $transCol => $colDefinition) {
					if ($transCol != 'id' &&
						$transCol != 'lang' &&
						$transCol != 'created_at' &&
						$transCol != 'updated_at' &&
						$transCol != 'deleted_at') {

						foreach ($transLangs as $transLang) {
							if (array_key_exists($transCol . '_' . $transLang, $data)) {
								$model->Translation[$transLang]->$transCol = $data[$transCol . '_' . $transLang];
							}
						}
					}
				}
			}

			/**
			 * just add data
			 */
			$this->_dataCollection->add($model, $data['id']);
		}
	}

	/**
	 * Retrieve stands for class name.
	 *
	 * @return void
	 */
	protected function _retriveStandsForClassName()
	{
		$name = get_class($this);
		$this->_standsForClass = substr($name, 0, strlen($name) - strlen('_Import'));
	}
}