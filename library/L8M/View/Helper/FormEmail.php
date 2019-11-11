<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/FormEmail.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FormEmail.php 73 2014-05-14 17:21:43Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_FormEmail
 *
 *
 */
class L8M_View_Helper_FormEmail extends Zend_View_Helper_FormElement
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
	public function formEmail($name, $value = null, $attribs = null)
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

		$xhtml = '<input type="email"'
				. ' name="' . $this->view->escape($name) . '"'
				. ' id="' . $this->view->escape($id) . '"'
				. ' value="' . $this->view->escape($value) . '"'
				. $disabled
				. $this->_htmlAttribs($attribs)
				. $endTag;

		return $xhtml;
	}
}
