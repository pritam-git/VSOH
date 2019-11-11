<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Form/Decorator.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Decorator.php 37 2014-04-10 13:19:03Z nm $
 */


/**
 *
 *
 * L8M_Form_Decorator
 *
 *
 */
class L8M_Form_Decorator extends Zend_Form_Decorator_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Decorate content and/or element
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{
		$boxClass = $this->getOption('boxClass')
				  ? $this->getOption('boxClass')
				  : NULL
		;

		$boxStyle = $this->getOption('boxStyle')
				  ? $this->getOption('boxStyle')
				  : NULL
		;

		$prependContent = $this->getOption('prependContent')
				  ? $this->getOption('prependContent')
				  : NULL
		;

		$appendContent = $this->getOption('appendContent')
				  ? $this->getOption('appendContent')
				  : NULL
		;

		$appendJsFiles = $this->getOption('appendJsFile');


		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		if ($viewFromMVC) {

			if ($appendJsFiles) {

				if (!is_array($appendJsFiles)) {
					$appendJsFiles = array($appendJsFiles);
				}

				foreach ($appendJsFiles as $appendJsFile) {
					if (file_exists(PUBLIC_PATH . $appendJsFile)) {
						$viewFromMVC->headScript()->appendFile($appendJsFile);
					}
				}
			}

			$content = $viewFromMVC->box($prependContent . $content . $appendContent, $boxClass, $boxStyle);
		}

		return $content;
	}

}