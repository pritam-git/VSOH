<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/View/Helper/FormTinyMCE.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FormTinyMCE.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_View_Helper_FormTinyMCE
 *
 *
 */
class L8M_JQuery_View_Helper_FormTinyMCE extends L8M_JQuery_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders TinyMCE.
	 *
	 * @param  int $id
	 * @param  string $value
	 * @return string
	 */
	public function formTinyMCE($id, $value = null, $attribs = null)
	{

		/**
		 * change CSS-Class for PRJ_View_Helper_TinyMCE-Placeholder: so it would not be replaced in ContentInjector
		 */
		if (preg_match_all('|<div class="sysviewhelper l8m-object"[^>]+>###:(.*?):###</div>|is', $value, $match)) {
			$matchingPatterns = $match[0];
			$matchingStrings = $match[1];

			for ($i = 0; $i < count($matchingPatterns); $i++) {
				$replaceWith = str_replace('sysviewhelper l8m-object', 'tinymce-backend sysviewhelper l8m-object', $matchingPatterns[$i]);
				$value = str_replace($matchingPatterns[$i], $replaceWith, $value);
			}
		}

		// build the element
		$xhtml = '<div class="tinymce" id="'
			   . $this->view->escape($id)
			   . '">'
			   . $value
			   . '</div>'
		;

		return $xhtml;
	}

}