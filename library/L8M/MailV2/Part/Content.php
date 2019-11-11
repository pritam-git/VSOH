<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mail/Part/Content.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Content.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_MailV2_Part_Content
 *
 *
 */
class L8M_MailV2_Part_Content extends L8M_MailV2_Part
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The salutation to be used for adressing the recipients
	 *
	 * @var string
	 */
	protected $_salutation = NULL;

	/**
	 * The salutation to be used for personally addressing the individual
	 * recipient.
	 *
	 * @var string
	 */
	protected $_salutationPersonalized = NULL;

	/**
	 * An array of signature items (array('label'=>'email',
	 * 'value'=>'nm@l8m.com')
	 *
	 * @var array
	 */
	protected $_signature = array();

	/**
	 * A complimentary close used at the end of the email
	 *
	 * @var string
	 */
	protected $_close = NULL;

	/**
	 * A complimentary close used at the end of the email, for sending
	 * personalized emails.
	 *
	 * @var string
	 */
	protected $_closePersonalized = NULL;


	/**
	 * A string with the email HTML content or Plain content
	 *
	 * @var  string
	 */
	protected $_content = NULL;

	/**
	 * Contains vars for replace action while rendering
	 *
	 * @var array
	 */
	protected $_dynamicVars = array();

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
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets salutation of this email
	 *
	 * @param  string  $salutation
	 * @return L8M_MailV2_Part_Content
	 */
	public function setSalutation($salutation = NULL)
	{
		$this->_salutation = (string) $salutation;
		return $this;
	}

	/**
	 * Sets personalized salutation of this email. Note: an exception will be
	 * thrown if it is attempted to send a personalized email without having set
	 * a personalized salutation
	 *
	 * @param  string  $salutation
	 * @return L8M_MailV2_Part_Content
	 */
	public function setSalutationPersonalized($salutation = NULL)
	{
		$this->_salutationPersonalized = (string) $salutation;
		return $this;
	}

	/**
	 * Sets complimentary close of this email.
	 *
	 * @param  string			   $close
	 * @return L8M_MailV2_Part_Content
	 */
	public function setClose($close = NULL)
	{
		$this->_close = $close;
		return $this;
	}

	/**
	 * Sets complimentary close of this email for sending personalized emails.
	 *
	 * @param  string			   $close
	 * @return L8M_MailV2_Part_Content
	 */
	public function setClosePersonalized($close = NULL)
	{
		$this->_closePersonalized = $close;
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
	 * Returns complimentary close used for this email.
	 *
	 * @return string
	 */
	public function getClose()
	{
		return $this->_close;
	}

	/**
	 * Returns complimentary close used for sending this email personalized.
	 *
	 * @return string
	 */
	public function getClosePersonalized()
	{
		if ($this->getParent()->getCurrentToName()) {
			return $this->_closePersonalized;
		} else {
			return $this->_close;
		}
	}

	/**
	 * Returns salutation used for this email.
	 *
	 * @return string
	 */
	public function getSalutation()
	{
		return $this->_salutation;
	}

	/**
	 * Returns salutation used for sending a personalized email.
	 *
	 * @return string
	 */
	public function getSalutationPersonalized()
	{
		$currentToName = $this->getParent()->getCurrentToName();
		if ($currentToName) {
			return $this->_salutationPersonalized . ' ' . $currentToName;
		} else {
			return $this->_salutation;
		}
	}

	/**
	 *
	 *
	 * Render Methods
	 *
	 *
	 */

	/**
	 * Renders all.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderAll($mode = self::RENDER_TEXT, $position = NULL)
	{
		$all = $this->_renderTop($mode, $position)
			 . $this->_renderHeadline($mode)
			 . $this->_renderSalutation($mode)
			 . $this->_renderDynamicVars(str_replace('{CONTENT}', $this->utf8Decode($this->_renderContent($mode)), $this->getLayout($mode)))
			 . $this->_renderClose($mode)
			 . $this->_renderBottom($mode)
		;
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
	 * Renders salutation.
	 *
	 * @param unknown_type $mode
	 * @return unknown
	 */
	protected function _renderSalutation($mode = self::RENDER_TEXT)
	{
		if ($this->getParent()->isPersonalizationEnabled() &&
			$this->getSalutationPersonalized()) {
			$salutation = $this->getSalutationPersonalized();
		} else {
			$salutation = $this->getSalutation();
		}
		if ($salutation) {
			ob_start();
			if ($mode==self::RENDER_HTML) {
?>
<!-- salutation begin -->
<p style="font-size:12px; line-height:1.5em; margin:0px; padding:0px; padding-bottom:2em; text-align:justify"><?php echo $this->escape($this->utf8Decode($salutation)); ?>,</p>
<!-- salutation end -->
<?php
			} else
			if ($mode==self::RENDER_TEXT) {
				echo $this->utf8Decode($salutation) . ',' . PHP_EOL . PHP_EOL;
			}
			return ob_get_clean();
		}
		return NULL;
	}

	/**
	 * Renders close.
	 *
	 * @todo   retrieve content from close
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderClose($mode = self::RENDER_TEXT)
	{
		if ($this->getParent()->isPersonalizationEnabled() &&
			$this->getClosePersonalized()) {
			$close = $this->getClosePersonalized();
		} else {
			$close = $this->getClose();
		}
		if ($close) {
			ob_start();
			if ($mode==self::RENDER_HTML) {
?>
<!-- close begin -->
<p style="font-size:12px; line-height:1.5em; margin:0px; padding:0px; padding-top:1em; padding-bottom:1em; text-align:justify"><?php echo $this->escape($this->utf8Decode($close)); ?></p>
<p style="font-size:12px; line-height:1.5em; margin:0px; padding:0px; text-align:justify"><?php echo $this->escape($this->utf8Decode($this->getParent()->getFromName())); ?></p>
<!-- close end -->
<?php
			} else
			if ($mode==self::RENDER_TEXT) {
				echo $this->utf8Decode($close) . PHP_EOL . PHP_EOL . $this->utf8Decode($this->getParent()->getFromName()) . PHP_EOL;
			}
			return ob_get_clean();
		}
		return NULL;
	}
}