<?php
class L8M_Form_Decorator_GoogleReCaptcha extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof L8M_Form_Element_GoogleReCaptcha) {
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

		//$markup = '<input type="text" name="' . $element->getName() . '" id="' . $element->getName() . '" value="' . $element->getValue() . '"' . $attribString . ' />';
		$markup = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
		$markup .= '<div class="g-recaptcha" data-sitekey="' . L8M_Config::getOption('google.reCaptcha.sitekey') . '"' . $attribString . ' id="' . $element->getName() . '"></div>';
		$markup .= '<input type="hidden" id="' . $element->getName() . '" name="' . $element->getName() . '" value="reCAPTCHA" />';
		$markup .= '<input type="hidden" id="' . $element->getName() . '-id" name="' . $element->getName() . '-id" value="reCAPTCHA" />';

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}
}