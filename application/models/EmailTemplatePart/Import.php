<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/EmailTemplatePart/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 404 2015-09-08 14:44:40Z nm $
 */

/**
 *
 *
 * Default_Model_EmailTemplatePart_Import
 *
 *
 */
class Default_Model_EmailTemplatePart_Import extends L8M_Doctrine_Import_Abstract
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
			'content_plain_de'=>'{CONTENT}

Sie haben eine Anfrage zum Zurücksetzen Ihres Passwortes geschickt.

Wenn Sie dies wirklich tun möchten, klicken Sie auf den folgenden Link:

{LINK}',
			'content_html_de'=>'{CONTENT}
<p>Sie haben eine Anfrage zum Zurücksetzen Ihres Passwortes geschickt.<br />
Wenn Sie dies wirklich tun möchten, klicken Sie auf den folgenden Link:</p>
<p><a href="{LINK}">{LINK}</a></p>',
			'content_plain_fr'=>'{CONTENT}

You requested to reset your password.
If it is what you really want to do so follow the link below:

{LINK}',
			'content_html_fr'=>'{CONTENT}
<p>You requested to reset your password.<br />
If it is what you really want to do so follow the link below:</p>
<p><a href="{LINK}">{LINK}</a></p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'reset_password',
			'name'=>'reset_password',
			'content_plain_de'=>'{CONTENT}
Sie haben eine Anfrage zum Zurücksetzen Ihres Passwortes geschickt.

Ihr neues Passwort lautet: {PASSWORD}',
			'content_html_de'=>'{CONTENT}
<p>Sie haben eine Anfrage zum Zurücksetzen Ihres Passwortes geschickt.</p>
<p>Ihr neues Passwortet lautet: {PASSWORD}</p>',
			'content_plain_fr'=>'{CONTENT}
You requested to reset your password.

Your new password is: {PASSWORD}',
			'content_html_fr'=>'{CONTENT}
<p>You requested to reset your password.</p>
<p>Your new password is: {PASSWORD}</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'register',
			'name'=>'register',
			'content_plain_de'=>'{CONTENT}
Vielen Dank für Ihre Registrierung.

Um Ihren Account vollständig zu aktivieren, klicken Sie bitte auf den unten stehenden Link (oder kopieren Sie den Link in die Adresszeile eines Internetbrowsers ihrer Wahl):

{ACTIVATIONCODE}

Wenn Sie sich nicht selber registriert haben, können Sie diese E-Mail ignorieren - Die Registrierung verfällt bei Nichtaktivierung automatisch.',
			'content_html_de'=>'{CONTENT}
<p>Vielen Dank für Ihre Registrierung.</p>
<p>Um Ihren Account vollständig zu aktivieren, klicken Sie bitte auf den unten stehenden Link (oder kopieren Sie den Link in die Adresszeile eines Internetbrowsers ihrer Wahl):</p>
<a href="{ACTIVATIONCODE}">{ACTIVATIONCODE}</a>
<p>Wenn Sie sich nicht selber registriert haben, können Sie diese E-Mail ignorieren - Die Registrierung verfällt bei Nichtaktivierung automatisch.</p>',
			'content_plain_fr'=>'{CONTENT}
Thank you for registering.

To ensure that your account can be fully activated, click on the link below (or alternatively copy it into the address bar of the browser of your choice):

{ACTIVATIONCODE}

If you have not raised the registry is nothing more necessary at this point - they will expire automatically.',
			'content_html_fr'=>'{CONTENT}
<p>Thank you for registering.</p>
<p>To ensure that your account can be fully activated, click on the link below (or alternatively copy it into the address bar of the browser of your choice):</p>
<a href="{ACTIVATIONCODE}">{ACTIVATIONCODE}</a>
<p>If you have not raised the registry is nothing more necessary at this point - they will expire automatically.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'register_buy',
			'name'=>'register_buy',
			'content_plain_de'=>'{CONTENT}
herzlich willkommen in unserem Shop. Um sich bei Ihrem nächsten Besuch in unserem Shop einzuloggen, klicken sie einfach Login oder Mein Benutzerkonto im oberen Bereich jeder Seite und geben Sie ihr E-Mail-Adresse und Passwort ein.

Mit folgenden Zugangsdaten können Sie sich anmelden:
E-Mail: {CUSTOMER_EMAIL}
Passwort: {PASSWORD}

Nach erfolgreichem Login auf unsere Seite haben Sie die folgenden Möglichkeiten:
- Schnellerer Bezahlvorgang beim nächsten Einkauf
- Alle laufenden Bestellungen im Auge behalten
- Verlauf der vergangenen Bestellungen
- Verwalten der Kundenkonto-Einstellungen
- Ihr Passwort zu ändern
- Verschiedene Adressen für den Versand an Familienmitglieder und Freunde sowie Rechnungsadressen zu speichern

Um Ihren Account vollständig zu aktivieren, klicken Sie bitte auf den unten stehenden Link (oder kopieren Sie den Link in die Adresszeile eines Internetbrowsers ihrer Wahl):

{ACTIVATIONCODE}

Wenn Sie sich nicht selber registriert haben, können Sie diese E-Mail ignorieren - Die Registrierung verfällt bei Nichtaktivierung automatisch.

Mit freundlichen Grüßen

Ihr {COMPANY_NAME} Team


--------------------------------------------------------------------------------------------
{COMPANY_NAME}
{COMPANY_ADDRESS}
{SERVICE_HOTLINE}',
			'content_html_de'=>'{CONTENT}
<p>herzlich willkommen in unserem Shop. Um sich bei Ihrem nächsten Besuch in unserem Shop einzuloggen, klicken sie einfach Login oder Mein Benutzerkonto im oberen Bereich jeder Seite und geben Sie ihr E-Mail-Adresse und Passwort ein.</p>
<p>
Mit folgenden Zugangsdaten können Sie sich anmelden:<br />
E-Mail: {CUSTOMER_EMAIL}<br />
Passwort: {PASSWORD}
</p>
<p>Nach erfolgreichem Login auf unsere Seite haben Sie die folgenden Möglichkeiten:</p>
<ul>
	<li>Schnellerer Bezahlvorgang beim nächsten Einkauf</li>
	<li>Alle laufenden Bestellungen im Auge behalten</li>
	<li>Verlauf der vergangenen Bestellungen</li>
	<li>Verwalten der Kundenkonto-Einstellungen</li>
	<li>Ihr Passwort zu ändern</li>
	<li>Verschiedene Adressen für den Versand an Familienmitglieder und Freunde sowie Rechnungsadressen zu speichern</li>
</ul>
<p>Um Ihren Account vollständig zu aktivieren, klicken Sie bitte auf den unten stehenden Link (oder kopieren Sie den Link in die Adresszeile eines Internetbrowsers ihrer Wahl):</p>
<a href="{ACTIVATIONCODE}">{ACTIVATIONCODE}</a>
<p>Wenn Sie sich nicht selber registriert haben, können Sie diese E-Mail ignorieren - Die Registrierung verfällt bei Nichtaktivierung automatisch.</p>',
			'content_plain_fr'=>'{CONTENT}
welcome to our shop. To log on your next visit in our shop, just click Login or My Account at the top of every page and enter your e-mail address and password.

With the following access data you can login:
Email: {CUSTOMER_EMAIL}
Password: {PASSWORD}

After successful login in to our site you have the following options:
- Faster checkout process the next time you purchase
- Keep an eye on all current orders
- History of past orders
- Managing the Account Settings
- To change your password
- To store several addresses for sending to family members and friends as well as billing addresses

To ensure that your account can be fully activated, click on the link below (or alternatively copy it into the address bar of the browser of your choice):

{ACTIVATIONCODE}

If you have not raised the registry is nothing more necessary at this point - they will expire automatically.',
			'content_html_fr'=>'{CONTENT}
<p>welcome to our shop. To log on your next visit in our shop, just click Login or My Account at the top of every page and enter your e-mail address and password.</p>
<p>With the following access data you can login:<br />
Email: {CUSTOMER_EMAIL}<br />
Password: {PASSWORD}
</p>
<p>After successful login in to our site you have the following options:</p>
<ul>
	<li>Faster checkout process the next time you purchase</li>
	<li>Keep an eye on all current orders</li>
	<li>History of past orders</li>
	<li>Managing the Account Settings</li>
	<li>To change your password</li>
	<li>To store several addresses for sending to family members and friends as well as billing addresses</li>
</ul>
<p>To ensure that your account can be fully activated, click on the link below (or alternatively copy it into the address bar of the browser of your choice):</p>
<a href="{ACTIVATIONCODE}">{ACTIVATIONCODE}</a>
<p>If you have not raised the registry is nothing more necessary at this point - they will expire automatically.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'contact',
			'name'=>'contact',
			'content_plain_de'=>'{CONTENT}
Sie haben am {CURRENT_DATE} um {CURRENT_TIME} eine Anfrage über das Kontaktformular erhalten.',
			'content_html_de'=>'{CONTENT}
<p>Sie haben am {CURRENT_DATE} um {CURRENT_TIME} eine Anfrage über das Kontaktformular erhalten.</p>',
			'content_plain_fr'=>'{CONTENT}
You have received a request via the contact form on {CURRENT_DATE} at {CURRENT_TIME}',
			'content_html_fr'=>'{CONTENT}
<p>You have received a request via the contact form on {CURRENT_DATE} at {CURRENT_TIME}</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'admin_newsletter_content',
			'name'=>'admin_newsletter_content',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order',
			'name'=>'order',
			'content_plain_de'=>'{CONTENT}
wir haben Ihre Bestellung bei {COMPANY_NAME} überprüft und bestätigen diese hiermit.
Nachfolgend finden Sie Ihre Bestelldaten, vielen Dank!
Ihre Bestellnr.: {ORDER_ID}

{ORDER_CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">wir haben Ihre Bestellung bei {COMPANY_NAME} überprüft und bestätigen diese hiermit.</p>
<p style="margin:0px; margin-bottom:15px">Nachfolgend finden Sie Ihre Bestelldaten, vielen Dank!</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Ihre Bestellnr.: {ORDER_ID}</p>
{ORDER_CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
We\'ve checked your order at {COMPANY_NAME} and confirm herewith.
Below you will find your order confirmation, thanks.
Your Order ID: {ORDER_ID}

{ORDER_CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">We\'ve checked your order at {COMPANY_NAME} and confirm herewith.</p>
<p style="margin:0px; margin-bottom:15px">Below you will find your order confirmation, thanks.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Your Order ID: {ORDER_ID}</p>
{ORDER_CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order-verify',
			'name'=>'order-verify',
			'content_plain_de'=>'{CONTENT}
Vielen Dank für Ihre Bestellung bei {COMPANY_NAME}. Nachdem Ihre Bestellung überprüft wurde, erhalten sie per E-Mail eine Bestätigung mit weiteren Informationen.
Nachfolgend finden Sie Ihre Bestelldaten, vielen Dank!
Ihre Bestellnr.: {ORDER_ID}

{ORDER_CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Vielen Dank für Ihre Bestellung bei {COMPANY_NAME}. Nachdem Ihre Bestellung überprüft wurde, erhalten sie per E-Mail eine Bestätigung mit weiteren Informationen.</p>
<p style="margin:0px; margin-bottom:15px">Nachfolgend finden Sie Ihre Bestelldaten, vielen Dank!</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Ihre Bestellnr.: {ORDER_ID}</p>
{ORDER_CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
Thank you very much for your order at {COMPANY_NAME}. Once the order has been verified, you will receive a confirmation email with further information.
Below you will find your order confirmation, thanks.
Your Order ID: {ORDER_ID}

{ORDER_CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Thank you very much for your order at {COMPANY_NAME}. Once the order has been verified, you will receive a confirmation email with further information.</p>
<p style="margin:0px; margin-bottom:15px">Below you will find your order confirmation, thanks.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Your Order ID: {ORDER_ID}</p>
{ORDER_CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_end',
			'name'=>'order_end',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_shipped',
			'name'=>'order_shipped',
			'content_plain_de'=>'{CONTENT}
Ihre Bestellung wurde verschickt.',
			'content_html_de'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Ihre Bestellung wurde verschickt.</p>',
			'content_plain_fr'=>'{CONTENT}
Your order has been shipped.',
			'content_html_fr'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Your order has been shipped.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_payment',
			'name'=>'order_payment',
			'content_plain_de'=>'{CONTENT}
Wir haben ihre Zahlungen erhalten. Ihre Bestellung wird in Kürze versandt, Sie werden dazu von uns benachrichtigt.
Ihre Bestellnr.: {ORDER_ID}',
			'content_html_de'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Wir haben Ihre Zahlungen erhalten. Ihre Bestellung wird in Kürze versandt, Sie werden dazu von uns benachrichtigt.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Ihre Bestellnr.: {ORDER_ID}</p>',
			'content_plain_fr'=>'{CONTENT}
We have received your payment. Your order will be sent shortly, you are notified to do so by us.
Your Order ID: {ORDER_ID}',
			'content_html_fr'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">We have received your payment. Your order will be sent shortly, you are notified to do so by us.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Your Order ID: {ORDER_ID}</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'footer',
			'name'=>'footer',
			'content_plain_de'=>'{CONTENT}
Haben Sie Fragen? Wir sind für Sie da per Telefon: {SERVICE_HOTLINE} order E-Mail: {EMAIL}
----------------------------------------
{COMPANY_NAME}
-----------------------------------------
{COMPANY_ADDRESS}

Unsere Nutzungsbedingungen finden Sie {AGB_LINK}
Unsere Datenschutz finden Sie unter {PRIVACY_LINK}',
			'content_html_de'=>'{CONTENT}
<p style="margin-top:30px;">Haben Sie Fragen? Wir sind für Sie da per Telefon: {SERVICE_HOTLINE}, E-Mail: <a href="mailto:{EMAIL}" style="color: #b8cb00">{EMAIL}</a></p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px;">{COMPANY_NAME}</p>
<p style="margin:0px;">{COMPANY_ADDRESS}</p>
<p style="margin:0px;">Unsere Nutzungsbedingungen finden Sie <a href="{AGB_LINK}" style="color: #b8cb00">hier</a></p>
<p style="margin:0px;margin-bottom:15px;">Unsere Datenschutz finden Sie <a href="{PRIVACY_LINK}" style="color: #b8cb00">hier</a></p>
</div>',
			'content_plain_fr'=>'{CONTENT}
Do You Have Questions? We are here for you by phone: {SERVICE_HOTLINE} order E-Mail: {EMAIL}
----------------------------------------
{COMPANY_NAME}
-----------------------------------------
{COMPANY_ADDRESS}

Our terms of service can be found {AGB_LINK}
Our privacy policy is available at {PRIVACY_LINK}',
			'content_html_fr'=>'{CONTENT}
<p style="margin-top:30px;">Do You Have Questions? We are here for you by phone: {SERVICE_HOTLINE}, E-Mail: <a href="mailto:{EMAIL}" style="color: #b8cb00">{EMAIL}</a></p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px;">{COMPANY_NAME}</p>
<p style="margin:0px;">{COMPANY_ADDRESS}</p>
<p style="margin:0px;">Our terms of service can be found <a href="{AGB_LINK}" style="color: #b8cb00">hier</a></p>
<p style="margin:0px;margin-bottom:15px;">Our privacy policy is available at <a href="{PRIVACY_LINK}" style="color: #b8cb00">hier</a></p>
</div>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_confirm',
			'name'=>'order_confirm',
			'content_plain_de'=>'{CONTENT}
Vielen Dank für Ihre Bestellung bei {COMPANY_NAME}. Sobald Ihre Sendung verschickt wurde, erhalten Sie per E-Mail weitere Informationen zur Sendungsverfolgung. Sie können den aktuellen Status Ihrer Bestellung jederzeit in Ihrem Kundenbereich einsehen.

Bitte nehmen Sie unter dem Link {PAYMENT_LINK} jetzt ihre Bezahlung vor.

Nachfolgend finden Sie Ihre Bestellbestätigung, vielen Dank.

{ORDER_CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Vielen Dank für Ihre Bestellung bei {COMPANY_NAME}. Sobald Ihre Sendung verschickt wurde, erhalten Sie per E-Mail weitere Informationen zur Sendungsverfolgung. Sie können den aktuellen Status Ihrer Bestellung jederzeit <a href="{ACCOUNT_LINK}">in Ihrem Kundenbereich</a> einsehen.</p>
<p style="margin:0px; margin-bottom:15px">Bitte nehmen Sie unter dem Link <a href="{PAYMENT_LINK}">{PAYMENT_LINK}</a> jetzt ihre Bezahlung vor.</p>
<p style="margin:0px; margin-bottom:15px">Nachfolgend finden Sie Ihre Bestellbestätigung, vielen Dank.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Ihre Bestellnr.: {ORDER_ID}  - bestellt am {ORDERDATE} um {ORDERTIME}</p>
{ORDER_CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
Thank you very much for your order at {COMPANY_NAME}. Once your shipment has been sent, you will get more tracking information by email. You can view the current status of your order at any time in your account.

Please make now your payment under {PAYMENT_LINK}.

Below you will find your order confirmation, thanks.

Your Order ID: {ORDER_ID}  - ordered on {ORDERDATE} at {ORDERTIME}

{ORDER_CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Thank you very much for your order at {COMPANY_NAME}. Once your shipment has been sent, you will get more tracking information by email. You can view the current status of your order at any time <a href="{ACCOUNT_LINK}">in your account.</a></p>
<p style="margin:0px; margin-bottom:15px">Please make now your payment under <a href="{PAYMENT_LINK}">{PAYMENT_LINK}</a>.</p>
<p style="margin:0px; margin-bottom:15px">Below you will find your order confirmation, thanks.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Your Order ID: {ORDER_ID}  - ordered on {ORDERDATE} at {ORDERTIME}</p>
{ORDER_CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'order_cancel',
			'name'=>'order_cancel',
			'content_plain_de'=>'{CONTENT}
Wir haben Ihre Bestellung bei {COMPANY_NAME} storniert. Sie können den aktuellen Status Ihrer Bestellung jederzeit in Ihrem Kundenbereich einsehen.

Nachfolgend finden Sie Ihre Bestelldetails, vielen Dank.

{ORDER_CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">Wir haben Ihre Bestellung bei {COMPANY_NAME} storniert. Sie können den aktuellen Status Ihrer Bestellung jederzeit <a href="{ACCOUNT_LINK}">in Ihrem Kundenbereich</a> einsehen.</p>
<p style="margin:0px; margin-bottom:15px">Nachfolgend finden Sie Ihre Bestelldetails, vielen Dank.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Ihre Bestellnr.: {ORDER_ID}  - bestellt am {ORDERDATE} um {ORDERTIME}</p>
{ORDER_CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
We cancelled your order at {COMPANY_NAME}. You can view the current status of your order at any time in your account.

Below you will find your order information, thanks.

Your Order ID: {ORDER_ID}  - ordered on {ORDERDATE} at {ORDERTIME}

{ORDER_CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p style="margin:0px; margin-bottom:15px">We cancelled your order at {COMPANY_NAME}. You can view the current status of your order at any time <a href="{ACCOUNT_LINK}">in your account.</a></p>
<p style="margin:0px; margin-bottom:15px">Below you will find your order information, thanks.</p>
<p style="margin:0px; font-weight:bold; margin-bottom:15px">Your Order ID: {ORDER_ID}  - ordered on {ORDERDATE} at {ORDERTIME}</p>
{ORDER_CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'header',
			'name'=>'header',
			'content_plain_de'=>'{CONTENT}
Hallo {FIRSTNAME} {LASTNAME},',
			'content_html_de'=>'{CONTENT}
<div class="order" style="width:650px; background-color:#ffffff; font-family:arial; margin:auto; font-size:11px; color:#000000;">
{HEADER}
<p style="font-weight:bold">Hallo {FIRSTNAME} {LASTNAME}</p>',
			'content_plain_fr'=>'{CONTENT}
Hello {FIRSTNAME} {LASTNAME},',
			'content_html_fr'=>'{CONTENT}
<div class="order" style="width:650px; background-color:#ffffff; font-family:arial; margin:auto; font-size:11px; color:#000000;">
{HEADER}
<p style="font-weight:bold">Hello {FIRSTNAME} {LASTNAME}</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'account_disabled',
			'name'=>'account_disabled',
			'content_plain_de'=>'{CONTENT}
Ihr Benutzerkonto wurde aus Sicherheitsgründen deaktiviert.
Sie können das Benutzerkonto mit dem Link {ENABLE_ACCOUNT} wieder freischalten.',
			'content_html_de'=>'{CONTENT}
<p>Ihr Benutzerkonto wurde aus Sicherheitsgründen deaktiviert.</p>
<p>Wenn Sie das Benutzerkonto wieder freischalten möchten, klicken Sie bitte <a href="{ENABLE_ACCOUNT}">hier</a>.</p>',
			'content_plain_fr'=>'{CONTENT}
Your account has been disabled for security reasons.
You can unlock your account by using the link {ENABLE_ACCOUNT}.',
			'content_html_fr'=>'{CONTENT}
<p>Your account has been disabled for security reasons.</p>
<p>If you want to unlock your account, click <a href="{ENABLE_ACCOUNT}">here</a>.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'push_email',
			'name'=>'push_email',
			'content_plain_de'=>'{CONTENT}
Es gibt einen neuen Beitrag unter:
{REDIRECT_URL}',
			'content_html_de'=>'{CONTENT}
<p style="margin-bottom:0">Es gibt einen neuen Beitrag unter:</p>
<p style="margin:0"><a href="{REDIRECT_URL}" target="_blank">{REDIRECT_URL}</a></p>',
			'content_plain_fr'=>'{CONTENT}
There is a new post under:
{REDIRECT_URL}',
			'content_html_fr'=>'{CONTENT}
<p style="margin-bottom:0">There is a new post under:</p>
<p style="margin:0"><a href="{REDIRECT_URL}" target="_blank">{REDIRECT_URL}</a></p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'event_registration',
			'name'=>'event_registration',
			'content_plain_de'=>'{CONTENT}
Eine Neuanmeldung für eine Veranstaltung. Die Registrierungsdetails sind wie folgt,

{CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p>Eine Neuanmeldung für eine Veranstaltung. Die Registrierungsdetails sind wie folgt,<p/>
{CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
Une nouvelle inscription à un événement. Les détails de l\'inscription sont les suivants,

{CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p>Une nouvelle inscription à un événement. Les détails de l\'inscription sont les suivants,<p/>
{CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'event_registration_attachment',
			'name'=>'event_registration_attachment',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'change_event_registration_details',
			'name'=>'change_event_registration_details',
			'content_plain_de'=>'{CONTENT}
Eine Änderung für eine Anmeldung zu einer Veranstaltung. Die Registrierungsdetails sind wie folgt,

{CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p>Eine Änderung für eine Anmeldung zu einer Veranstaltung. Die Registrierungsdetails sind wie folgt,<p/>
{CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
FR[Eine Änderung für eine Anmeldung zu einer Veranstaltung. Die Registrierungsdetails sind wie folgt,]

{CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p>FR[Eine Änderung für eine Anmeldung zu einer Veranstaltung. Die Registrierungsdetails sind wie folgt,]<p/>
{CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'change_event_registration_details_attachment',
			'name'=>'change_event_registration_details_attachment',
			'content_plain_de'=>'{CONTENT}',
			'content_html_de'=>'{CONTENT}',
			'content_plain_fr'=>'{CONTENT}',
			'content_html_fr'=>'{CONTENT}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'event_unregistration',
			'name'=>'event_unregistration',
			'content_plain_de'=>'{CONTENT}
Eine Abmeldung von einer Veranstaltung. Die Registrierungsdetails waren wie folgt,

{CONTENT_PLAIN}',
			'content_html_de'=>'{CONTENT}
<p>Eine Abmeldung von einer Veranstaltung. Die Registrierungsdetails waren wie folgt,<p/>
{CONTENT_HTML}',
			'content_plain_fr'=>'{CONTENT}
FR[Eine Abmeldung von einer Veranstaltung. Die Registrierungsdetails waren wie folgt,]

{CONTENT_PLAIN}',
			'content_html_fr'=>'{CONTENT}
<p>FR[Eine Abmeldung von einer Veranstaltung. Die Registrierungsdetails waren wie folgt,]<p/>
{CONTENT_HTML}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'membership_request',
			'name'=>'membership_request',
			'content_plain_de'=>'{CONTENT}
Es gibt eine neue Mitgliedsschiffsanfrage:

Benutzername: {USER_NAME}
Email: {USER_EMAIL}',
			'content_html_de'=>'{CONTENT}
<p>Es gibt eine neue Mitgliedsschiffsanfrage:</p>
<p style="margin: 0;"><span style="font-weight: bold;">Benutzername:</span> {USER_NAME}</p>
<p style="margin: 0;"><span style="font-weight: bold;">Email: </span>{USER_EMAIL}</p>',
			'content_plain_fr'=>'{CONTENT}
There is a new membership request:

Username: {USER_NAME}
Email: {USER_EMAIL}',
			'content_html_fr'=>'{CONTENT}
<p>There is a new membership request:</p>
<p style="margin: 0;"><span style="font-weight: bold;">Username:</span> {USER_NAME}</p>
<p style="margin: 0;"><span style="font-weight: bold;">Email: </span>{USER_EMAIL}</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'user_edit_request',
			'name'=>'user_edit_request',
			'content_plain_de'=>'{CONTENT}
Es gibt eine neue Profilanforderung vom Benutzer:

CH_CODE: {CH_CODE}
CADC: {CADC}
EMAIL: {EMAIL}
SALUTATION_ID: {SALUTATION_NAME} (ID:{SALUTATION_ID})
FIRSTNAME: {FIRSTNAME}
LASTNAME: {LASTNAME}
STREET: {STREET}
ZIP: {ZIP}
CITY: {CITY}
ADDRESS_LINE_1: {ADDRESS_LINE_1}
PHONE: {PHONE}
FAX: {FAX}
WWW: {WWW}
COMPANY: {COMPANY}
GL: {GL}',
			'content_plain_fr'=>'{CONTENT}
There is a new edit profile request from user:

CH_CODE: {CH_CODE}
CADC: {CADC}
EMAIL: {EMAIL}
SALUTATION_ID: {SALUTATION_NAME} (ID:{SALUTATION_ID})
FIRSTNAME: {FIRSTNAME}
LASTNAME: {LASTNAME}
STREET: {STREET}
ZIP: {ZIP}
CITY: {CITY}
ADDRESS_LINE_1: {ADDRESS_LINE_1}
PHONE: {PHONE}
FAX: {FAX}
WWW: {WWW}
COMPANY: {COMPANY}
GL: {GL}',
			'content_html_de'=>'{CONTENT}
<p>Es gibt eine neue Profilanforderung vom Benutzer:</p>
<p style="margin: 0;"><span style="font-weight: bold;">CH_CODE: </span>{CH_CODE}</p>
<p style="margin: 0;"><span style="font-weight: bold;">CADC: </span>{CADC}</p>
<p style="margin: 0;"><span style="font-weight: bold;">EMAIL: </span>{USER_EMAIL}</p>
<p style="margin: 0;"><span style="font-weight: bold;">SALUTATION_ID: </span>{SALUTATION_NAME} (ID:{SALUTATION_ID})</p>
<p style="margin: 0;"><span style="font-weight: bold;">FIRSTNAME: </span>{FIRSTNAME}</p>
<p style="margin: 0;"><span style="font-weight: bold;">LASTNAME: </span>{LASTNAME}</p>
<p style="margin: 0;"><span style="font-weight: bold;">STREET: </span>{STREET}</p>
<p style="margin: 0;"><span style="font-weight: bold;">ZIP: </span>{ZIP}</p>
<p style="margin: 0;"><span style="font-weight: bold;">CITY: </span>{CITY}</p>
<p style="margin: 0;"><span style="font-weight: bold;">ADDRESS_LINE_1: </span>{ADDRESS_LINE_1}</p>
<p style="margin: 0;"><span style="font-weight: bold;">PHONE: </span>{PHONE}</p>
<p style="margin: 0;"><span style="font-weight: bold;">FAX: </span>{FAX}</p>
<p style="margin: 0;"><span style="font-weight: bold;">WWW: </span>{WWW}</p>
<p style="margin: 0;"><span style="font-weight: bold;">COMPANY: </span>{COMPANY}</p>
<p style="margin: 0;"><span style="font-weight: bold;">GL: </span>{GL}</p>',
			'content_html_fr'=>'{CONTENT}
<p>There is a new edit profile request from user:</p>
<p style="margin: 0;"><span style="font-weight: bold;">CH_CODE: </span>{CH_CODE}</p>
<p style="margin: 0;"><span style="font-weight: bold;">CADC: </span>{CADC}</p>
<p style="margin: 0;"><span style="font-weight: bold;">EMAIL: </span>{USER_EMAIL}</p>
<p style="margin: 0;"><span style="font-weight: bold;">SALUTATION_ID: </span>{SALUTATION_NAME} (ID:{SALUTATION_ID})</p>
<p style="margin: 0;"><span style="font-weight: bold;">FIRSTNAME: </span>{FIRSTNAME}</p>
<p style="margin: 0;"><span style="font-weight: bold;">LASTNAME: </span>{LASTNAME}</p>
<p style="margin: 0;"><span style="font-weight: bold;">STREET: </span>{STREET}</p>
<p style="margin: 0;"><span style="font-weight: bold;">ZIP: </span>{ZIP}</p>
<p style="margin: 0;"><span style="font-weight: bold;">CITY: </span>{CITY}</p>
<p style="margin: 0;"><span style="font-weight: bold;">ADDRESS_LINE_1: </span>{ADDRESS_LINE_1}</p>
<p style="margin: 0;"><span style="font-weight: bold;">PHONE: </span>{PHONE}</p>
<p style="margin: 0;"><span style="font-weight: bold;">FAX: </span>{FAX}</p>
<p style="margin: 0;"><span style="font-weight: bold;">WWW: </span>{WWW}</p>
<p style="margin: 0;"><span style="font-weight: bold;">COMPANY: </span>{COMPANY}</p>
<p style="margin: 0;"><span style="font-weight: bold;">GL: </span>{GL}</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'request_sent',
			'name'=>'request_sent',
			'content_plain_de'=>'{CONTENT}
Vielen Dank für Ihre Anfrage, wir werden sie so schnell wie möglich bearbeiten.',
			'content_html_de'=>'{CONTENT}
<p>Vielen Dank für Ihre Anfrage, wir werden sie so schnell wie möglich bearbeiten.</p>',
			'content_plain_fr'=>'{CONTENT}
Thank you for your inquiry, we will process it as soon as possible.',
			'content_html_fr'=>'{CONTENT}
<p>Thank you for your inquiry, we will process it as soon as possible.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'request_accepted',
			'name'=>'request_accepted',
			'content_plain_de'=>'{CONTENT}
Vielen Dank für Ihre Anfrage, wir haben die Änderung angenommen.',
			'content_html_de'=>'{CONTENT}
<p>Vielen Dank für Ihre Anfrage, wir haben die Änderung angenommen.</p>',
			'content_plain_fr'=>'{CONTENT}
Thank you for your inquiry, we have accepted the change.',
			'content_html_fr'=>'{CONTENT}
<p>Thank you for your inquiry, we have accepted the change.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'request_rejected',
			'name'=>'request_rejected',
			'content_plain_de'=>'{CONTENT}
Vielen Dank für Ihre Anfrage, wir haben die Änderung abgelehnt.',
			'content_html_de'=>'{CONTENT}
<p>Vielen Dank für Ihre Anfrage, wir haben die Änderung abgelehnt.</p>',
			'content_plain_fr'=>'{CONTENT}
Thank you for your request, we have rejected the change.',
			'content_html_fr'=>'{CONTENT}
<p>Thank you for your request, we have rejected the change.</p>',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'create_subuser',
			'name'=>'create_subuser',
			'content_plain_de'=>'{CONTENT}
Herzlich willkommen bei VSOH. Um unsere Website zu besuchen, klicken Sie oben auf jeder Seite auf das Login-Symbol und geben Sie Ihre E-Mail-Adresse und Ihr Passwort ein.

With the following access data you can login:
E-mail: {USER_EMAIL}
Passwort: {PASSWORD}',
			'content_html_de'=>'{CONTENT}
<p>Herzlich willkommen bei <a href="{BASE_URL}" style="color: #b8cb00">VSOH</a>. Um unsere Website zu besuchen, klicken Sie oben auf jeder Seite auf das Login-Symbol und geben Sie Ihre E-Mail-Adresse und Ihr Passwort ein.</p>
<p>
Mit folgenden Zugangsdaten können Sie sich einloggen:<br />
E-Mail: {USER_EMAIL}<br />
Passwort: {PASSWORD}
</p>',
			'content_plain_fr'=>'{CONTENT}
<p>Welcome to VSOH. To visit our site, just click the Login icon at the top of every page and enter your e-mail address and password.</p>
<p>
With the following access data you can log in:<br />
Email: {USER_EMAIL}<br />
Password: {PASSWORD}
</p>',
			'content_html_fr'=>'{CONTENT}
Welcome to <a href="{BASE_URL}" style="color: #b8cb00">VSOH</a>. To visit our site, just click the Login icon at the top of every page and enter your e-mail address and password.

With the following access data you can log in:
Email: {CUSTOMER_EMAIL}
Password: {PASSWORD}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'user_credentials',
			'name'=>'user_credentials',
			'content_plain_de'=>'{CONTENT}
{USER_THANKS}
E-mail: {USER_EMAIL}
Passwort: {PASSWORD}',
			'content_html_de'=>'{CONTENT}

<p>
{USER_THANKS}<br />
E-Mail: {USER_EMAIL}<br />
Passwort: {PASSWORD}
</p>',
			'content_plain_en'=>'{CONTENT}
<p>
{USER_THANKS}<br />
E-Mail: {USER_EMAIL}<br />
Passwort: {PASSWORD}
</p>',

			'content_html_en'=>'{CONTENT}
{USER_THANKS}
E-mail: {USER_EMAIL}
Passwort: {PASSWORD}',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'survey_email',
			'name'=>'survey_email',
			'content_plain_de'=>'{CONTENT}
Bitte nehmen Sie sich einen Augenblick Zeit und füllen Sie den folgenden Fragebogen aus.
{SURVEY_URL}',
			'content_html_de'=>'{CONTENT}
<p style="margin-bottom:0">Bitte nehmen Sie sich einen Augenblick Zeit und füllen Sie den folgenden Fragebogen aus.</p>
<p style="margin:0"><a href="{SURVEY_URL}" target="_blank">{SURVEY_URL}</a></p>',
			'content_plain_fr'=>'{CONTENT}
Please take a moment to complete the following questionnaire.
{SURVEY_URL}',
			'content_html_fr'=>'{CONTENT}
<p style="margin-bottom:0">Please take a moment to complete the following questionnaire.</p>
<p style="margin:0"><a href="{SURVEY_URL}" target="_blank">{SURVEY_URL}</a></p>',
		);

//		$w[] = array(
//			'id'=>$i++,
//			'short'=>'reset_password',
//			'name'=>'reset_password',
//			'content_plain_de'=>'{CONTENT}',
//			'content_html_de'=>'{CONTENT}',
//			'content_plain_fr'=>'{CONTENT}',
//			'content_html_fr'=>'{CONTENT}',
//		);

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