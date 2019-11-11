<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/ElementContentPrepend.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ElementContentPrepend.php 513 2016-09-05 10:13:44Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_ElementContentPrepend
 *
 *
 */
class L8M_Form_Decorator_ElementContentPrepend extends Zend_Form_Decorator_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_myContent = array();
	private $_myCssClass = array();

	/**
	 * Constructor
	 * Content needs to be kind of:
	 *     array(
	 *         0 => array(
	 *             'content'=>
	 *             'cssClass'=>
	 *         ),
	 *         1 => array(
	 *             'content'=>
	 *             'cssClass'=>
	 *         ),
	 *     );
	 *
	 * @param  Array|string $content
	 * @param  string $cssClass
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($content = NULL, $cssClass = NULL, $options = NULL)
	{

		if (is_array($content) &&
			count($content) >= 1) {

			$i = 0;
			foreach ($content as $contentItem) {
				if (isset($contentItem['content'])) {
					$this->_myContent[$i] = trim((string) $contentItem['content']);

					if (!isset($contentItem['cssClass'])) {
						$contentItem['cssClass'] = $cssClass;
					}
					$this->_myCssClass[$i] = trim((string) $contentItem['cssClass']);
					if ($this->_myCssClass[$i]) {
						$this->_myCssClass[$i] = ' ' . $this->_myCssClass[$i];
					}
					$i++;
				}
			}
		} else {
			$this->_myContent[0] = trim((string) $content);
			$this->_myCssClass[0] = trim((string) $cssClass);
			if ($this->_myCssClass[0]) {
				$this->_myCssClass[0] = ' ' . $this->_myCssClass[0];
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
			$extraContent .= '<div class="form-element-container form-element-container-has-no-label' . $this->_myCssClass[$i] . ' control-group"><div class="form-element-element form-element-element-content control">' . $this->_myContent[$i] . '</div></div>';
		}
		return $extraContent . $content;
	}
}
