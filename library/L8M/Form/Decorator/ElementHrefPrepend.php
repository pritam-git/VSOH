<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/ElementHrefPrepend.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ElementHrefPrepend.php 513 2016-09-05 10:13:44Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_ElementHrefPrepend
 *
 *
 */
class L8M_Form_Decorator_ElementHrefPrepend extends Zend_Form_Decorator_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_myContent = array();
	private $_myHref = array();
	private $_myLinkCssClass = array();
	private $_myElementCssClass = array();

	/**
	 * Constructor
	 * Content needs to be kind of:
	 *     array(
	 *         0 => array(
	 *             'content'=>
	 *             'href'=>
	 *             'linkCssClass'=>
	 *             'cssClass'=>
	 *         ),
	 *         1 => array(
	 *             'content'=>
	 *             'href'=>
	 *             'linkCssClass'=>
	 *             'cssClass'=>
	 *         ),
	 *     );
	 *
	 * @param  Array|string $content
	 * @param  string $href
	 * @param  string $linkCssClass
	 * @param  string $elementCssClass
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($content = NULL, $href = NULL, $linkCssClass = NULL, $elementCssClass = NULL, $options = NULL)
	{

		if (is_array($content) &&
			count($content) >= 1) {

			$i = 0;
			foreach ($content as $contentItem) {
				if (isset($contentItem['content'])) {
					$this->_myContent[$i] = trim((string) $contentItem['content']);

					if (!isset($contentItem['href'])) {
						$contentItem['href'] = $href;
					}
					$this->_myHref[$i] = trim((string) $contentItem['href']);

					if (!isset($contentItem['linkCssClass'])) {
						$contentItem['linkCssClass'] = $linkCssClass;
					}
					$this->_myLinkCssClass[$i] = trim((string) $contentItem['linkCssClass']);
					if ($this->_myLinkCssClass[$i]) {
						$this->_myLinkCssClass[$i] = ' class="' . $this->_myLinkCssClass[$i] . '"';
					}

					if (!isset($contentItem['cssClass'])) {
						$contentItem['cssClass'] = $elementCssClass;
					}
					$this->_myElementCssClass[$i] = trim((string) $contentItem['cssClass']);
					if ($this->_myElementCssClass[$i]) {
						$this->_myElementCssClass[$i] = ' ' . $this->_myElementCssClass[$i];
					}
					$i++;
				}
			}
		} else {
			$this->_myContent[0] = trim((string) $content);
			$this->_myHref[0] = trim((string) $href);
			$this->_myLinkCssClass[0] = trim((string) $linkCssClass);
			if ($this->_myLinkCssClass[0]) {
				$this->_myLinkCssClass[0] = ' class="' . $this->_myLinkCssClass[0] . '"';
			}
			$this->_myElementCssClass[0] = trim((string) $elementCssClass);
			if ($this->_myElementCssClass[0]) {
				$this->_myElementCssClass[0] = ' ' . $this->_myElementCssClass[0];
			}
		}

		parent::__construct($options);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	/**
	 * Render
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{
		$extraContent = NULL;
		for ($i = 0; $i < count($this->_myContent); $i++) {
			$extraContent .= '<div class="form-element-container form-element-container-has-no-label' . $this->_myElementCssClass[$i] . ' control-group"><div class="form-element-element form-element-element-href control"><a href="' . $this->_myHref[$i] . '"' . $this->_myLinkCssClass[$i] . '>' . $this->_myContent[$i] . '</a></div></div>';
		}
		return $extraContent . $content;
	}
}
