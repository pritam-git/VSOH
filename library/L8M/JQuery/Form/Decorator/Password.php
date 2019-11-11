<?php
class L8M_JQuery_Form_Decorator_Password extends Zend_Form_Decorator_Abstract
{
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof L8M_JQuery_Form_Element_Password) {
			// wir wollen nur das Element
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}

		$markup = $this->_getMarkup($element);

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}

	/**
	 * Generate Markup.
	 *
	 * @param L8M_JQuery_Form_Element_Password $element
	 */
	protected function _getMarkup($element)
	{

		$content = NULL;
		ob_start();

?>
<div class="box password">
	<input type="password" name="<?php echo $element->getName(); ?>" id="<?php echo $element->getName(); ?>" value="<?php echo $element->getValue(); ?>" />
	<a href="" class="recycle <?php echo $element->getName(); ?>_link"><?php echo $element->getView()->translate('Wiederherstellen', 'de'); ?></a>
	<input type="text" name="<?php echo $element->getName(); ?>_default" id="<?php echo $element->getName(); ?>_default" value="<?php echo $element->getDefaultValue(); ?>" class="hidden" />
	<div class="<?php echo $element->getName(); ?>_time password-time"></div>
	<script type="text/javascript">
	//<![CDATA[

		$(document).ready(function(){
			$('#<?php echo $element->getName(); ?>').pwdstr('div.<?php echo $element->getName(); ?>_time', new Object({
				thePasswordIs: '<?php echo $element->getView()->translate('The password is forceable in'); ?>',
				oneYear: '<?php echo $element->getView()->translate('1 year'); ?>',
				xYears: '<?php echo $element->getView()->translate('years'); ?>',
				oneMonth: '<?php echo $element->getView()->translate('1 month'); ?>',
				xMonths: '<?php echo $element->getView()->translate('months'); ?>',
				oneDay: '<?php echo $element->getView()->translate('1 day'); ?>',
				xDays: '<?php echo $element->getView()->translate('days'); ?>',
				oneHour: '<?php echo $element->getView()->translate('1 hour'); ?>',
				xHours: '<?php echo $element->getView()->translate('hours'); ?>',
				oneMinute: '<?php echo $element->getView()->translate('1 minute'); ?>',
				xMinutes: '<?php echo $element->getView()->translate('minutes'); ?>',
				oneSecond: '<?php echo $element->getView()->translate('1 second'); ?>',
				xSeconds: '<?php echo $element->getView()->translate('seconds'); ?>',
				lessThan: '<?php echo $element->getView()->translate('less than one second'); ?>'
			}));

			$('div.password a.<?php echo $element->getName(); ?>_link').click(function() {
				$('#<?php echo $element->getName(); ?>').val($('#<?php echo $element->getName(); ?>_default').val());
				$('#<?php echo $element->getName(); ?>').parent().removeClass('changed');
				$('div.<?php echo $element->getName(); ?>_time').html('');

				return false;
			});

			$('#<?php echo $element->getName(); ?>').keyup(function() {
				if ($('#<?php echo $element->getName(); ?>').val() != $('#<?php echo $element->getName(); ?>_default').val()) {
					$('#<?php echo $element->getName(); ?>').parent().addClass('changed');
				}
			});

			$('#<?php echo $element->getName(); ?>').bind('input propertychange', function() {
				if ($('#<?php echo $element->getName(); ?>').val() != $('#<?php echo $element->getName(); ?>_default').val()) {
					$('#<?php echo $element->getName(); ?>').parent().addClass('changed');
				}
			});

			$('#<?php echo $element->getName(); ?>').focus(function() {
				if ($('#<?php echo $element->getName(); ?>').val() == $('#<?php echo $element->getName(); ?>_default').val()) {
					$('#<?php echo $element->getName(); ?>').val('');
				}
			});

			$('#<?php echo $element->getName(); ?>').blur(function() {
				if ($('#<?php echo $element->getName(); ?>').val() == '') {
					$('#<?php echo $element->getName(); ?>').val($('#<?php echo $element->getName(); ?>_default').val());
					$('#<?php echo $element->getName(); ?>').parent().removeClass('changed');
					$('div.<?php echo $element->getName(); ?>_time').html('');
				}
			});
		});

	//]]>
	</script>
</div>
<?php

		$content = ob_get_clean();
		return $content;
	}
}