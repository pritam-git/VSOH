<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/FormHasRequiredElements.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FormHasRequiredElements.php 206 2014-10-22 09:31:01Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_FormRequiredElements
 *
 *
 */
class L8M_Form_Decorator_FormHasRequiredElements extends Zend_Form_Decorator_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private static $_initializedForFormID = array();
	private static $_renderInline = FALSE;

	/**
	 * Constructor
	 *
	 * @param  boolean $renderInline
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($renderInline = FALSE, $options = NULL)
	{

		if (is_bool($renderInline)) {
			self::$_renderInline = $renderInline;
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
	 * Render a form
	 *
	 * Replaces $content entirely from currently set element.
	 *
	 * @param  string $content
	 * @return string
	 */
	public function render($content)
	{

		/**
		 * form
		 */
		$form = $this->getElement();

		if (!isset(self::$_initializedForFormID[$form->getId()]) ||
			self::$_initializedForFormID[$form->getId()] == FALSE) {

			/**
			 * no view, no rendering
			 */
			$viewFromForm = $form->getView();
			if ($viewFromForm) {

				/**
				 * check elements to see if we have required form elements
				 */
				$elements = $form->getElements();
				$hasRequired = FALSE;
				foreach($elements as $element) {

					/**
					 * there is an element that is required
					 */
					if ($element->isRequired()) {
						$hasRequired = TRUE;
					}
				}

				if ($hasRequired) {
					self::$_initializedForFormID[$form->getId()] = TRUE;

					if (Zend_Controller_Front::getInstance()->getRequest()->isXmlHttpRequest() ||
						self::$_renderInline) {

						$content .= '
<script type="text/javascript">
//<![CDATA[
	' . $this->_getJavaScriptForRequired($form, $viewFromForm) . '
//]]>
</script>
						';
					} else {
						$viewFromForm->headScript()->captureStart();

?>
$(document).ready(function() {
	<?php echo $this->_getJavaScriptForRequired($form, $viewFromForm); ?>
});
<?php

						$viewFromForm->headScript()->captureEnd();
					}
				} else {
					self::$_initializedForFormID[$form->getId()] = FALSE;
				}
			}
		}
		return $content;
	}

	private function _getJavaScriptForRequired($form, $viewFromForm)
	{
		$returnValue = '
			var elem = null;
			if ($(\'#l8m_system_back_after_save-label label\').length == 1) {
				elem = \'#l8m_system_back_after_save-label label\';
			} else {
				elem = \'#' . $form->getId() . ' input[type=submit]\';
			}
			$(elem).before(\'<div class="required-form-hint"><span class="required-sign">*</span> ' . $viewFromForm->translate('Pflichtfeld', 'de') . '</div>\');

		';
		return $returnValue;
	}
}