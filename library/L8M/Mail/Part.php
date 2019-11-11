<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mail/Part.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Part.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mail_Part
 *
 *
 */
class L8M_Mail_Part
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

    /**
     * The content of the mail part.
     *
     * @var string
     */
    protected $_content = NULL;

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
     * @var L8M_Mail
     */
    protected $_mail = NULL;

    /**
     *
     *
     * Class Constructor
     *
     *
     */

    /**
     * Constructs L8M_Mail_Part instance
     *
     * @return L8M_Mail_Part
     *
     */
    public function __construct($options = NULL)
    {
        if ($options instanceof L8M_Mail) {
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
     * Sets reference to L8M_Mail instance owning this L8M_Mail_Part
     * instance.
     *
     * @param  L8M_Mail               $parent
     * @return L8M_Mail_Part
     */
    public function setParent($parent = NULL)
    {
    	if (!$parent ||
    		!($parent instanceof L8M_Mail)) {
    		throw new L8M_Mail_Part_Exception('Could not set parent as parent needs to inherit from L8M_Mail.');
   		}
   		$this->_mail = &$parent;
   		return $this;
    }

    /**
     * Sets headline of this mail part.
     *
     * @param  string       $headline
     * @return L8M_Mail_Part
     */
    public function setHeadline($headline = NULL)
    {
    	$headline = trim(strip_tags((string) $headline));
    	if (!is_string($headline)) {
            throw new L8M_Mail_Part_Exception('Headline needs to be a string.');
        }
    	$this->_headline = $headline;
        return $this;
    }

    /**
     * Sets content of this mail part.
     *
     * @param  string       $content
     * @return L8M_Mail_Part
     */
    public function setContent($content = NULL)
    {
    	$content = trim(strip_tags((string) $content));
        if (!is_string($content)) {
        	throw new L8M_Mail_Part_Exception('Content needs to be a string.');
        }
        $this->_content = $content;
        return $this;
    }

    /**
     * Sets content from source.
     *
     * @param  string                $source
     * @return L8M_Mail_Part
     */
    public function setContentFromSource($source = NULL)
    {
    	$source = (string) $source;
        if (!file_exists($source) ||
            !is_file($source) ||
            !is_readable($source)) {
            throw new L8M_Mail_Part_Exception('Could not set content from source as source does not exist, is not readable or is not a file.');
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
     * L8M_Mail_Partinstance.
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
    public function getHeadline()
    {
        return $this->_headline;
    }

    /**
     * Returns content of this mail part.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
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
     * @param  string             $message
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
     * @return L8M_Mail_Part
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
     * Renders L8M_Mail_Part in specified mode.
     *
     * @param  string $mode
     * @param  int    $position
     * @return string
     */
    public function to($mode = NULL, $position = NULL)
    {
        $mode = trim(strtolower(((string) $mode)));
        if (!in_array($mode, array(self::RENDER_TEXT,
                                   self::RENDER_HTML))) {
            throw new L8M_Mail_Part_Exception('Can not render to unknown mode.');
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
     * @param  int    $position
     * @return string
     */
    protected function _renderAll($mode = self::RENDER_TEXT, $position = NULL)
    {
    	return $this->_renderTop($mode, $position) .
               $this->_renderHeadline($mode) .
               $this->_renderContent($mode) .
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
?>
<?php
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
     * @param  string       $mode
     * @return string
     */
    protected function _renderParagraphs($paragraphs = NULL, $mode = self::RENDER_TEXT, $onlyContent = FALSE)
    {
        if (!is_array($paragraphs) &&
            !is_string($paragraphs)) {
			return NULL;
        }
        if (is_string($paragraphs)) {
            $paragraphs = strip_tags($paragraphs);
            $paragraphs = trim($paragraphs);
            $paragraphs = explode(PHP_EOL, $paragraphs);
        }
        if (is_array($paragraphs)) {
            $paragraphCount = count($paragraphs);
            if ($paragraphCount>0) {
                ob_start();
                if ($mode == self::RENDER_HTML) {
                    $currentParagraph = 1;
                    foreach($paragraphs as $paragraph) {
                        $currentParagraph++;
                        $paragraph = $this->utf8Decode($paragraph);
                        $paragraph = $this->escape($paragraph);
                        /**
                         * @todo wrap links in A tag
                         */
?>
<?php
                    }
                }
                if ($mode == self::RENDER_TEXT) {
                    foreach($paragraphs as $paragraph) {
                        $paragraph = $this->utf8Decode($paragraph);
                        echo $paragraph . PHP_EOL . PHP_EOL;
                    }
                }
                return ob_get_clean();
            }
        }
        return NULL;
    }

	/**
     * Renders content of this mail part.
     *
     * @param  string $mode
     * @return string
     */
    protected function _renderContent($mode = self::RENDER_TEXT)
    {
    	$renderedContent = $this->_renderParagraphs($this->getContent(), $mode);
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
        return htmlentities($content);
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
}