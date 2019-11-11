<?php
class L8M_JQuery_Form_Decorator_MultiTab extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof L8M_JQuery_Form_Element_MultiTab) {
			// wir wollen nur das Element darstellen
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}

		$formElements = $element->getElements();
		$formElementName = $element->getName() . '_tabs';
		$formLists = '';
		$formContentDivs = '';
		$cssClassAddition = '';
		$cssCurrent = 'current';

		foreach ($formElements as $key => $formElement) {
			$cssClassLi = '';
			$cssClassDiv = '';

			if (Zend_Registry::isRegistered('jQueryTools') &&
				Zend_Registry::get('jQueryTools') !== FALSE) {

				switch (get_class($formElement)) {
					case 'Zend_Form_Element_Text':
						$cssClassAddition = ' multitabtext';
						break;
					case 'Zend_Form_Element_Textarea':
						$cssClassAddition = ' multitabtextarea';
						break;
					case 'L8M_JQuery_Form_Element_TinyMCE':
						$cssClassAddition = ' multitabtinymce';
						break;
					default:
						$cssClassAddition = '';
				}
				$cssClassLi = ' class="' . $cssCurrent . '"';
				$cssClassDiv = ' class="' . $cssClassAddition . '"';
			}

			$formLists .= '<li><a href="#' . $formElementName . '-' . $key . '"' . $cssClassLi . '>' . $key . '</a></li>';
			$formContentDivs .= '<div id="' . $formElementName . '-' . $key . '"' . $cssClassDiv . '>' . $formElement . '</div>';
			$cssCurrent = '';

		}

		if (Zend_Registry::isRegistered('jQueryTools') &&
				Zend_Registry::get('jQueryTools') !== FALSE) {

			$markup = '<div id="' . $formElementName. '" class="multitabs">' .
					  '<ul class="navi multitabflowtabs">' .
					  $formLists .
					  '</ul>' .
					  '<div class="multitabflowpanes' . $cssClassAddition . '">' .
					  '<div class="items">' .
					  $formContentDivs .
					  '</div>' .
					  '</div>' .
					  '</div>';
		} else {
			$markup = '<div id="' . $formElementName. '" class="multitabs">' .
					  '<ul>' .
					  $formLists .
					  '</ul>' .
					  $formContentDivs .
					  '</div>';
		}

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}
}