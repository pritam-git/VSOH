<?php

/**
 * L8M
 *
 *
 * @filesource library/L8M/MailV2.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MailV2.php 556 2018-01-18 19:43:01Z nm $
 */

/**
 *
 *
 * L8M_Mail
 *
 *
 */
class L8M_MailV2
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * The email address to which every email sent using this class will be
	 * BCC'ed to
	 *
	 */
	const MAIL_DEFAULT_BCC = 'server@l8m.com';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The email address of the sender.
	 *
	 * @var string
	 */
	protected $_fromEmail = NULL;

	/**
	 * The name of the sender.
	 *
	 * @var string
	 */
	protected $_fromName = NULL;

	/**
	 * An array of recipients who will receive this email as a carbon copy.
	 *
	 * @var array
	 */
	protected $_cc = array();

	/**
	 * An array of recipients who will receive this email as a blind carbon copy.
	 *
	 * @var array
	 */
	protected $_bcc = array();

	/**
	 * If personalization is enabled, the name of the currently addressed
	 * user is stored here so an L8M_MailV2_Part instance can access it via a
	 * getter.
	 *
	 * @var string
	 */
	protected $_currentToName = NULL;

	/**
	 * A Zend_Mail instance that is used for sending emails
	 *
	 * @var Zend_Mail
	 */
	protected $_mail = NULL;

	/**
	 * The subject of the email.
	 *
	 * @var string
	 */
	protected $_subject = NULL;

	/**
	 * An array of recipients of this email.
	 *
	 * @var array
	 */
	protected $_to = array();

	/**
	 * A Zend_Translate instance.
	 *
	 * @var Zend_Translate
	 */
	protected $_translator = NULL;

	/**
	 * Whether or not this email will be sent with an HTML part.
	 *
	 * @var bool
	 */
	protected $_htmlEnabled = FALSE;

	/**
	 * contains html object standard css
	 */
	protected $_htmlStandardCssStyles = array(
		'html_body_css_style'=>'color:#000000; background-color:#FFFFFF; margin:0px; padding:0px; font-size:12px; font-family:Arial,Verdana,Helvetica,sans-serif;',
		'html_paragraph_css_style'=>'font-size:12px; margin-bottom:4px; margin-top:0px;',
		'html_headline_css_style'=>'font-size:14px; margin-top:10px; margin-bottom:4px;',
		'html_data_css_style'=>'font-size:12px; margin:0px; padding:0px; border:0px;',
		'html_dataline_label_css_style'=>'font-size:12px; padding-right:15px; padding-bottom:5px; font-weight:bold; vertical-align:top;',
		'html_dataline_data_css_style'=>'font-size:12px; padding-left:10px; padding-bottom:5px; vertical-align:top;',
	);

	/**
	 * Whether or not this email will be sent using rendering.
	 *
	 * @var bool
	 */
	protected $_renderingEnabled = FALSE;

	/**
	 * Whether or not emails will be sent personalized.
	 *
	 * @var bool
	 */
	protected $_personalizationEnabled = TRUE;

	/**
	 * The project root of the web application. Needed for rendering HTML emails
	 * that link to images on the server.
	 *
	 * @var string
	 */
	protected $_projectRoot = NULL;

	/**
	 * An array of L8M_MailV2_Part_Abstract instances.
	 *
	 * @var array
	 */
	protected $_parts = array();

	/**
	 * A string representing the theme to use for sending this email.
	 *
	 * @todo provide functionality
	 * @var  string
	 */
	protected $_theme = NULL;


	/**
	 * A array with the email HTML layout and or Plain content
	 *
	 * @var  array
	 */
	protected $_layout = NULL;

	/**
	 * remember my template / layout short
	 *
	 * @var string
	 */
	protected $_emailTemplateShort = NULL;

	/**
	 * contains lang short to use for templates
	 *
	 * @var string
	 */
	protected $_languageShort = NULL;

	/**
	 * Contains vars for replace action while rendering
	 *
	 * @var array
	 */
	protected $_dynamicVars = array();

	/**
	 * Mail character set
	 * @var string
	 */
	protected $_charset = 'utf-8';

	/**
	 * Mail organisation
	 * @var string
	 */
	protected $_organisation = 'HAHN media group ag.';

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Mail instance
	 *
	 * @param  string|array $options
	 * @return L8M_Mail
	 */
	public function __construct($options = NULL)
	{
		$this->_init($options);
		$this->_languageShort = L8M_Locale::getLang();
	}

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes L8M_Mail instance. Creates a Zend_Mail instance owned by this
	 * instance.
	 *
	 * @return void
	 */
	protected function _init($options = NULL)
	{
		if (is_array($options)) {
			if (array_key_exists('charset', $options)) {
				$charset = $options['charset'];
			}
		} else {
			$charset = $options;
		}
		$this->getMail(TRUE, $charset);
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

	/**
	 * Sets HTML body of this email.
	 *
	 * @todo   disable rendering of parts if this function is called
	 * @param  string  $html
	 * @param  string  $charset
	 * @param  string  $encoding
	 * @return L8M_Mail
	 */
	public function setBodyHtml($html = NULL, $charset = NULL, $encoding = Zend_Mime::ENCODING_8BIT)
	{
		$this->disableRendering();
		if ($html!==NULL) {
			$this->enableHtml(TRUE);
			$this->_mail->setBodyHtml($html, $charset, $encoding);
		}
		return $this;
	}

	/**
	 * Sets Text body of this email.
	 *
	 * @todo   disable rendering of parts if this function is called
	 * @param  string  $txt
	 * @param  string  $charset
	 * @param  string  $encoding
	 * @return L8M_Mail
	 */
	public function setBodyText($txt = NULL, $charset = NULL, $encoding = Zend_Mime::ENCODING_8BIT)
	{
		$this->disableRendering();
		$this->_mail->setBodyText($txt, $charset, $encoding);
		return $this;
	}

	/**
	 * Sets current addressee's name.
	 *
	 * @return L8M_Mail
	 */
	protected function _setCurrentToName($name = NULL)
	{
		$this->_currentToName = $name;
		return $this;
	}

	/**
	 * Sets sender of this email, but clears headers if it has been set before.
	 *
	 * @param  string  $email
	 * @param  string  $name
	 * @return L8M_Mail
	 */
	public function setFrom($email = NULL, $name = NULL)
	{
		if (array_key_exists('From', $this->_mail->getHeaders())) {
			$this->_mail->clearFrom();
		}
		$this->_mail->setFrom($email, $this->utf8Decode($name));
		$this->_fromEmail = $email;
		$this->_fromName = $name;
		return $this;
	}

	/**
	 * Sets project root of this email.
	 *
	 * @param  string  $projectRoot
	 * @return L8M_Mail
	 */
	public function setProjectRoot($projectRoot = NULL)
	{
		$this->_projectRoot = (string) $projectRoot;
		return $this;
	}

	/**
	 * Sets Reply-to to specified email address.
	 *
	 * @param  string  $email
	 * @return L8M_Mail
	 */
	public function setReplyTo($email = NULL)
	{
		$this->_replyTo = $email;
		return $this;
	}

	/**
	 * Sets subject of this email
	 *
	 * @param  string  $subject
	 * @return L8M_Mail
	 */
	public function setSubject($subject = NULL)
	{
		if ($subject) {
			if ($this->_subject) {
				$this->_subject = str_replace('{CONTENT}', $subject, $this->_subject);
			} else {
				 $this->_subject = $subject;
			}
		}
		return $this;
	}

	/**
	 * Sets theme to be used for sending this email.
	 *
	 * @param  string $theme
	 * @return L8M_Mail
	 */
	public function setTheme($theme = NULL)
	{
		$this->_theme = $theme;
		return $this;
	}

	/**
	 * Set dynamic Var for search & replace
	 *
	 * @param string $var
	 * @param string $value
	 * @return L8M_MailV2_Part_Content
	 */
	public function setDynamicVar($var, $value)
	{
		$this->_dynamicVars[$var] = $value;
		return $this;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
	 * Returns list of BCC recipients.
	 *
	 * @return array
	 */
	public function getBcc()
	{
		$bcc = $this->_bcc;

		if (!L8M_Config::getOption('resources.mail.defaultBcc.disabled')) {
			if (!in_array(self::MAIL_DEFAULT_BCC, $bcc)) {
				$bcc = array_merge(
					$bcc,
					array(
						self::MAIL_DEFAULT_BCC,
					)
				);
			}
		}

		return $bcc;
	}

	/**
	 * Returns HTML body of this email.
	 *
	 * @return string
	 */
	public function getBodyHtml()
	{
		return $this->getMail()->getBodyHtml();
	}

	/**
	 * Returns Text body of this email.
	 *
	 * @return string
	 */
	public function getBodyText()
	{
		return $this->getMail()->getBodyText();
	}

	/**
	 * Returns array of recipients who will receive this email as a carbon copy.
	 *
	 * @return array
	 */
	public function getCc()
	{
		return $this->_cc;
	}

	/**
	 * Returns current addressee.
	 *
	 * @return string
	 */
	public function getCurrentToName()
	{
		return $this->_currentToName;
	}

	/**
	 * Returns sender of this email.
	 *
	 * @return string
	 */
	public function getFrom()
	{
		return $this->_mail->getFrom();
	}

	/**
	 * Returns email address of the sender of this email.
	 *
	 * @return string
	 */
	public function getFromEmail()
	{
		return $this->_fromEmail;
	}

	/**
	 * Returns name of the sender of this email.
	 *
	 * @return string
	 */
	public function getFromName()
	{
		return $this->_fromName;
	}

	/**
	 * Returns Zend_Mail instance.
	 *
	 * @param  bool	  $enforce
	 * @param  string	$charset
	 * @return Zend_Mail
	 */
	public function getMail($enforce = FALSE, $charset = 'UTF-8')
	{
		if ($this->_mail===NULL ||
			$enforce==TRUE) {

			/**
			 * do not construct Zend_Mail with charset (utf-8) this will not work
			 */
			$this->_mail = new Zend_Mail($this->_charset);
			$this->_mail->addHeader('Charset', $this->_charset);
			$this->_mail->addHeader('X-Mailer', str_replace('_', ' ', get_class($this)) . ' using ZendFramework ' . Zend_Version::VERSION);
			$organisationString = trim(L8M_Config::getOption('resources.mail.organisation'));
			if (!$organisationString) {
				$organisationString = $this->_organisation;
			}
			if (!L8M_Config::getOption('resources.mail.defaultBcc.disabled')) {
				$organisationString .= ' (' . L8M_Config::getOption('l8m.project.short') . ')';
			}
			$this->_mail->addHeader('Organization', $organisationString);
		}
		return $this->_mail;
	}

	/**
	 * Returns an array of L8M_MailV2_Part_Abstract instances
	 *
	 * @return array
	 */
	public function getParts()
	{
		return $this->_parts;
	}

	/**
	 * Returns project root.
	 *
	 * @return string
	 */
	public function getProjectRoot()
	{
		if ($this->_projectRoot === NULL) {
			if (php_sapi_name() != 'cli' &&
				isset($_SERVER['SERVER_NAME'])) {

				$this->setProjectRoot(L8M_Library::getSchemeAndHttpHost(TRUE));
			} else {
				$this->setProjectRoot('http://www.l8m.com/');
			}
		}
		return $this->_projectRoot;
	}

	/**
	 * Returns subject of this email.
	 *
	 * @return string
	 */
	public function getSubject()
	{
		return $this->_subject;
	}

	/**
	 * Returns theme used for this email.
	 *
	 * @return unknown
	 */
	public function getTheme()
	{
		return $this->_theme;
	}

	/**
	 * Returns style of html object used in html mode.
	 *
	 * @return string
	 */
	public function getHtmlCssStyle($htmlObject = NULL)
	{
		$returnValue = NULL;
		if ($htmlObject &&
			array_key_exists($htmlObject, $this->_htmlStandardCssStyles)) {

			$returnValue = $this->_htmlStandardCssStyles[$htmlObject];
		}

		return $returnValue;
	}

	/**
	 * Returns an array with recipients of this email.
	 *
	 * @return array
	 */
	public function getTo()
	{
		return $this->_to;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */


	/**
	 * Load dynamic Vars from DB
	 *
	 * @return L8M_MailV2_Part_Content
	 */
	public function loadDynamicVars()
	{
		$emailTemplateReplacementArray = Doctrine_Query::create()
			->from('Default_Model_EmailTemplateReplacement m')
			->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
			->execute()
		;

		$this->_dynamicVars = L8M_Library::arrayIdToKeyValue($emailTemplateReplacementArray, 'short', 'value');

		return $this;
	}

	/**
	 * Adds recipient to internal list of recipients
	 *
	 * @param  string $email
	 * @param  string $name
	 * @return L8M_Mail
	 */
	public function addTo($email = NULL, $name = NULL)
	{
		$email = strtolower(trim($email));
		if (!array_key_exists($email, $this->_to)) {
			$this->_to[$email] = trim($name);
		}
		return $this;
	}

	/**
	 * Clears recipient list.
	 *
	 * @return L8M_Mail
	 */
	public function clearTo()
	{
		$this->_to = array();
		return $this;
	}

	/**
	 * Adds carbon copy recipient.
	 *
	 * @todo   consider this if we are iterating over recipients (if cc is set,
	 *		 maybe we should not iterate, otherwise the CC will receive the
	 *		 email as many times as there are recipients. Same applies for BCC)
	 * @param  string  $email
	 * @param  string  $name
	 * @return L8M_Mail
	 */
	public function addCc($email = NULL, $name = NULL)
	{
		$email = strtolower(trim($email));
		if (!array_key_exists($email, $this->_cc)) {
			$this->_cc[$email] = trim($name);
		}
		return $this;
	}

	/**
	 * Clears CC recipient list.
	 *
	 * @return L8M_Mail
	 */
	public function clearCc()
	{
		$this->_cc = array();
		return $this;
	}

	/**
	 * Adds blind carbon copy recipient.
	 *
	 * @todo   consider this if we are iterating over recipients (if bcc is set,
	 *		 maybe we should not iterate, otherwise the BCC will receive the
	 *		 email as many times as there are recipients.)
	 * @param  string  $email
	 * @param  string  $name
	 * @return L8M_Mail
	 */
	public function addBcc($email = NULL)
	{
		$email = strtolower(trim($email));
		if (!in_array($email, $this->_bcc)) {
			$this->_bcc[] = $email;
		}
		return $this;
	}

	/**
	 * Clears list of BCC recipients.
	 *
	 * @return L8M_Mail
	 */
	public function clearBcc()
	{
		$this->_bcc = array();
		return $this;
	}

	/**
	 * Enables/disables sending as rendered email.
	 *
	 * @param  bool	$enable
	 * @return L8M_Mail
	 */
	public function enableRendering($enable = TRUE)
	{
		$this->_renderingEnabled = (bool) $enable;
		return $this;
	}

	/**
	 * Disables sending as rendered email.
	 *
	 * @return L8M_Mail
	 */
	public function disableRendering()
	{
		$this->enableRendering(FALSE);
		return $this;
	}

	/**
	 * Returns TRUE if this email will be rendered.
	 *
	 * @return bool
	 */
	public function isRenderingEnabled()
	{
		return $this->_renderingEnabled;
	}

	/**
	 * Enables/disables sending as HTML email
	 *
	 * @param  bool	$enable
	 * @return L8M_Mail
	 */
	public function enableHtml($enable = TRUE)
	{
		$this->_htmlEnabled = (bool) $enable;
		return $this;
	}

	/**
	 * Disables sending as HTML email
	 *
	 * @return L8M_Mail
	 */
	public function disableHtml()
	{
		$this->enableHtml(FALSE);
		return $this;
	}

	/**
	 * Returns TRUE if this email will be sent with an HTML part.
	 *
	 * @return bool
	 */
	public function isHtmlEnabled()
	{
		return $this->_htmlEnabled;
	}

	/**
	 * Enables sending as personalized email
	 *
	 * @param  bool	$enable
	 * @return L8M_Mail
	 */
	public function enablePersonalization($enable = TRUE)
	{
		$this->_personalizationEnabled = (bool) $enable;
		return $this;
	}

	/**
	 * Disables personalized sending of emails.
	 *
	 * @return L8M_Mail
	 */
	public function disablePersonalization()
	{
		$this->enablePersonalization(FALSE);
		return $this;
	}

	/**
	 * Returns TRUE if this email will be sent personalized.
	 *
	 * @return bool
	 */
	public function isPersonalizationEnabled()
	{
		return $this->_personalizationEnabled;
	}

	/**
	 * Adds a mail part to the list of mail parts for later rendering.
	 *
	 * @param  L8M_MailV2_Part_Abstract $part
	 * @return L8M_Mail
	 */
	public function addPart($part = NULL)
	{
		if (!$part instanceof L8M_MailV2_Part) {
			throw new L8M_MailV2_Exception('Parts added to an instance of L8M_MailB2 need to inherit from L8M_MailV2_Part_Abstract.');
		}
		if ($part) {
			$part->setParent($this);
			$this->_parts[] = $part;
			$this->enableRendering();
		}
		if ($part instanceof L8M_MailV2_Part_Attachment &&
			$part->isInline()) {

			$this->_mail->setType(Zend_Mime::MULTIPART_RELATED);
		}
		return $this;
	}

	/**
	 * Decodes from UTF8, but only if email charset is not UTF8.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function utf8Decode($content = NULL)
	{
		if (!$this->isUtf8()) {
			$content = utf8_decode($content);
		}
		return $content;
	}

	/**
	 * Returns TRUE if email is sent using an UTF8 charset
	 *
	 * @return bool
	 */
	public function isUtf8()
	{
		return strtoupper($this->getMail()->getCharset())=='UTF-8';
	}

	/**
	 * Sends email(s)
	 *
	 * @todo   overhaul!!
	 * @param  Zend_Mail_Transport_Abstract $transport
	 * @return L8M_Mail
	 */
	public function send($transport = NULL)
	{
		/**
		 * recipients?
		 */
		if (count($this->getTo())==0) {
			throw new L8M_MailV2_Exception('Can not send email if no recipient is added');
		}
		/**
		 * sender?
		 */
		if (!$this->getFromEmail()) {
			throw new L8M_MailV2_Exception('Can not send email if no sender is set.');
		}
		/**
		 * rendering disabled, text and html body not set?
		 */
		if (!$this->isRenderingEnabled() &&
			!$this->getBodyText() &&
			!$this->getBodyHtml()) {
			throw new L8M_MailV2_Exception('Can not send email if no body is set.');
		} else

		/**
		 *
		 *
		 * rendering disabled, send as normal Zend_Mail instance
		 *
		 *
		 */
		if (!$this->isRenderingEnabled()) {
			/**
			 * add recipients
			 */
			foreach($this->getTo() as $email=>$name) {
				$this->getMail()->addTo($email, $this->utf8Decode($name));
			}
			foreach($this->getBcc() as $email) {
				$this->getMail()->addBcc($email);
			}
			foreach($this->getCc() as $email=>$name) {
				$this->getMail()->addCc($email, $name);
			}
			$this->getMail()->setSubject($this->getSubject());
			$this->getMail()->send($transport);
		} else

		/**
		 *
		 *
		 * rendering enabled
		 *
		 *
		 */
		if ($this->isRenderingEnabled()) {
			/**
			 *
			 *
			 * personalization enabled
			 *
			 *
			 */
			if ($this->isPersonalizationEnabled()) {
				$this->getMail()->setSubject($this->getSubject());

				/**
				 * iterate over recipients
				 */
				foreach($this->getTo() as $email=>$name) {
					$this->getMail()->clearRecipients();
					$this->getMail()->addTo($email, $this->utf8Decode($name));
					$this->_setCurrentToName($name);
					foreach($this->getBcc() as $bcc) {
						$this->getMail()->addBcc($bcc);
					}
					$this->getMail()->setBodyText($this->toText(), $this->_charset, Zend_Mime::ENCODING_8BIT);
					if ($this->isHtmlEnabled()) {
						$this->getMail()->setBodyHtml($this->toHtml(), $this->_charset, Zend_Mime::ENCODING_8BIT);
					}
					$this->getMail()->send($transport);
				}
			} else {
				/**
				 *
				 *
				 * personalization disabled
				 *
				 *
				 */
				foreach($this->getTo() as $email=>$name) {
					$this->getMail()->addTo($email, $this->utf8Decode($name));
				}
				foreach($this->getBcc() as $email) {
					$this->getMail()->addBcc($email);
				}
				foreach($this->getCc() as $email=>$name) {
					$this->getMail()->addCc($email, $this->utf8Decode($name));
				}
				$this->getMail()->setSubject($this->getSubject());
				$this->getMail()->setBodyText($this->toText(), $this->_charset, Zend_Mime::ENCODING_8BIT);
				if ($this->isHtmlEnabled()) {
					$this->getMail()->setBodyHtml($this->toHtml(), $this->_charset, Zend_Mime::ENCODING_8BIT);
				}
				$this->_mail->send($transport);
			}
		}
		return $this;
	}

	/**
	 *
	 *
	 * Render Methods
	 *
	 *
	 */

	/**
	 * Returns a Text representation of this mail part for use in a plain
	 * text email.
	 *
	 * @return string
	 */
	public function toText()
	{
		return $this->_renderAll(L8M_MailV2_Part::RENDER_TEXT);
	}

	/**
	 * Returns an HTML representation of this mail part for use in a multi-part
	 * email.
	 *
	 * @return string
	 */
	public function toHtml()
	{
		return $this->_renderAll(L8M_MailV2_Part::RENDER_HTML);
	}

	/**
	 * Renders all.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderAll($mode = L8M_MailV2_Part::RENDER_TEXT)
	{
		return $this->_renderTop($mode) . $this->_renderDynamicVars(str_replace('{CONTENT}', $this->_renderContent($mode), $this->getLayout($mode))) . $this->_renderBottom($mode);

	}

	/**
	 * Renders top of email.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderTop($mode = L8M_MailV2_Part::RENDER_TEXT)
	{
		ob_start();
		if ($mode == L8M_MailV2_Part::RENDER_HTML) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_charset; ?>" />
		<title>HTML eMail</title>
	</head>

	<body style="<?php echo $this->getHtmlCssStyle('html_body_css_style'); ?>">
<?php
		} else
		if ($mode == L8M_MailV2_Part::RENDER_TEXT) {
		}
		return ob_get_clean();
	}

	/**
	 * Renders content of mail.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderContent($mode = L8M_MailV2_Part::RENDER_TEXT)
	{
		ob_start();
		$i = 1;
		foreach($this->getParts() as $part) {
			echo $part->to($mode, $i);
			$i++;
		}
		$all = ob_get_clean();
		return $all;
	}

	/**
	 * Renders dynamic vars into content.
	 *
	 * @param  string $content
	 * @return string
	 */
	protected function _renderDynamicVars($content)
	{
		foreach ($this->_dynamicVars as $key => $dynamicVar) {
			$content = str_replace('{' . strtoupper($key) . '}', $this->utf8Decode($dynamicVar), $content);
		}

		return $content;
	}

	/**
	 * Renders bottom of email.
	 *
	 * @return string
	 */
	protected function _renderBottom($mode = L8M_MailV2_Part::RENDER_TEXT)
	{
		ob_start();
		if ($mode==L8M_MailV2_Part::RENDER_HTML) {
?>
	</body>
</html>
<?php
		} else
		if ($mode==L8M_MailV2_Part::RENDER_TEXT) {
		}
		return ob_get_clean();
	}

	/**
	 * retrieve the email layout
	 */
	public function getLayout($mode = L8M_MailV2_Part::RENDER_TEXT)
	{
		return $this->_layout[$mode];
	}

	/**
	 * set the email content HTML
	 */
	private function _setLayout($content = NULL, $mode = L8M_MailV2_Part::RENDER_TEXT)
	{
		if (strstr($content, '{CONTENT}') === FALSE)  {
			$content = $content . '{CONTENT}';
		}

		$this->_layout[$mode] = $this->utf8Decode($content);

		if ($mode == L8M_MailV2_Part::RENDER_HTML) {
			$this->enableHtml();
		}

		return $this;
	}

	/**
	 * return short of email layout / template
	 *
	 * @return string
	 */
	public function getEmailTemplateShort()
	{
		return $this->_emailTemplateShort;
	}

	/**
	 *
	 *
	 * Factory Method
	 *
	 *
	 */
	/**
	 * create an new Email with content from the db
	 *
	 * @param String $short
	 * @param String $options
	 * @return L8M_MailV2 $email
	 */
	public static function factory($emailTemplateShort = NULL, $languageShort = NULL)
	{
		$email = new L8M_MailV2();
		$email->_useLanguageShort($languageShort);

		/**
		 * retrieve the email with this short and it's parts
		 * @var Donctrine_Collection
		 */
		$emailTemplateModel = Doctrine_Query::create()
			->from('Default_Model_EmailTemplate et')
			->where('et.short = ? ', array($emailTemplateShort))
			->execute()
			->getFirst()
		;


		/**
		 * if an email template model exists with the given short
		 * set the email with the subject
		 * and put the content in a container
		 *
		 * otherwise create an new template model and save the short
		 */
		$email->_emailTemplateShort = $emailTemplateShort;
		if ($emailTemplateModel){

			/**
			 * html object standard css
			 */
			foreach ($email->_htmlStandardCssStyles as $key=>$value) {
				if (isset($emailTemplateModel[$key])) {
					$email->_htmlStandardCssStyles['html_body_css_style'] = $emailTemplateModel->$key;
				}
			}
		} else {
			if ($emailTemplateShort) {
				$emailTemplateModel = new Default_Model_EmailTemplate();
				$emailTemplateModel->short = $emailTemplateShort;
				$emailTemplateModel->name = $emailTemplateShort;
				foreach (L8M_Locale::getSupported() as $lang) {
					$emailTemplateModel->Translation[$lang]->content_html = '{CONTENT}';
					$emailTemplateModel->Translation[$lang]->content_plain = '{CONTENT}';
					$emailTemplateModel->Translation[$lang]->subject = '{CONTENT}';
				}
				foreach ($email->_htmlStandardCssStyles as $key=>$value) {
					$emailTemplateModel->$key = $value;
				}
				$emailTemplateModel->save();
			}
		}

		/**
		 * set content
		 */
		if ($emailTemplateModel->content_html) {
			$email->_setLayout($emailTemplateModel->Translation[$email->getLanguageShort()]->content_html, L8M_MailV2_Part::RENDER_HTML);
		}

		if ($emailTemplateModel->content_plain) {
			$email->_setLayout($emailTemplateModel->Translation[$email->getLanguageShort()]->content_plain, L8M_MailV2_Part::RENDER_TEXT);
		}

		/**
		 * set the subject
		 */
		$email->enablePersonalization(FALSE);
		$email->_subject = $emailTemplateModel->Translation[$email->getLanguageShort()]->subject;

		/**
		 * enable rendering
		 */
		$email->enableRendering();

		/**
		 * load dynamic vars
		 */
		$email->loadDynamicVars();

		return $email;
	}


	/**
	 *
	 *
	 * Magic Methods
	 *
	 *
	 */

	/**
	 * Called when a method is called that does not exist in this class. Method
	 * call is deferred to Zend_Mail instance.
	 *
	 * @todo   reconsider if we can safely use this
	 * @param  string  $name
	 * @param  array   $arguments
	 * @return L8M_Mail
	 */
	public function __call($name = NULL, $arguments = NULL)
	{
		if ($this->getMail() instanceof Zend_Mail &&
			method_exists($this->_mail, $name)) {
			call_user_func(array($this->getMail(), $name), $arguments);
			return $this;
		} else {
			throw new L8M_MailV2_Exception('Method "' . (string) $name . '"does not exist.');
		}
	}

	/**
	 * sets language short to use for templates
	 *
	 * @param string $languageShort
	 * @return L8M_MailV2
	 */
	protected function _useLanguageShort($languageShort = NULL)
	{
		if ($languageShort &&
			in_array($languageShort, L8M_Locale::getSupported())) {

			$this->_languageShort = $languageShort;
		} else {
			$this->_languageShort = L8M_Locale::getLang();
		}
		return $this;
	}

	/**
	 * returns language short to use for templates
	 *
	 * @return string
	 */
	public function getLanguageShort()
	{
		return $this->_languageShort;
	}
}