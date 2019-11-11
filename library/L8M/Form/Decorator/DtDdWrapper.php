<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/DtDdWrapper.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: DtDdWrapper.php 67 2014-05-09 13:50:13Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_DtDdWrapper
 *
 *
 */
class L8M_Form_Decorator_DtDdWrapper extends Zend_Form_Decorator_Abstract
{
	/**
	 * Default placement: surround content
	 * @var string
	 */
	protected $_placement = null;

	/**
	 * Render
	 *
	 * Renders as the following:
	 * <dt>$dtLabel</dt>
	 * <dd>$content</dd>
	 *
	 * $dtLabel can be set via 'dtLabel' option, defaults to '\&#160;'
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{
		$elementObjekt = $this->getElement();
		$elementName = $elementObjekt->getName();
		$dtLabel = $this->getOption('dtLabel');
		$returnValue = '';

		if ($elementObjekt instanceof Zend_Form_DisplayGroup) {
			if ($dtLabel ) {
				$returnValue .= '<div id="' . $elementName . '-label">' . $dtLabel . '</div>' . PHP_EOL;
			}
		} else {
			if (NULL === $dtLabel ) {
				$dtLabel = '&#160;';
			}
			$returnValue .= '<div id="' . $elementName . '-label">' . $dtLabel . '</div>' . PHP_EOL;
		}

		$returnValue .= '<div id="' . $elementName . '-element">' . $content . '</div>';

		return $returnValue;
	}
}
