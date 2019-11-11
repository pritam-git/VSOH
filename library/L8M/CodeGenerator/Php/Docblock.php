<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/CodeGenerator/Php/Docblock.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Docblock.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_CodeGenerator_Php_Docblock
 *
 *
 */
class L8M_CodeGenerator_Php_Docblock extends Zend_CodeGenerator_Php_Docblock
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An integer representing the maximum length taken up by a tag name added
	 * to this L8M_CodeGenerator_Php_Docblock instance.
	 *
	 * @var int
	 */
	protected $_maxTagLength = NULL;

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_CodeGenerator_Php_Docblock instance.
	 *
	 * @return void
	 */
	public function _init()
	{
		parent::_init();
		$this->setShortDescription('L8M' . self::LINE_FEED);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Sets tag.
     *
     * @param  array|L8M_CodeGenerator_Php_Docblock_Tag $tag
     * @return L8M_CodeGenerator_Php_Docblock
     */
    public function setTag($tag)
    {
        if (is_array($tag)) {
            $tag = new L8M_CodeGenerator_Php_Docblock_Tag($tag);
        }

        if (!($tag instanceof Zend_CodeGenerator_Php_Docblock_Tag)) {
        	throw new L8M_CodeGenerator_Php_Docblock_Exception('Tag needs to be specified as an array or a Zend_CodeGenerator_Php_Dockblock_Tag instance.');
        }

        $this->_maxTagLength = max(array(
        	$this->_maxTagLength,
        	strlen($tag->getName()),
        ));

        return parent::setTag($tag);
    }

    /**
     * generate()
     *
     * @return string
     */
    public function generate()
    {
        if (!$this->isSourceDirty()) {
            return $this->_docCommentize($this->getSourceContent());
        }

        $output  = '';
        if (null !== ($sd = $this->getShortDescription())) {
            $output .= $sd . self::LINE_FEED . self::LINE_FEED;
        }
        if (null !== ($ld = $this->getLongDescription())) {
            $output .= $ld . self::LINE_FEED . self::LINE_FEED;
        }

        foreach ($this->getTags() as $tag) {
            $output .= $tag->generate($this->_maxTagLength) . self::LINE_FEED;
        }

        return $this->_docCommentize(trim($output));
    }

}