<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/MediaImage.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaImage.php 511 2016-08-29 15:54:24Z nm $
 */

/**
 *
 *
 * Default_Model_MediaImage
 *
 *
 */
class Default_Model_MediaImage extends Default_Model_Base_MediaImage
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array of HTML attributes that can be set for the tag returned by
	 * getTag().
	 *
	 * @var array
	 */
	private $_settableHtmlAttributes = array(
		'alt',
		'class',
		'id',
		'style',
		'property',
	);

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a Default_Model_MediaImage, if specified coordinates and
	 * dimensions then crop image.
	 *
	 * @param unknown_type $coordinateX
	 * @param unknown_type $coordinateY
	 * @param unknown_type $width
	 * @param unknown_type $height
	 * @return Default_Model_MediaImage|Default_Model_MediaImage
	 */
	public function crop($coordinateX = NULL, $coordinateY = NULL, $width = NULL, $height = NULL)
	{
		$image = $this;

		if ($this instanceof Default_Model_MediaImageInstance) {
			$image = Doctrine_Query::create()
				->from('Default_Model_MediaImage mi')
				->addWhere('mi.id = ?', $this->media_image_id)
				->execute(array())
				->getFirst()
			;
		}

		return Default_Service_MediaImage::crop($image, $coordinateX, $coordinateY, $width, $height);
	}

	/**
	 * Returns an XHTML tag.
	 *
	 * @return string
	 */
	public function getTag($withDimensions = TRUE)
	{
		$tag = '<img src="'
			 . $this->getLink()
			 . '"';
		if ($withDimensions) {
			$tag .= ' width="'
				 . $this->width
				 . '" height="'
				 . $this->height
				 . '"';
		}

		if (array_key_exists('alt', $this->_htmlAttributes) &&
			$this->_htmlAttributes['alt'] != '' &&
			($this->description == NULL || $this->description == '')) {

			$tag .= ' ';

		} else {
			$tag .= ' alt="'
				 . htmlentities($this->description)
				 . '"'
			;
		}

		$tag .= $this->_htmlAttributesToString() . ' />';
		return $tag;
	}

	/**
	 * Returns a Default_Model_MediaImageInstance, if specified width and height are
	 * smaller than width and height of this Default_Model_MediaImage instance or
	 * enlarge is set to TRUE, returns self otherwise.
	 *
	 * @param  int $width
	 * @param  int $height
	 * @param  bool $enlarge
	 * @return Default_Model_MediaImage|Default_Model_MediaImageInstance
	 */
	public function maxBox($width = NULL, $height = NULL, $enlarge = FALSE)
	{
		$image = $this;

		if ($this instanceof Default_Model_MediaImageInstance) {
			$image = Doctrine_Query::create()
				->from('Default_Model_MediaImage mi')
				->addWhere('mi.id = ?', $this->media_image_id)
				->execute(array())
				->getFirst()
			;
		}

		return Default_Service_MediaImage::maxBox($image, $width, $height, $enlarge);
	}

	/**
	 * Returns an HTML5 picture tag..
	 *
	 * @param  array $viewPortWidths
	 * @param  boolean $viewPortMax
	 * @param  boolean $fallbackWithDimensions
	 * @return string
	 */
	public function maxBoxPictureTag($viewPortWidths = array(), $viewPortMax = TRUE, $fallbackWithDimensions = TRUE)
	{
		$image = $this;

		if ($this instanceof Default_Model_MediaImageInstance) {
			$image = Doctrine_Query::create()
				->from('Default_Model_MediaImage mi')
				->addWhere('mi.id = ?', $this->media_image_id)
				->execute(array())
				->getFirst()
			;
		}


		$tag = '<picture';
		$tag .= $this->_htmlAttributesToString(array('alt', 'property'));
		$tag .= ' >';
		if ($viewPortMax) {
			$viewPortType = 'max';
		} else {
			$viewPortType = 'min';
		}
		foreach ($viewPortWidths as $viewPort=>$width) {
			$imageInstance = Default_Service_MediaImage::maxBox($image, $width, NULL, FALSE);
			$tag .= '<source media="(' . $viewPortType . '-width: ' . $viewPort . 'px)" srcset="' . $imageInstance->getLink() . '">';
		}
		$tag .= $image->getTag($fallbackWithDimensions);
		$tag .= '</picture>';
		return $tag;
	}

	/**
	 * Returns a Default_Model_MediaImageInstance from the specified
	 * Default_Model_MediaImage instance, resized so it would fit in a box with
	 * the specified maximum width and height.
	 *
	 * @param  int $width
	 * @param  int $height
	 * @return Default_Model_MediaImage|Default_Model_MediaImageInstance
	 */
	public function resize($width = NULL, $height = NULL)
	{
		$image = $this;

		if ($this instanceof Default_Model_MediaImageInstance) {
			$image = Doctrine_Query::create()
				->from('Default_Model_MediaImage mi')
				->addWhere('mi.id = ?', $this->media_image_id)
				->execute(array())
				->getFirst()
			;
		}

		return Default_Service_MediaImage::resize($image, $width, $height);
	}

	/**
	 * Returns a Default_Model_MediaImage, if specified degrees then
	 * rotated with degrees.
	 *
	 * @param  int $degrees
	 * @param  int $backgroundColorRed
	 * @param  int $backgroundColorGreen
	 * @param  int $backgroundColorBlue
	 * @param  int $backgroundColorAlpha
	 * @return Default_Model_MediaImage|Default_Model_MediaImage
	 */
	public function rotate($degrees = NULL, $backgroundColorRed = 0, $backgroundColorGreen = 0, $backgroundColorBlue = 0, $backgroundColorAlpha = 0)
	{
		$image = $this;

		if ($this instanceof Default_Model_MediaImageInstance) {
			$image = Doctrine_Query::create()
				->from('Default_Model_MediaImage mi')
				->addWhere('mi.id = ?', $this->media_image_id)
				->execute(array())
				->getFirst()
			;
		}

		return Default_Service_MediaImage::rotate($image, $degrees, $backgroundColorRed, $backgroundColorGreen, $backgroundColorBlue, $backgroundColorAlpha);
	}

	/**
	 * Attempts to set an HTML attribute for rendering the associated tag.
	 *
	 * @param  string $attribute
	 * @param  string $value
	 * @return Default_Model_MediaImage
	 */
	public function setHtmlAttribute($attribute = NULL, $value = NULL)
	{
		if (!is_string($attribute)) {
			throw new Default_Model_Media_Exception('Attribute needs to be specified as string.');
		}

		if (!is_string($value)) {
			throw new Default_Model_Media_Exception('Specified attribute is not settable.');
		}

		/**
		 * @todo filter attribute and value (strip out invalid characters)
		 */

		/**
		 * check whether the specified attribute can be set
		 */
		if (!in_array($attribute, $this->_settableHtmlAttributes)) {
			throw new Default_Model_Media_Exception('Specified attribute is not settable.');
		}

		$this->_htmlAttributes[$attribute] = $value;

		return $this;
	}

	/**
	 * Attempts to set the specified attributes.
	 *
	 * @param  array $attributes
	 * @return Default_Model_MediaImage
	 */
	public function setHtmlAttributes($attributes = NULL)
	{
		if (!is_array($attributes)) {
			throw new Default_Model_Media_Exception('Attributes needs to be specified as an array.');
		}

		$this->_htmlAttributes = array();

		/**
		 * iterate over attributes if the attribute array is not empty
		*/
		if (count($attributes)>0) {
			foreach($attributes as $attribute=>$value) {
				$this->setHtmlAttribute($attribute, $value);
			}
		}

		return $this;
	}
}