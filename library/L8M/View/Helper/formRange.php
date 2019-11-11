<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/FormRange.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FormRange.php 87 2014-05-14 17:23:58Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_FormRange
 *
 *
 */
class L8M_View_Helper_FormRange extends Zend_View_Helper_FormElement
{
	/**
	 * Generates a 'text' element.
	 *
	 * @access public
	 *
	 * @param string|array $name If a string, the element name.  If an
	 * array, all other parameters are ignored, and the array elements
	 * are used in place of added parameters.
	 *
	 * @param mixed $value The element value.
	 *
	 * @param array $attribs Attributes for the element tag.
	 *
	 * @return string The element XHTML.
	 */
	public function formRange($name, $value = null, $attribs = null)
	{
		$info = $this->_getInfo($name, $value, $attribs);
		extract($info); // name, value, attribs, options, listsep, disable

		// build the element
		$disabled = '';
		if ($disable) {
			// disabled
			$disabled = ' disabled="disabled"';
		}

		// XHTML or HTML end tag?
		$endTag = ' />';
		if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
			$endTag= '>';
		}

		$xhtml = '<input type="range"'
				. ' name="' . $this->view->escape($name) . '"'
				. ' id="' . $this->view->escape($id) . '"'
				. ' value="' . $this->view->escape($value) . '"'
		;
		if (is_array($attribs)) {
			if (array_key_exists('min', $attribs)) {
				$xhtml .= ' min="' . $this->view->escape($attribs['min']) . '"';
			}
			if (array_key_exists('max', $attribs)) {
				$xhtml .= ' max="' . $this->view->escape($attribs['max']) . '"';
			}
			if (array_key_exists('step', $attribs)) {
				$xhtml .= ' step="' . $this->view->escape($attribs['step']) . '"';
			}
			if (array_key_exists('data-buffer', $attribs)) {
				$xhtml .= ' data-buffer="' . $this->view->escape($attribs['data-buffer']) . '"';
			}
		}
		$xhtml .= $disabled
				. $this->_htmlAttribs($attribs)
				. $endTag
		;

		return $xhtml;
	}
}
