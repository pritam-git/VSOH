<?php
class L8M_JQuery_Form_Decorator_Date extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof L8M_JQuery_Form_Element_Date) {
			// wir wollen nur das Element
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}

		$attribString = NULL;
		$attribs = $element->getAttribs();
		if (array_key_exists('helper', $attribs)) {
			unset($attribs['helper']);
		}
		foreach ($attribs as $key => $value) {
			$attribString .= ' ' . $key . '="' . $value . '"';
		}

		$markup = '<input type="text" name="' . $element->getName() . '" id="' . $element->getName() . '" value="' . $element->getValue() . '"' . $attribString . ' />';

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}
}