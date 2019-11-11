<?php

/**
 * L8M
 *
 *
 * @filesource /library/Mandala/HTML5/Element.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HTML5.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * Mandala_HTML5_Element
 *
 *
 */
class Mandala_HTML5_Element
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * tag type
	 * @var string
	 */
	private $_tag = 'div';

	/**
	 * no end tag flag
	 * @var string
	 */
	protected $_hasNoEndTag = FALSE;

	/**
	 * inner html
	 * @var string
	 */
	private $_content = '';

	/**
	 * html attributes
	 * @var array$input
	 */
	private $_attributes = array(
		'id'=>'',
		'class'=>array(),
		'style'=>array(),
	);

	/**
	 *
	 *
	 * Class Method
	 *
	 *
	 */

	/**
	 * constructor for all html5 element
	 *
	 * @param string $tag tag name
	 * @param string $class html5 class attribute
	 * @param string $id html5 id attribute
	 * @return Mandala_HTML5_Element
	 */
	public function __construct($tag, $class = NULL, $id = NULL) {

		if (!is_string($tag)) {
			trigger_error('Every Mandala element must have a tag!', E_USER_ERROR);
		}

		$this->_tag = $tag;
		$this->setClass($class);
		$this->setID($id);

		return $this;

	}

	/**
	 * set html attribute
	 * @param string $key
	 * @param mixed $value
	 * @return Mandala_HTML5_Element
	 */
	public function setAttr($key, $value) {

		if (!is_array($value)){
			$value = trim($value);
		}

		$this->_attributes[$key] = $value;

		return $this;
	}

	/**
	 * get html attribute
	 * @return mixed
	 */
	public function getAttr($key) {

		return $this->_attributes[$key];
	}
	/**
	 * set html aria attribute
	 * @param string $key
	 * @param mixed $value
	 * @return Mandala_HTML5_Element
	 */
	public function setAria($key, $value) {

		$key = 'aria-' . $key;

		$this->_attributes[$key] = trim($value);

		return $this;
	}

	/**
	 * get html aria attribute
	 * @return mixed
	 */
	public function getAria($key) {

		$key = 'aria-' . $key;

		return $this->_attributes[$key];
	}

	/**
	 * set html data attribute
	 * @param string $key
	 * @param mixed $value
	 * @return Mandala_HTML5_Element
	 */
	public function setData($key, $value) {

		$key = 'data-' . $key;

		$this->_attributes[$key] = trim($value);

		return $this;
	}

	/**
	 * get html data attribute
	 * @return mixed
	 */
	public function getData($key) {

		$key = 'data-' . $key;

		return $this->_attributes[$key];
	}


	/**
	 * set id
	 * @param string $id
	 * @return Mandala_HTML5_Element
	 */
	public function setID($id) {

		$this->setAttr('id', $id);

		return $this;
	}

	/**
	 * get id
	 * @return string
	 */
	public function getID() {

		return $this->getAttr('id');
	}

	/**
	 * set html class
	 * @param string $class
	 * @return Mandala_HTML5_Element
	 */
	public function setClass($class) {

		$classArray = explode(' ', trim($class));
		$this->setAttr('class', $classArray);

		return $this;
	}

	/**
	 * add html class
	 * @param string $class
	 * @return Mandala_HTML5_Element
	 */
	public function addClass($class) {

		$classArray = explode(' ', trim($class));

		foreach ($classArray as $class) {
			$this->_attributes['class'][] = $class;
		}

		return $this;

	}

	/**
	 * set html inline style
	 * @param string $key,
	 *
	 * @param string $value
	 * @return Mandala_HTML5_Element
	 */
	public function setStyle($key, $value) {

		$this->_attributes['style'][$key] = trim($value);

		return $this;
	}

	/**
	 * append html5 content
	 * @param string $content
	 * @return Mandala_HTML5_Element
	 */
	public function append($content) {

		$this->_content .= $content;
		return $this;
	}

	/**
	 * prepend html5 content
	 * @param string $content
	 * @return Mandala_HTML5_Element
	 */
	public function prepend($content) {

		$this->_content = $content . $this->_content;
		return $this;
	}

	/**
	 * set html5 content
	 * @param string $content
	 * @return Mandala_HTML5_Element
	 */
	public function setContent($content){

		$this->_content = $content;
		return $this;
	}

	/**
	 * get attribute output for rendering
	 * @return void
	 */
	private function _getAttributeOutput() {

		foreach ($this->_attributes as $key=>$value) {

			switch (gettype($value)) {
				case 'array':
					if (count($this->_attributes[$key]) > 0) {

						if (PRJ_Library::isAssoc($this->_attributes[$key])) {

							ksort($this->_attributes[$key]);

							foreach ($this->_attributes[$key] as $assocKey=>$assocValue) {
								if ($assocValue == NULL ||
									$assocValue == '') {
									unset($this->_attributes[$key][$assocKey]);
								}
							}

							if (count($this->_attributes[$key]) > 0) {
								$this->_attributes[$key] = implode('; ',
									array_map(
										function($v, $k) {
											return sprintf('%s: %s', $k, $v);
										},
										$this->_attributes[$key],
										array_keys($this->_attributes[$key])
									)
								);
							} else {
								unset($this->_attributes[$key]);
							}
						} else {
							for($i = 0; $i < count($this->_attributes[$key]); $i++) {
								if ($this->_attributes[$key][$i] == NULL ||
									trim($this->_attributes[$key][$i]) == '') {
										unset($this->_attributes[$key][$i]);
									}
							}

							if (count($this->_attributes[$key]) > 0) {
								$this->_attributes[$key] = trim(implode(' ', $value));
							} else {
								unset($this->_attributes[$key]);
							}
						}
					} else {
						unset($this->_attributes[$key]);
					}
					break;
				case 'boolean':
					if ($this->_attributes[$key]) {
						$this->_attributes[$key] = 'true';
					} else {
						$this->_attributes[$key] = 'false';
					}
					break;
				default:
					if (trim($this->_attributes[$key]) == '' &&
						$key != 'href') {
						unset($this->_attributes[$key]);
					}
			}
		}

		$attributeOutput = implode(' ',
			array_map(
				function($v, $k) {
					return sprintf('%s="%s"', $k, $v);
				},
				$this->_attributes,
				array_keys($this->_attributes)
			)
		);

		return trim($attributeOutput);
	}

	/**
	 * render html
	 * @return string
	 */
	protected function _render() {

		$returnValue = '<' . $this->_tag . ' ' . $this->_getAttributeOutput();

		if ($this->_hasNoEndTag) {
			$returnValue .= '/>';
		} else {
			$returnValue .= '>' . $this->_content . '</' . $this->_tag . '>';
		}

		return $returnValue;

	}

	public function __toString() {
		return $this->_render();
	}

}