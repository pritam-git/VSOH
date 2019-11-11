<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mail/Part.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Part.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_MailV2_Part
 *
 *
 */
class L8M_MailV2_Part
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	const RENDER_HTML = 'html';
	const RENDER_TEXT = 'txt';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * The headline of the mail part.
	 *
	 * @var string
	 */
	protected $_headline = NULL;
	protected $_headline_html = NULL;

	/**
	 * The content of the mail part.
	 *
	 * @var string
	 */
	protected $_content = NULL;
	protected $_content_html = NULL;

	/**
	 * An array of contact items
	 *
	 * @var array
	 */
	protected $_items = array();

	/**
	 * A Zend_Translate instance
	 *
	 * @var Zend_Translate
	 */
	protected $_translator = NULL;

	/**
	 * An L8M_Mail instance to which this part is attached.
	 *
	 * @var L8M_MailV2
	 */
	protected $_mail = NULL;

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
	protected $_emailTemplatePartShort = NULL;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_MailV2_Part instance
	 *
	 * @return L8M_MailV2_Part
	 *
	 */
	public function __construct($options = NULL)
	{
		if ($options instanceof L8M_MailV2) {
			$this->setParent($options);
		}
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

	/**
	 * Sets reference to L8M_MailV2 instance owning this L8M_MailV2_Part
	 * instance.
	 *
	 * @param  L8M_MailV2			   $parent
	 * @return L8M_MailV2_Part
	 */
	public function setParent($parent = NULL)
	{
		if (!$parent ||
			!($parent instanceof L8M_MailV2)) {

			throw new L8M_MailV2_Part_Exception('Could not set parent as parent needs to inherit from L8M_Mail.');
		}

		$this->_mail = &$parent;

		/**
		 * retrieve the email with this short and it's parts
		 * @var Donctrine_Collection
		 */
		$emailTemplateM2nModel = Doctrine_Query::create()
			->from('Default_Model_EmailTemplateM2nEmailTemplatePart e2p')
			->leftJoin('e2p.EmailTemplate et')
			->leftJoin('e2p.EmailTemplatePart etp')
			->where('et.short = ? ', array($parent->getEmailTemplateShort()))
			->where('etp.short = ? ', array($this->getEmailTemplatePartShort()))
			->limit(1)
			->execute()
			->getFirst()
		;
		$contentPlain = NULL;
		$contentHtml = NULL;
		if ($emailTemplateM2nModel) {
			if ($emailTemplateM2nModel->EmailTemplatePart->content_plain) {
				$contentPlain = $emailTemplateM2nModel->EmailTemplatePart->Translation[$parent->getLanguageShort()]->content_plain;
			}

			if ($emailTemplateM2nModel->EmailTemplatePart->content_html) {
				$contentHtml = $emailTemplateM2nModel->EmailTemplatePart->Translation[$parent->getLanguageShort()]->content_html;
			}
		} else {

			$emailTemplatePartModel = Doctrine_Query::create()
				->from('Default_Model_EmailTemplatePart m')
				->where('m.short = ? ', array($this->getEmailTemplatePartShort()))
				->limit(1)
				->execute()
				->getFirst()
			;

			if (!$emailTemplatePartModel) {
				$emailTemplatePartModel = new Default_Model_EmailTemplatePart();
				$emailTemplatePartModel->short = $this->getEmailTemplatePartShort();
				$emailTemplatePartModel->name = $this->getEmailTemplatePartShort();
				foreach (L8M_Locale::getSupported() as $langShort) {
					$emailTemplatePartModel->Translation[$langShort]->content_plain = '{CONTENT}';
					$emailTemplatePartModel->Translation[$langShort]->content_html = '{CONTENT}';
				}
				$emailTemplatePartModel->save();

				$contentPlain = '{CONTENT}';
				$contentHtml = '{CONTENT}';
			} else {
				$contentPlain = $emailTemplateM2nModel->Translation[$parent->getLanguageShort()]->content_plain;
				$contentHtml = $emailTemplateM2nModel->Translation[$parent->getLanguageShort()]->content_html;
			}

			$emailTemplateModel = Doctrine_Query::create()
				->from('Default_Model_EmailTemplate m')
				->where('m.short = ? ', array($parent->getEmailTemplateShort()))
				->limit(1)
				->execute()
				->getFirst()
			;

			if ($emailTemplateModel) {
				$emailTemplateM2nModel = new Default_Model_EmailTemplateM2nEmailTemplatePart();
				$emailTemplateM2nModel->email_template_id = $emailTemplateModel->id;
				$emailTemplateM2nModel->email_template_part_id = $emailTemplatePartModel->id;
				$emailTemplateM2nModel->save();
			}

		}

		if (!$this->getLayout(self::RENDER_TEXT)) {
			$this->_setLayout($contentPlain, self::RENDER_TEXT);
		}
		if (!$this->getLayout(self::RENDER_HTML)) {
			$this->_setLayout($contentHtml, self::RENDER_HTML);
		}

		return $this;
	}

	/**
	 * Sets headline of this mail part.
	 *
	 * @param  string	   $headline
	 * @return L8M_MailV2_Part
	 */
	public function setHeadline($headline = NULL, $mode = self::RENDER_TEXT)
	{
		if ($mode == self::RENDER_TEXT) {
			$headline = strip_tags((string) $headline);
		}
		if (!is_string($headline)) {
			throw new L8M_MailV2_Part_Exception('Headline needs to be a string.');
		}
		$headline = trim($headline);
		if ($mode == self::RENDER_HTML) {
			$this->_headline_html = $headline;
		} else {
			$this->_headline = $headline;
		}
		return $this;
	}

	/**
	 * Sets content of this mail part.
	 *
	 * @param  string	   $content
	 * @return L8M_MailV2_Part
	 */
	public function setContent($content = NULL, $mode = self::RENDER_TEXT)
	{
		if ($mode == self::RENDER_TEXT) {
			$content = strip_tags((string) $content);
		}
		if ($content === NULL) {
			$content = '';
		}
		if (!is_string($content)) {
			throw new L8M_MailV2_Part_Exception('Content needs to be a string.');
		}
		$content = trim($content);
		if ($mode == self::RENDER_HTML) {
			$this->_content_html = $content;
		} else {
			$this->_content = $content;
		}
		return $this;
	}

	/**
	 * Sets content from source.
	 *
	 * @param  string				$source
	 * @return L8M_MailV2_Part
	 */
	public function setContentFromSource($source = NULL)
	{
		$source = (string) $source;
		if (!file_exists($source) ||
			!is_file($source) ||
			!is_readable($source)) {
			throw new L8M_MailV2_Part_Exception('Could not set content from source as source does not exist, is not readable or is not a file.');
		}
		$this->setContent(file_get_contents($source));
		return $this;
	}

	/**
	 *
	 *
	 * Class Getters
	 *
	 *
	 */

	/**
	 * Returns reference to L8M_Mail instance that owns this
	 * L8M_MailV2_Partinstance.
	 *
	 * @return L8M_Mail
	 */
	public function getParent()
	{
		return $this->_mail;
	}

	/**
	 * Returns headline of this mail part.
	 *
	 * @return string
	 */
	public function getHeadline($mode = self::RENDER_TEXT)
	{
		$returnValue = $this->_headline;
		if ($mode == self::RENDER_HTML) {
			if ($returnValue &&
				$this->_headline_html !== NULL) {

				$returnValue = $this->_headline_html;
			}
		}
		return $returnValue;
	}

	/**
	 * Returns content of this mail part.
	 *
	 * @return string
	 */
	public function getContent($mode = self::RENDER_TEXT)
	{
		$returnValue = $this->_content;
		if ($mode == self::RENDER_HTML) {
			if ($returnValue &&
				$this->_content_html !== NULL) {

				$returnValue = $this->_content_html;
			}
		}
		return $returnValue;
	}

	/**
	 * retrieve the email part layout
	 */
	public function getLayout($mode = self::RENDER_TEXT)
	{
		$returnValue = NULL;
		if (isset($this->_layout[$mode])) {
			$returnValue = $this->_layout[$mode];
		}
		return $returnValue;
	}

	/**
	 * Returns project root.
	 *
	 * @todo   fix hard coded return value
	 * @return string
	 */
	protected function _getProjectRoot()
	{
		return $this->getParent()->getProjectRoot();
	}

	/**
	 * Returns Zend_Translate instance.
	 *
	 * @return Zend_Translate
	 */
	protected function _getTranslator()
	{
		if ($this->_translator === NULL) {
			if (Zend_Registry::isRegistered('Zend_Translate') &&
				(NULL!=$translator = Zend_Registry::get('Zend_Translate')) &&
				$translator instanceof Zend_Translate) {
				$this->_translator = $translator;
			} else {
				$this->_translator = FALSE;
			}
		}
		return $this->_translator;
	}

	/**
	 * Translates specified content into specified locale.
	 *
	 * @param  string			 $message
	 * @param  string|Zend_Locale $locale
	 * @return string
	 */
	public function translate($message = NULL, $locale = NULL)
	{
		if ($this->_getTranslator() !== NULL) {
			return $this->_getTranslator()->getAdapter()->translate($message, $locale);
		}
		return $message;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Clears array of items.
	 *
	 * @return L8M_MailV2_Part
	 */
	public function clearItems()
	{
		$this->_items = array();
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
	 * Renders L8M_MailV2_Part in specified mode.
	 *
	 * @param  string $mode
	 * @param  int	$position
	 * @return string
	 */
	public function to($mode = NULL, $position = NULL)
	{
		$mode = trim(strtolower(((string) $mode)));
		if (!in_array($mode, array(self::RENDER_TEXT,
								   self::RENDER_HTML))) {
			throw new L8M_MailV2_Part_Exception('Can not render to unknown mode.');
		}
		return $this->_renderAll($mode, $position);
	}

	/**
	 * Returns a Text representation of this mail part for use in a plain
	 * text email.
	 *
	 * @return string
	 */
	public function toText()
	{
		return $this->to(self::RENDER_TEXT);
	}

	/**
	 * Returns an HTML representation of this mail part for use in a multi-part
	 * email.
	 *
	 * @return string
	 */
	public function toHtml()
	{
		return $this->to(self::RENDER_HTML);
	}

	/**
	 * Renders all.
	 *
	 * @param  string $mode
	 * @param  int	$position
	 * @return string
	 */
	protected function _renderAll($mode = self::RENDER_TEXT, $position = NULL)
	{
		return $this->_renderTop($mode, $position) .
			   $this->_renderHeadline($mode) .
			   str_replace('{CONTENT}', $this->_renderContent($mode), $this->getLayout($mode)) .
			   $this->_renderItems($mode) .
			   $this->_renderBottom($mode);
	}

	/**
	 * Renders top of content box.
	 *
	 * @param  string $mode
	 * @param  string $first
	 * @return string
	 */
	protected function _renderTop($mode = self::RENDER_TEXT, $position = NULL)
	{
		ob_start();
		if ($mode == self::RENDER_HTML) {
			if ($position === 1) {
?>
<?php
			} else {
?>
<?php
			}
?>
<?php

		} else
		if ($mode == self::RENDER_TEXT) {
			if ($position === 1) {
			}
		}
		return ob_get_clean();
	}

	/**
	 * Renders bottom of content box.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderBottom($mode = self::RENDER_TEXT)
	{
		ob_start();
		if ($mode == self::RENDER_HTML) {
?>
<?php

		} else
		if ($mode == self::RENDER_TEXT) {
			echo PHP_EOL . PHP_EOL . PHP_EOL;
		}
		return ob_get_clean();
	}

	/**
	 * Renders a headline.
	 *
	 * @todo   escaping
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderHeadline($mode = self::RENDER_TEXT)
	{
		$headline = $this->getHeadline();
		$headline = (string) $headline;
		$headline = strip_tags($headline);
		$headline = trim($headline);
		$headline = $this->utf8Decode($headline);
		if ($headline) {
			ob_start();
			if ($mode == self::RENDER_HTML) {
				if ($headline{1} == '<') {
					echo $headline;
				} else {
					echo '<h1 style="' . $this->getParent()->getHtmlCssStyle('html_headline_css_style') . '">' . $headline . '</h1>';
				}
			} else
			if ($mode == self::RENDER_TEXT) {
				echo $headline . PHP_EOL .
					 str_pad('', strlen($headline), '-') . PHP_EOL . PHP_EOL;
			}
			return ob_get_clean();
		}
		return NULL;
	}

	/**
	 * Renders paragraphs.
	 *
	 * @todo   handling of empty paragraphs
	 * @todo   escaping
	 * @param  string|array $paragraphs
	 * @param  string	   $mode
	 * @return string
	 */
	protected function _renderParagraphs($paragraphs = NULL, $mode = self::RENDER_TEXT, $onlyContent = FALSE)
	{
		$returnValue = NULL;
		if (is_array($paragraphs) ||
			is_string($paragraphs)) {

			if (!is_array($paragraphs) &&
				is_string($paragraphs) &&
				$mode == self::RENDER_HTML &&
				L8M_Library::hasHtml($paragraphs)) {

				$returnValue = $paragraphs;
			} else {
				if (is_string($paragraphs)) {
					$paragraphs = strip_tags($paragraphs);
					$paragraphs = trim($paragraphs);
					$paragraphs = explode(PHP_EOL, $paragraphs);
				}
				if (is_array($paragraphs)) {
					$paragraphCount = count($paragraphs);
					if ($paragraphCount > 0) {
						ob_start();
						if ($mode == self::RENDER_HTML) {
							$currentParagraph = 1;
							foreach ($paragraphs as $paragraph) {
								$currentParagraph++;
								$paragraph = $this->utf8Decode($paragraph);
								$paragraph = $this->escape($paragraph);
								if (strlen($paragraph) > 0 &&
									$paragraph{1} == '<') {

									echo $paragraph;
								} else {
									echo '<p style="' . $this->getParent()->getHtmlCssStyle('html_paragraph_css_style') . '">' . $paragraph . '</p>';
								}
							}
						}
						if ($mode == self::RENDER_TEXT) {
							foreach ($paragraphs as $paragraph) {
								$paragraph = $this->utf8Decode($paragraph);
								echo $paragraph . PHP_EOL . PHP_EOL;
							}
						}
						$returnValue = ob_get_clean();
					}
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Renders content of this mail part.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderContent($mode = self::RENDER_TEXT)
	{
		$renderedContent = $this->_renderParagraphs($this->getContent($mode), $mode);
		return $renderedContent;
	}

	/**
	 * Renders items of this mail part.
	 *
	 * @param  string $mode
	 * @return string
	 */
	protected function _renderItems($mode = self::RENDER_TEXT)
	{
		return NULL;
	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Escapes content.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function escape($content = NULL)
	{
		return htmlentities($content, ENT_COMPAT, $this->_mail->getMail()->getCharset());
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
		return strtolower($this->_mail->getMail()->getCharset())=='utf-8';
	}

	/**
	 * set the email content HTML
	 */
	private function _setLayout($content = NULL, $mode = self::RENDER_TEXT)
	{
		if (strstr($content, '{CONTENT}') === FALSE)  {
			$content = $content . '{CONTENT}';
		}

		$this->_layout[$mode] = $this->utf8Decode($content);
		return $this;
	}

	/**
	 * return short of email part layout / template
	 *
	 * @return string
	 */
	public function getEmailTemplatePartShort()
	{
		return $this->_emailTemplatePartShort;
	}

	/**
	 * set short of email part layout / template
	 */
	public function setEmailTemplatePartShort($emailTemplatePartShort)
	{
		$this->_emailTemplatePartShort = $emailTemplatePartShort;
	}

	/**
	 *
	 * Factory Method
	 *
	 */

	/**
	 * retrieve the parts form the db for the email
	 * @param string $short
	 * @param L8M_MailV2 $parent
	 * @return L8M_MailV2_Part_Content
	 */
	public static function factory($emailTemplateShort = NULL, $parent = NULL, $options = NULL)
	{
		$partContent = new L8M_MailV2_Part_Content();
		$partContent->setEmailTemplatePartShort($emailTemplateShort);
		$partContent->setParent($parent);
		$partContent->loadDynamicVars();

		return $partContent;
	}
}