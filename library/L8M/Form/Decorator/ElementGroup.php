<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/ElementGroup.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ElementGroup.php 42 2014-04-17 16:48:27Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_ElementGroup
 *
 *
 */
class L8M_Form_Decorator_ElementGroup extends Zend_Form_Decorator_Abstract
{

	/**
	 * Render
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{
		$element = $this->getElement();
		$elementName = $element->getName();

		return '<div id="' . $elementName . '-group" class="form-element-group">' . $content . '</div>';
	}
}
