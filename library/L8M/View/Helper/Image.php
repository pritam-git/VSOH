<?php
/** *
 * @copyright  (c) 2010 Basti-sama <http://basti.4ym.org/>
 * @version    $Id: Image.php 7 2014-03-11 16:18:40Z nm $
 * Helper for generating valid image HTML tags.
 *
 * @thanks to Basti @ http://web-union.de/364
 */
class L8M_View_Helper_Image extends Zend_View_Helper_HtmlElement
{
	/**
	 * HTML attributes.
	 *
	 * @var array
	 */
	protected $_attributes = array();

	/**
	 * Creates an HTML image tag.
	 *
	 * @param string $src The image source URL
	 * @param integer $width Width attribute
	 * @param integer $height Height attribute
	 * @param string $title alt+title attribute content
	 * @param array $additionalAttribues Any additional attributes
	 *
	 * @return Recipe_Helper_View_Image
	 */
	public function image($src, $width = null, $height = null, $title = null, array $additionalAttribues = null)
	{
		$alt = $title;
		$attribues = compact('src', 'width', 'height', 'title', 'alt');
		if($additionalAttribues !== null)
			$attribues += $additionalAttribues;
		$this->setAttributes($attribues);

		return $this;
	}

	/**
	 * Set the attributes for image tag.
	 *
	 * @param array $attributes Key-Value pairs
	 *
	 * @return Recipe_Helper_View_Image
	 */
	public function setAttributes(array $attributes)
	{
		$this->_attributes = $attributes;
		return $this;
	}

	/**
	 * Returns the attributes.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->_attributes;
	}

	/**
	 * Generates the HTML tag for an image.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return '<img'.$this->_htmlAttribs($this->getAttributes()).$this->getClosingBracket();
	}
}