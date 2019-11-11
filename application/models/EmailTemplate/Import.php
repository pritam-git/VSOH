<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/EmailTemplate/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 404 2015-09-08 14:44:40Z nm $
 */

/**
 *
 *
 * Default_Model_EmailTemplate_Import
 *
 *
 */
class Default_Model_EmailTemplate_Import extends L8M_Doctrine_Import_Abstract
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
		$w = array();

		$w[] = array(
			'id'=>$i++,
			'short'=>'retrieve_password',
			'name'=>'retrieve_password',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Passwort vergessen - Bestätigungslink',
			'subject_fr'=>'Forgot the password - confirmation link',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'reset_password',
			'name'=>'reset_password',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihr neues Passwort',
			'subject_fr'=>'Forgot the password - confirmation link',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'register',
			'name'=>'register',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihre Registrierung - Bitte aktivieren sie ihren Account',
			'subject_fr'=>'Your Registration - Activate your account please',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'register_buy',
			'name'=>'register_buy',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihre Registrierung - Bitte aktivieren sie ihren Account',
			'subject_fr'=>'Your Registration - Activate your account please',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order-verify',
			'name'=>'order-verify',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Bestellungseingang',
			'subject_fr'=>'Order received',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order',
			'name'=>'order',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Bestellungseingang',
			'subject_fr'=>'Order received',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'contact',
			'name'=>'contact',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Das Kontaktformular wurde ausgefüllt',
			'subject_fr'=>'The contact form has been filled',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'admin_newsletter',
			'name'=>'admin_newsletter',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'{CONTENT}',
			'subject_fr'=>'{CONTENT}',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_confirm',
			'name'=>'order_confirm',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Bestätigung ihrer Bestellung',
			'subject_fr'=>'Confirmation of your order',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_cancel',
			'name'=>'order_cancel',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Storno Ihrer Bestellung',
			'subject_fr'=>'Cancellation of your order',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_payment',
			'name'=>'order_payment',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Zahlungseingang bestätigt',
			'subject_fr'=>'Confirmed receipt of payment',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_shipped',
			'name'=>'order_shipped',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihre Bestellung wurde verschickt',
			'subject_fr'=>'Your order has been shipped',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'account_disabled',
			'name'=>'account_disabled',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihr Benutzerkonto wurde deaktiviert',
			'subject_fr'=>'Your Account has been disabled',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'push_email',
			'name'=>'push_email',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Neuer Post ist angekommen',
			'subject_fr'=>'New Post has arrived',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'event_registration',
			'name'=>'event_registration',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Veranstaltungsanmeldung',
			'subject_fr'=>'Inscription à l\'événement',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'change_event_registration_details',
			'name'=>'change_event_registration_details',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihre Änderung zur Veranstaltungsanmeldung',
			'subject_fr'=>'FR[Ihre Änderung zur Veranstaltungsanmeldung]',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'event_unregistration',
			'name'=>'event_unregistration',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Ihre Abmeldung von der Veranstaltung',
			'subject_fr'=>'FR[Ihre Abmeldung von der Veranstaltung]',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'membership_request',
			'name'=>'membership_request',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Mitgliedschaftsanfrage',
			'subject_fr'=>'Membership request',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'user_edit_request',
			'name'=>'user_edit_request',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Benutzeranfrage bearbeiten',
			'subject_fr'=>'Edit user request',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'request_sent',
			'name'=>'request_sent',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Gesendete Anfrage bearbeiten',
			'subject_fr'=>'Edit request sent',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'request_accepted',
			'name'=>'request_accepted',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Editieranfrage akzeptiert',
			'subject_fr'=>'Edit request accepted',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'request_rejected',
			'name'=>'request_rejected',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Anfrage wurde abgelehnt',
			'subject_fr'=>'Editing request rejected',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'create_subuser',
			'name'=>'create_subuser',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Login-Daten',
			'subject_fr'=>'Login details',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'user_credentials',
			'name'=>'user_credentials',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>'Login-Daten',
			'subject_en'=>'Login details',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_en'=>'{CONTENT}',
			'content_html_en'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'survey_email',
			'name'=>'survey_email',
			'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
			'html_paragraph_css_style'=>'font-size:11px; margin-bottom:4px; margin-top:0px;',
			'html_headline_css_style'=>'font-size:13px; margin-top:10px; margin-bottom:4px;',
			'html_data_css_style'=>'font-size:11px; margin:0px; padding:0px; border:0px;',
			'html_dataline_label_css_style'=>'font-size:11px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
			'html_dataline_data_css_style'=>'font-size:11px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
			'subject_de'=>NULL,
			'subject_fr'=>NULL,
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

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