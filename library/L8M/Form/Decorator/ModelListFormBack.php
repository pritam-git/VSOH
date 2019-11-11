<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/ModelListFormBack.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: ModelListFormBack.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_ModelListFormBack
 *
 *
 */
class L8M_Form_Decorator_ModelListFormBack extends Zend_Form_Decorator_Abstract
{

	/**
	 * contains string for backbutton
	 *
	 * @var string
	 */
	private $_url = NULL;

	/**
	 * Constructor
	 *
	 * @param  string $options
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($url = NULL, $options = NULL)
	{

		if ($url !== NULL &&
			is_string($url)) {

			$this->_url = $url;
		}

		parent::__construct($options);
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders backbutton in form.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{

		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		if ($viewFromMVC) {
			if (!$this->_url) {
				$formParamValues = NULL;
				if (isset($viewFromMVC->formParamValues)) {
					$formParamValues = $viewFromMVC->formParamValues;
				}
				$this->_url = $viewFromMVC->url(array('action'=>'list')) . $formParamValues;
			}

			ob_start();

?>
<a href="<?php echo $this->_url; ?>" class="moddellistform-back"><?php echo $viewFromMVC->translate('Back')?></a>
<?php

			$content .= ob_get_clean();
		}

		return $content;
	}

}