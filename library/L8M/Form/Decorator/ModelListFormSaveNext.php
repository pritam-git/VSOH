<?php

/**
 * L8M
 *
 * @filesource /library/L8M/Form/Decorator/ModelListFormSaveNext.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: ModelListFormSaveNext.php 53 2014-04-24 06:32:54Z nm $
 */

/**
 *
 *
 * L8M_Form_Decorator_ModelListFormSaveNext
 *
 *
 */
class L8M_Form_Decorator_ModelListFormSaveNext extends Zend_Form_Decorator_Abstract
{

	/**
	 * contains string for form ID
	 *
	 * @var string
	 */
	private $_formId = NULL;

	/**
	 * contains boolean whether to render button or not
	 *
	 * @var boolean
	 */
	private $_renderMe = TRUE;

	/**
	 * contains boolean whether edit-string is rendered or add-string
	 *
	 * @var boolean
	 */
	private $_useEditString = FALSE;

	/**
	 * Constructor
	 *
	 * @param  string $formId
	 * @param  boolean $renderMe
	 * @param  boolean $useEditString
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public function __construct($formId = NULL, $renderMe = TRUE, $useEditString = FALSE, $options = NULL)
	{

		if ($formId !== NULL &&
			is_string($formId)) {

			$this->_formId = $formId;
		}

		if ($renderMe !== NULL &&
			is_bool($renderMe)) {

			$this->_renderMe = $renderMe;
		}

		if ($useEditString !== NULL &&
			is_bool($useEditString)) {

			$this->_useEditString = $useEditString;
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
		if ($this->_renderMe &&
			$viewFromMVC) {

			$viewFromMVC->headScript()->captureStart();

?>
function formsubmit<?php echo $this->_formId?>()
{
	var actionString = $('#<?php echo $this->_formId?>').attr('action');
	if (actionString == '') {
		actionString = this.document.location.href + '&backToForm=true';
	} else {
		actionString = actionString + '?backToForm=true';
	}

	$('div.tinymce').each(function(i) {
		var editorContent = tinyMCE.get(this.id).getContent();
		if ($('#' + this.id + '_systeminput').length > 0) {
			$('#' + this.id + '_systeminput').val(editorContent);
		} else {
			$('input[name=' + this.id + ']').remove();
			$('#' + this.id).after('<textarea id="' + this.id + '_systeminput" name="' + this.id + '" class="hidden"></textarea>');
			$('#' + this.id + '_systeminput').val(editorContent);
		}
	});

	l8mModelFormSubmitFormOkay = true;

	$('#<?php echo $this->_formId?>')
		.attr('action', actionString)
		.submit()
	;
	return false;
}
<?php

			$viewFromMVC->headScript()->captureEnd();


			if ($this->_useEditString) {
				$linkString = $viewFromMVC->translate('Speichern & nächsten Datensatz bearbeiten', 'de');
			} else {
				$linkString = $viewFromMVC->translate('Speichern & nächsten Datensatz hinzufügen', 'de');
			}

			/**
			 * capture start
			 */
			ob_start();
?>
<a href="" class="moddellistform-back save" onclick="formsubmit<?php echo $this->_formId?>(); return false;"><?php echo $linkString; ?></a>
<?php

			/**
			 * capture end
			 */
			$content .= ob_get_clean();
		}

		return $content;
	}

}