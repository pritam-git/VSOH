<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/TinyMCE/Css/Interface.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Interface.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_TinyMCE_Css_Interface
 *
 *
 */
interface L8M_JQuery_Form_Element_TinyMCE_Css_Interface
{

	/**
	 * get css filename and path
	 *
	 * @param string $elementId
	 * @return string
	 */
	static public function getCssFile($elementId = NULL);
}
