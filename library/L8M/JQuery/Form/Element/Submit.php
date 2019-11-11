<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/JQuery/Form/Element/Submit.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Submit.php 37 2014-04-10 13:19:03Z nm $
 */

/**
 *
 *
 * L8M_JQuery_Form_Element_Submit
 *
 *
 */
class L8M_JQuery_Form_Element_Submit extends Zend_Form_Element_Submit
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A Zend_Form instance.
	 *
	 * @var Zend_Form
	 */
	protected $_parentForm = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Sets parent form.
	 *
	 * @param  Zend_Form $form
	 * @return L8M_JQuery_Form_Element_Submit
	 */
	public function setParentForm($form = NULL)
	{
		if (!$form ||
			!($form instanceof Zend_Form)) {
			throw new L8M_JQuery_Form_Element_Submit_Exception('Form needs to be a Zend_Form instance.');
		}
		$this->_parentForm = $form;
		return $this;
	}

	/**
	 * Render form element
	 *
	 * @param  Zend_View_Interface $view
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null)
	{
		if (!$this->_parentForm) {
			throw new L8M_JQuery_Form_Element_Submit_Exception('Parent form needs to be specified prior to rendering.');
		}

		ob_start();

?>
<script type="text/javascript">
$(document).ready(
	function() {
		var form = $("#<?php echo $this->_parentForm->getId(); ?>");
		form.submit(
			function() {

				l8mModelFormSubmitFormOkay = true;

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
				if (typeof(jQuery.fn.colorbox) != "undefined") {
					var colorboxAction = '';
					colorboxAction = '/colorboxAction/colorbox';
					$.post(
						form.attr("action") + "/format/html" + colorboxAction,
						form.serialize(),
						function(data){
							$("#<?php echo $this->_parentForm->getId(); ?>").parent('div').parent('div').html(data);
							if (typeof(jQuery.fn.colorbox) != "undefined") {
								jQuery.fn.colorbox.resize();
							}
						},
						"text"
					);
					return false;
				}
			}
		);
	}
);
</script>
<?php
		$content = parent::render($view);
		$content.= ob_get_clean();

		return $content;
	}

}