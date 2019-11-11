<?php
class L8M_JQuery_Form_Decorator_M2N extends Zend_Form_Decorator_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private static $_SORTABLE_Init = FALSE;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * render form element
	 *
	 * @param string $content
	 * @return string
	 */
	public function render($content)
	{
		$element = $this->getElement();
		if (!$element instanceof L8M_JQuery_Form_Element_M2N) {
			// wir wollen nur das Element
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}

		$cssSortable = NULL;
		if ($element->isSortable()) {
			$cssSortable = ' m2n-sortable';
		}

		$loadAction = Doctrine_Query::create()
			->from('Default_Model_Action m')
			->addWhere('m.resource = ? ', array('system.auto-complete.load'))
			->limit(1)
			->execute()
			->getFirst()
		;

		if (($element->isMediaRealtion() || count($element->getOptionValuesForRender()) > 19) &&
			$loadAction) {

			$html = $this->_renderComplexHTML($element, $view);
		} else {
			$html = $this->_renderEasyHTML($element, $view);
		}

		ob_start();

?>
<div class="m2n-form-element box<?php echo $cssSortable; ?>">
	<?php echo $html; ?>
	<ul class="selected-values">
<?php

		$foreignAlias = $element->getForeignAlias();
		foreach ($element->getSelectedOptionValuesForRender() as $itemModel) {
			if ($element->isTranslateable()) {
				$transValues = $itemModel->Translation;
			} else {
				$transValues = array();
			}
			if ($element->hasExtraValue()) {
				$extraValueElement = $this->_renderExtraValue($itemModel->value, L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id));
			} else {
				$extraValueElement = NULL;
			}
			if ($element->hasRealtionValues()) {
				$extraValueElement .= $this->_renderRelationValues(L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id), $itemModel->id);
			}

			echo '<li class="selecte_tab" id="' . $element->getName() . '_' . L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id) . '_selected_id">';
			echo '<input type="text" class="hidden item-value" id="' . $element->getName() . '_selected_input_id_' . L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id) . '" name="' . $element->getName() . '[]" value="' . L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id) . '" data-id="' . L8M_Library::getUsableUrlStringOnly($element->getName() . '_selector_link_' . $itemModel[$foreignAlias]->id . '_li') . '" />';
			echo '<span>' . $itemModel[$foreignAlias][$element->getUseForeignColumn()] . '</span>';
			echo ' ' . $extraValueElement . $this->_renderTranslateable($transValues, L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id)) . ' ';
			echo '<a class="remove ' . $element->getName() . '_remove" href="" id="' . $element->getName() . '_' . L8M_Library::getUsableUrlStringOnly($itemModel[$foreignAlias]->id) . '_selected" onclick="' . $element->getName() . '_remove(this); return false;">' . $view->translate('Remove') . '</a>';
			echo '</li>';
		}
?>
	</ul>
</div>
<script type="text/javascript">
	//<![CDATA[

		$("a.<?php echo $element->getName(); ?>_add").click(function() {

			var m2nValue = '';
			var m2nInfo = '';
			var m2nID = $("#<?php echo $element->getName(); ?>_selector li.active").attr('id');

			if ($("#<?php echo $element->getName(); ?>_selector li").length == 0 &&
				$("#<?php echo $element->getName(); ?>_selector").attr('value') != '' &&
				$("#<?php echo $element->getName(); ?>_" + $("#<?php echo $element->getName(); ?>_selector").attr('value') + '_selected_id').length == 0) {

				m2nValue = $("#<?php echo $element->getName(); ?>_selector").attr('value');
				m2nInfo = $("#<?php echo $element->getName(); ?>_selector option:selected").text();
			} else
			if ($("#<?php echo $element->getName(); ?>_selector li.active").length != 0 &&
				$("#<?php echo $element->getName(); ?>_selector li.active span.value").html().length != 0 &&
				$("#<?php echo $element->getName(); ?>_selector li.active span.info").html().length != 0) {

				m2nValue = $("#<?php echo $element->getName(); ?>_selector li.active span.value").html();
				m2nInfo = $("#<?php echo $element->getName(); ?>_selector li.active span.info").html();
				$("ul#<?php echo $element->getName(); ?>_selector li").removeClass('active');
			}

			if (m2nValue &&
				m2nInfo &&
				m2nID &&
				$("#<?php echo $element->getName(); ?>_selected_input_id_" + m2nValue).length == 0) {

				var transFormElement = ' ';

				var randNumber = Math.floor(Math.random() * 11);
				var currentUnixTime = Math.round((new Date()).getTime() / 1000);
				var referencedElementName = 'ref_' + currentUnixTime.toString() + '_' + randNumber.toString();

<?php

		if ($element->isTranslateable()) {

?>
				transFormElement = ''
				+ '<div id="<?php echo $element->getName(); ?>_tabs-' + m2nValue + '" class="multitabs">'
				+   '<ul>'
<?php

			foreach (L8M_Locale::getSupported() as $lang) {

?>
				+      '<li><a href="#<?php echo $element->getName(); ?>_tabs-' + m2nValue.toString() + '-<?php echo $lang; ?>"><?php echo $lang; ?></a></li>'
<?php

			}

?>
				+   '</ul>'
<?php

			foreach (L8M_Locale::getSupported() as $lang) {

?>
				+   '<div id="<?php echo $element->getName(); ?>_tabs-' + m2nValue + '-<?php echo $lang; ?>">'
				+      '<div>'
				+         '<input type="text" name="<?php echo $element->getName(); ?>_tabs_<?php echo $lang; ?>_' + m2nValue.toString() + '" id="<?php echo $element->getName(); ?>_tabs_<?php echo $lang; ?>_' + m2nValue.toString() + '" value="" />'
				+      '</div>'
				+   '</div>'
<?php

			}

?>
				+ '</div>'
				+ ' ';
<?php

		}

		if ($element->hasExtraValue()) {

?>
				transFormElement = ''
				+ '<div id="<?php echo $element->getName(); ?>_tabs_div-' + m2nValue + '" class="extravalue-tab">'
				+    '<input type="text" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + '" id="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + '" value="" />'
				+ '</div>'
				+ ' ';
<?php

		}

		if ($element->hasRealtionValues()) {
			if ($element->hasMultipleRelationM2nValuesRows()) {
				echo 'var mrMVRClass = " border";';
			} else {
				echo 'var mrMVRClass = "";';
			}
?>
				transFormElement = transFormElement
				+ '<div class="relation-values' + mrMVRClass + '">'
				+ '<ul class="relation-values">'
				+ '<li class="relation-value">'
				+ '<table>';
<?php

			if ($element->hasRelation2y()) {

?>
				transFormElement = transFormElement
				+ '<tr>'
				+ '<td>'
				+ '<?php echo $view->translate($element->getRelationValueColumnLablel('reverenced_id')); ?>'
				+ '</td>'
				+ '<td>'
				+ '<select id="' + referencedElementName + '" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['referenced_id'][]" + '" class="unsortable"></select>'
				+ '</td>'
				+ '</tr>';
<?php

			}

			foreach ($element->getRealtionValueColumns() as $relationColumnName => $relationColumnDefinition) {
				$inputClass = NULL;
				if (isset($relationColumnDefinition['type']) &&
					$relationColumnDefinition['type'] == 'date') {

					$inputClass = 'date';
				}
?>
				transFormElement = transFormElement
				+ '<tr>'
				+ '<td>'
				+ '<?php echo $view->translate($element->getRelationValueColumnLablel($relationColumnName)); ?>'
				+ '</td>'
				+ '<td>'
<?php

				if (isset($relationColumnDefinition['type']) &&
					$relationColumnDefinition['type'] == 'boolean') {

?>
				+ '<input type="text" class="hidden <?php echo $inputClass; ?>" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['<?php echo $relationColumnName; ?>'][]" + '" value="0" />'
				+ '<input type="checkbox" class="<?php echo $inputClass; ?>" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['<?php echo $relationColumnName; ?>'][]" + '" value="1" />'
<?php

					} else {

?>
				+ '<input type="text" class="<?php echo $inputClass; ?>" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['<?php echo $relationColumnName; ?>'][]" + '" value="" />'
<?php

				}

?>
				+ '</td>'
				+ '</tr>';
<?php

			}

?>
				transFormElement = transFormElement
				+ '</table>'
				+ '<a href="" class="remove" onclick="<?php echo $element->getName(); ?>_relationValuesRemove(this); return false;"><?php echo $view->translate('Remove'); ?></a>'
				+ '</li>'
				+ '</ul>'
				+ '<a href="" class="add" onclick="<?php echo $element->getName(); ?>_relationValuesAdd(this); return false;"><?php echo $view->translate('Add'); ?></a>'
				+ '</div>';
<?php

		}

?>
				var elementText = ''
				+ '<li class="selecte_tab" id="<?php echo $element->getName(); ?>_' + m2nValue.toString() + '_selected_id">'
				+ '<input type="text" class="hidden item-value" id="<?php echo $element->getName(); ?>_selected_input_id_' + m2nValue.toString() + '" name="<?php echo $element->getName(); ?>[]" value="' + m2nValue.toString() + '" data-id="' + m2nID + '" />'
				+ '<span>' + m2nInfo + '</span>'
				+ transFormElement
				+ '<a class="remove <?php echo $element->getName(); ?>_remove" href="" id="<?php echo $element->getName(); ?>_' + m2nValue.toString() + '_selected" onclick="<?php echo $element->getName(); ?>_remove(this); return false;"><?php echo $view->translate('Remove'); ?></a>'
				+ '</li>';
				$("#<?php echo $element->getName(); ?>-element ul.selected-values").append(elementText);
<?php

		if ($element->isTranslateable()) {

?>
				$('#<?php echo $element->getName(); ?>_tabs-' + m2nValue.toString()).tabs();
<?php

		}

		if ($element->hasRelation2y()) {

?>

				$.get("/system/auto-complete/model-collection/?model=<?php echo $element->getRelation2yModelName(); ?>&id=" + m2nValue.toString(), function(data){
					$("#" + referencedElementName).append(data);
				});
<?php



		}

?>
			}
			$.datepicker.setDefaults( $.datepicker.regional[ "<?php echo L8M_Locale::getLang(); ?>" ] );
			$("#<?php echo $element->getName(); ?>-element ul.selected-values div.relation-values input.date").datepicker({
				dateFormat: 'yy-mm-dd'
			});
			<?php echo $element->getName(); ?>_updateUsed();
			return false;
		});

		function <?php echo $element->getName(); ?>_relationValuesAdd(elem) {
			var m2nValue = $(elem).parent().parent().find('input.item-value').attr('value');

			var randNumber = Math.floor(Math.random() * 11);
			var currentUnixTime = Math.round((new Date()).getTime() / 1000);
			var referencedElementName = 'ref_' + currentUnixTime.toString() + '_' + randNumber.toString();

			var transFormElement = '<li class="relation-value">'
			+ '<table>';
<?php

		if ($element->hasRelation2y()) {

?>
				transFormElement = transFormElement
				+ '<tr>'
				+ '<td>'
				+ '<?php echo $view->translate($element->getRelationValueColumnLablel('reverenced_id')); ?>'
				+ '</td>'
				+ '<td>'
				+ '<select id="' + referencedElementName + '" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['referenced_id'][]" + '" class="unsortable"></select>'
				+ '</td>'
				+ '</tr>';
<?php

		}

		foreach ($element->getRealtionValueColumns() as $relationColumnName => $relationColumnDefinition) {
			$inputClass = NULL;
			if (isset($relationColumnDefinition['type']) &&
				$relationColumnDefinition['type'] == 'date') {

				$inputClass = 'date';
			}
?>
			transFormElement = transFormElement
			+ '<tr>'
			+ '<td>'
			+ '<?php echo $view->translate($element->getRelationValueColumnLablel($relationColumnName)); ?>'
			+ '</td>'
			+ '<td>'
<?php

			if (isset($relationColumnDefinition['type']) &&
				$relationColumnDefinition['type'] == 'boolean') {

?>
			+ '<input type="text" class="hidden <?php echo $inputClass; ?>" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['<?php echo $relationColumnName; ?>'][]" + '" value="0" />'
			+ '<input type="checkbox" class="<?php echo $inputClass; ?>" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['<?php echo $relationColumnName; ?>'][]" + '" value="1" />'
<?php

			} else {

?>
			+ '<input type="text" class="<?php echo $inputClass; ?>" name="<?php echo $element->getName(); ?>_tabs_' + m2nValue.toString() + "_rv['<?php echo $relationColumnName; ?>'][]" + '" value="" />'
<?php

			}

?>
			+ '</td>'
			+ '</tr>';
<?php

		}

?>
			transFormElement = transFormElement
			+ '</table>'
			+ '<a href="" class="remove" onclick="<?php echo $element->getName(); ?>_relationValuesRemove(this); return false;"><?php echo $view->translate('Remove'); ?></a>'
			+ '</li>';

			$(elem).parent().find('ul.relation-values').append(transFormElement);

<?php

		if ($element->hasRelation2y()) {

?>

				$.get("/system/auto-complete/model-collection/?model=<?php echo $element->getRelation2yModelName(); ?>&id=" + m2nValue.toString(), function(data){
					$("#" + referencedElementName).append(data);
				});
<?php

		}

?>

			$.datepicker.setDefaults( $.datepicker.regional[ "<?php echo L8M_Locale::getLang(); ?>" ] );
			$("#<?php echo $element->getName(); ?>-element ul.selected-values div.relation-values input.date").datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#<?php echo $element->getName(); ?>-element ul.selected-values div.relation-values input[type=checkbox]").unbind('click');
			//$("#<?php echo $element->getName(); ?>-element ul.selected-values div.relation-values input[type=checkbox]").click(function(){
			$("#<?php echo $element->getName(); ?>-element ul.selected-values").on('click', 'div.relation-values input[type=checkbox]', function(){
				if ($(this).attr('checked') == 'checked') {
					$(this).parent().children('input[type=text].hidden').remove();
				} else {
					$(this).before('<input type="text" class="hidden" name="' + $(this).attr('name') + '" value="0" />');
				}
			});
		}

		function <?php echo $element->getName(); ?>_relationValuesRemove(elem) {
			$(elem).parent().remove();
		}

		function <?php echo $element->getName(); ?>_remove(item) {
			$("#" + item.id + '_id').remove();
			<?php echo $element->getName(); ?>_updateUsed();
			return false;
		};

		function <?php echo $element->getName(); ?>_updateUsed() {
			$("ul#<?php echo $element->getName(); ?>_selector li").removeClass('active');
			$("#<?php echo $element->getName(); ?>-element div.m2n-form-element-extra ul li").removeClass('used');

			$("#<?php echo $element->getName(); ?>-element ul.selected-values li input[type=text]").each(function (index, domEle) {
				$("#" + $(domEle).attr('data-id')).addClass('used');
			})
			return false;
		};

		function <?php echo $element->getName(); ?>_updateNavi() {
			var actPos = parseInt($("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.prev").attr('rel'));
			var maxResults = parseInt($("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.next").attr('rel'));
			var maxPossibleResults = maxResults + (75 - (maxResults % 75));

			if (actPos == 1) {
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.prev").hide();
			} else {
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.prev").show();
			}


			if (maxPossibleResults / 75 <= actPos) {
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.next").hide();
			} else {
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.next").show();
			}

			if (<?php echo $element->getOptionValuesCountForRender(); ?> < 75) {
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi li:first-child").html('<?php echo $element->getOptionValuesCountForRender() . ' ' .$view->translate('of') . ' ' . $element->getOptionValuesCountForRender(); ?>');
			} else {
				var showItems = actPos * 75;
				if (showItems > <?php echo $element->getOptionValuesCountForRender(); ?>) {
					showItems = <?php echo $element->getOptionValuesCountForRender(); ?>;
				}
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi li:first-child").html(showItems.toString() + ' <?php echo $view->translate('of') . ' ' . $element->getOptionValuesCountForRender(); ?>');
			}

			return false;
		}

		$(document).ready(function(){
			$.datepicker.setDefaults( $.datepicker.regional[ "<?php echo L8M_Locale::getLang(); ?>" ] );
			$("#<?php echo $element->getName(); ?>-element ul.selected-values div.relation-values input.date").datepicker({
				dateFormat: 'yy-mm-dd'
			});
			<?php echo $element->getName(); ?>_updateUsed();
			<?php echo $element->getName(); ?>_updateNavi();

			//$("#<?php echo $element->getName(); ?>-element ul.selected-values div.relation-values input[type=checkbox]").click(function(){
			$("#<?php echo $element->getName(); ?>-element ul.selected-values").on('click', 'div.relation-values input[type=checkbox]', function(){
				if ($(this).attr('checked') == 'checked') {
					$(this).parent().children('input[type=text].hidden').remove();
				} else {
					$(this).before('<input type="text" class="hidden" name="' + $(this).attr('name') + '" value="0" />');
				}
			});
		});

	//]]>
</script>
<?php

		$markup = ob_get_clean() . $this->_renderSortable();

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}

	private function _renderSortable()
	{
		$returnValue = NULL;
		if (!self::$_SORTABLE_Init) {
			self::$_SORTABLE_Init = TRUE;

			$returnValue = '
<script type="text/javascript">
	//<![CDATA[

		$(document).ready(function(){
			$("div.m2n-sortable ul.selected-values").sortable({
				opacity: 0.6,
				axis:\'y\',
				containment:\'parent\',
				cursor: \'move\',
				cancel: \':input,select,option,a,button,.unsortable,div.multitabs\',
				tolerance: \'pointer\'
			});
		});

	//]]>
</script>
			';
		}

		return $returnValue;
	}

	private function _renderExtraValue($extraValue, $ID)
	{
		$markup = NULL;
		$element = $this->getElement();

		$markup = '<div id="' . $element->getName() . '_tabs_div-' . $ID . '" class="extravalue-tab">' .
				  '<input type="text" name="' . $element->getName() . '_tabs_' . $ID . '" id="' . $element->getName() . '_tabs_' . $ID . '" value="' . $extraValue . '" />' .
				  '</div>';

		return $markup;
	}

	private function _renderRelationValues($itemID, $ID)
	{
		$markup = NULL;
		$element = $this->getElement();
		$view = $element->getView();

		if ($element->hasMultipleRelationM2nValuesRows()) {
			$mrMVRClass = ' border';
		} else {
			$mrMVRClass = NULL;
		}

		$markup .= '<div class="relation-values' . $mrMVRClass . '">' .
				   '<ul class="relation-values">';

		$relationValueRows = $element->getRealtionValuesRows($ID);

		if ($relationValueRows) {
			foreach ($relationValueRows as $relationValueRowModel) {
				$markup .= '<li class="relation-value">' .
						   '<table>';
				if ($element->hasRelation2y()) {
					$markup .= '<tr>' .
							   '<td>' .
							   $view->translate($element->getRelationValueColumnLablel('reverenced_id')) .
							   '</td>' .
							   '<td>' .
							   '<select id="" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'referenced_id\'][]" class="unsortable">' . $this->_renderRelation2yOptionValue($itemID, $relationValueRowModel->referenced_id) . '</select>' .
							   '</td>' .
							   '</tr>';
				}
				foreach ($element->getRealtionValueColumns() as $relationColumnName => $relationColumnDefinition) {
					$inputClass = NULL;
					if (isset($relationColumnDefinition['type']) &&
						$relationColumnDefinition['type'] == 'date') {

						$inputClass = 'date';
					}
					$outputValue = L8M_JQuery_Form_Element_M2N::prepareRelationValueOutput($relationValueRowModel->$relationColumnName, $relationColumnDefinition);
					$markup .= '<tr>' .
							   '<td>' .
							   $view->translate($element->getRelationValueColumnLablel($relationColumnName)) .
							   '</td>' .
							   '<td>';
					if (isset($relationColumnDefinition['type']) &&
						$relationColumnDefinition['type'] == 'boolean') {
						$checkBoxChecked = NULL;
						if ($outputValue) {
							$checkBoxChecked = ' checked="checked"';
						} else {
							$markup .= '<input type="text" class="hidden ' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="0" />';
						}
						$markup .= '<input type="checkbox"' . $checkBoxChecked . ' class="' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="1" />';
					} else {
						$markup .= '<input type="text" class="' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="' . $outputValue . '" />';
					}
					$markup .= '</td>' .
							   '</tr>';
				}
				$markup .= '</table>' .
						   '<a href="" class="remove" onclick="' . $element->getName() . '_relationValuesRemove(this); return false;">' . $view->translate('Remove') . '</a>' .
						   '</li>';
			}
		} else {
			$markup .= '<li class="relation-value">' .
						   '<table>';

			if ($element->hasRelation2y()) {
				$markup .= '<tr>' .
						   '<td>' .
						   $view->translate($element->getRelationValueColumnLablel('reverenced_id')) .
						   '</td>' .
						   '<td>' .
						   '<select id="" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'referenced_id\'][]" class="unsortable">' . $this->_renderRelation2yOptionValue($itemID) . '</select>' .
						   '</td>' .
						   '</tr>';
			}
			foreach ($element->getRealtionValueColumns() as $relationColumnName => $relationColumnDefinition) {
				$inputClass = NULL;
				if (isset($relationColumnDefinition['type']) &&
					$relationColumnDefinition['type'] == 'date') {
					$inputClass = 'date';
				}
				$markup .= '<tr>' .
							'<td>' .
							$view->translate($element->getRelationValueColumnLablel($relationColumnName)) .
							'</td>' .
							'<td>';
				if (isset($relationColumnDefinition['type']) &&
					$relationColumnDefinition['type'] == 'boolean') {
					$checkBoxChecked = NULL;
					$markup .= '<input type="text" class="hidden ' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="0" />';
					$markup .= '<input type="checkbox"' . $checkBoxChecked . ' class="' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="1" />';
				} else {
					$markup .= '<input type="text" class="' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="" />';
				}
				$markup .= '</td>' .
							'</tr>';
				// $markup .= '<tr>' .
				// 		   '<td>' .
				// 		    $view->translate($element->getRelationValueColumnLablel($relationColumnName)) .
				// 		   '</td>' .
				// 		   '<td>' .
				// 		   '<input type="text" class="' . $inputClass . '" name="' . $element->getName() . '_tabs_' . $itemID . '_rv[\'' . $relationColumnName . '\'][]" value="" />' .
				// 		   '</td>' .
				// 		   '</tr>';
			}

			$markup .= '</table>' .
					   '<a href="" class="remove" onclick="' . $element->getName() . '_relationValuesRemove(this); return false;">' . $view->translate('Remove') . '</a>' .
					   '</li>';
		}

		$markup .= '</ul>' .
				   '<a href="" class="add" onclick="' . $element->getName() . '_relationValuesAdd(this); return false;">' . $view->translate('Add') . '</a>' .
				   '</div>';

		return $markup;
	}

	private function _renderRelation2yOptionValue($paramID = NULL, $selectedID = NULL)
	{
		$returnValue = '<option value="">-</option>';

		$element = $this->getElement();

		if ($element->hasRelation2y() &&
			$paramID) {

			$paramModel = $element->getRelation2yModelName();

			/**
			 * load model
			 */
			if (class_exists($paramModel, TRUE)) {
				try {
					$helperModel = Doctrine_Query::create()
						->from($paramModel . ' m')
						->addWhere('m.id = ? ', array($paramID))
						->limit(1)
						->execute()
						->getFirst()
					;

					if ($helperModel &&
						$helperModel->getTable()->hasRelation('ModelName')) {

						$useModelName = $helperModel->ModelName->name;

						/**
						 * load model
						 */
						if (class_exists($useModelName, TRUE)) {

							$testModel = new $useModelName();
							$columnDefinitions = $testModel->getTable()->getColumns();

							if (array_key_exists('id', $columnDefinitions) &&
								array_key_exists('short', $columnDefinitions)) {

								try {
									$modelCollection = Doctrine_Query::create()
										->from($useModelName . ' m')
										->orderBy('m.short ASC')
										->execute()
									;

									foreach ($modelCollection as $model) {
										if ($selectedID == $model->id) {
											$optionSelected = ' selected="selected"';
										} else {
											$optionSelected = NULL;
										}
										$returnValue .= '<option value="' . $model->id . '"' . $optionSelected . '>' . $model->short . '</option>';
									}
								} catch (Doctrine_Connection_Exception $exception) {
									/**
									 * @todo maybe do something
									 */
								}
							}
						}
					}
				} catch (Doctrine_Connection_Exception $exception) {
					/**
					 * @todo maybe do something
					 */
				}
			}

		}

		return $returnValue;
	}

	private function _renderTranslateable($formValues, $ID)
	{
		$markup = NULL;

		if (count($formValues) > 0) {
			$element = $this->getElement();

			$view = $element->getView();

			$formElementName = $element->getName() . '_tabs';
			$formLists = '';
			$formContentDivs = '';
			$cssClassAddition = 'multitabtext';
			$cssCurrent = 'current';

			foreach (L8M_Locale::getSupported() as $key) {
				$cssClassLi = '';
				$cssClassDiv = '';

				if (Zend_Registry::isRegistered('jQueryTools') &&
					Zend_Registry::get('jQueryTools') !== FALSE) {

					$cssClassLi = ' class="' . $cssCurrent . '"';
					$cssClassDiv = ' class="' . $cssClassAddition . '"';
				}

				$formElement = new Zend_Form_Element_Text($formElementName . '_' . $key . '_' . $ID);
				$formElement
					->addDecorator('HtmlTag', array())
					->addDecorator('Label', array())
					->setValue($formValues[$key][$element->getTranslationColumn()])
				;

				$formLists .= '<li><a href="#' . $formElementName . '-' . $ID . '-' . $key . '"' . $cssClassLi . '>' . $key . '</a></li>';
				$formContentDivs .= '<div id="' . $formElementName . '-' . $ID . '-' . $key . '"' . $cssClassDiv . '>' . $formElement . '</div>';
				$cssCurrent = '';

			}

			if (Zend_Registry::isRegistered('jQueryTools') &&
					Zend_Registry::get('jQueryTools') !== FALSE) {

				$markup = '<div id="' . $formElementName . '-' . $ID . '" class="multitabs">' .
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
				$markup = '<div id="' . $formElementName . '-' . $ID . '" class="multitabs">' .
						  '<ul>' .
						  $formLists .
						  '</ul>' .
						  $formContentDivs .
						  '</div>';
			}
		}

		return $markup;
	}

	private function _renderEasyHTML($element, $view)
	{
		ob_start();

?>
	<select name="<?php echo $element->getName(); ?>_selector" id="<?php echo $element->getName(); ?>_selector">
		<option value="" selected="selected">-</option>
<?php

			foreach ($element->getOptionValuesForRender() as $itemModel) {
				echo '<option value="' . $itemModel->id . '">' . $itemModel[$element->getUseForeignColumn()] . '</option>';
			}

?>
	</select>
	<a href="" class="add <?php echo $element->getName(); ?>_add"><?php echo $view->translate('Add'); ?></a>
<?php

		return ob_get_clean();
	}

	private function _renderComplexHTML($element, $view)
	{
		ob_start();

?>
	<div class="m2n-form-element-extra">

		<div class="m2n-form-element-extra-container">
			<ul id="<?php echo $element->getName(); ?>_selector" rel="0">
<?php

			foreach ($element->getOptionValuesForRender() as $itemModel) {
				if ($element->getForeignAlias() == 'MediaImage') {
					$maxImage = $itemModel->maxBox(100, 54);
					echo '<li id="' . $element->getName() . '_selector_link_' . $itemModel->id . '_li" onclick="' . $element->getName() . '_selector_li_click(this); return false;" title="' . $itemModel[$element->getUseForeignColumn()] . '"><span class="value">' . $itemModel->id . '</span><span class="image" style="background-image:url(' . $maxImage->getLink() . ');"></span><span class="info img">' . $itemModel[$element->getUseForeignColumn()] . '</span></li>';
				} else {
					echo '<li id="' . $element->getName() . '_selector_link_' . $itemModel->id . '_li" onclick="' . $element->getName() . '_selector_li_click(this); return false;" title="' . $itemModel[$element->getUseForeignColumn()] . '"><span class="value">' . $itemModel->id . '</span><span class="info">' . $itemModel[$element->getUseForeignColumn()] . '</span></li>';
				}
			}

?>
			</ul>
		</div>
		<ul class="m2n-form-element-extra-navi">
			<li>
<?php

			if ($element->getOptionValuesCountForRender() < 75) {
				echo $element->getOptionValuesCountForRender();
			} else {
				echo '75';
			}
			echo ' ' . $view->translate('of') . ' ';
			echo $element->getOptionValuesCountForRender();

?>
			</li>
			<li>
				<a href="" class="navi prev" rel="1"></a>
			</li>
			<li>
				<a href="" class="navi next" rel="<?php echo $element->getOptionValuesCountForRender(); ?>"></a>
			</li>
		</ul>
	</div>
	<div class="search"><input type="text" id="<?php echo $element->getName(); ?>_search_input" name="<?php echo $element->getName(); ?>_search_input" value="" /><a href="" class="<?php echo $element->getName(); ?>_search_link"><?php echo $view->translate('Search'); ?></a></div>
	<a href="" class="add <?php echo $element->getName(); ?>_add"><?php echo $view->translate('Add'); ?></a>
<?php

			if ($element->getForeignAlias() == 'Media' ||
				$element->getForeignAlias() == 'MediaImage' ||
				$element->getForeignAlias() == 'MediaShockwave') {

?>
<div class="mediafolder">
	<span><?php echo $view->translate('MediaFolder'); ?></span>
	<select class="<?php echo $element->getName(); ?>_mediaFolderID">
		<option value="">[root]</option>
<?php

				foreach ($this->_getMediaFolders() as $key => $value) {
					echo '<option value="' . $value . '">' . $key . '</option>';
				}

?>
	</select>
	<br class="clear" />
</div>
<?php

			}

?>
	<script type="text/javascript">
	//<![CDATA[

		function <?php echo $element->getName(); ?>_selector_li_click(elem) {
			if (!$(elem).hasClass('used')) {
				if ($(elem).hasClass('active')) {
					$("ul#<?php echo $element->getName(); ?>_selector li").removeClass('active');
				} else {
					$("ul#<?php echo $element->getName(); ?>_selector li").removeClass('active');
					$("ul#<?php echo $element->getName(); ?>_selector #" + elem.id).addClass('active');
				}
			}
			return false;
		};

		$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.navi").click(function() {
			<?php echo $element->getName(); ?>_navi(this);
			return false;
		});

		function <?php echo $element->getName(); ?>_navi(elem) {
			var xMargin = parseInt($("ul#<?php echo $element->getName(); ?>_selector").css('marginTop'));
			var actPos = parseInt($("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.prev").attr('rel'));
			var maxResults = parseInt($("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.next").attr('rel'));

			if ($(elem).hasClass('prev') &&
				actPos > 1) {

				xMargin = (actPos - 2) * -345;
				$("ul#<?php echo $element->getName(); ?>_selector").animate({marginTop:xMargin});
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.prev").attr('rel', actPos - 1);
			} else
			if ($(elem).hasClass('next')) {
				xMargin = actPos * -345;

				$("ul#<?php echo $element->getName(); ?>_selector").animate({marginTop:xMargin});
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.prev").attr('rel', actPos + 1);

				var reloadResults = actPos * 75;
				var loadedResults = parseInt($("ul#<?php echo $element->getName(); ?>_selector").attr('rel'));

				if (loadedResults < maxResults &&
					reloadResults > loadedResults) {

					$.get("<?php echo $view->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'load', 'model'=>$element->getAutoloadModelName(), 'useSortColumn'=>$element->getUseForeignColumn(), 'useValueColumn'=>$element->getUseForeignColumn(), 'element'=>$element->getName()), NULL, TRUE); ?>", { offset: reloadResults, search: <?php echo $element->getName(); ?>_searchValue, mediaFolderID: <?php echo $element->getName(); ?>_mediaFolderID },
						function(data){
						$("ul#<?php echo $element->getName(); ?>_selector").append(data);
						$("ul#<?php echo $element->getName(); ?>_selector").attr('rel', reloadResults);
						<?php echo $element->getName(); ?>_updateUsed();
					});
				}
			}
			<?php echo $element->getName(); ?>_updateNavi()
			return false;
		};

		$("select.<?php echo $element->getName(); ?>_mediaFolderID").change(function(){
			<?php echo $element->getName(); ?>_mediaFolderID = $("select.<?php echo $element->getName(); ?>_mediaFolderID").attr('value');
			<?php echo $element->getName(); ?>_search(this);
		});

		$("a.<?php echo $element->getName(); ?>_search_link").click(function(){
			<?php echo $element->getName(); ?>_search(this);
			return false;
		});

		$("#<?php echo $element->getName(); ?>_search_input").keydown(function(e){
			if (e.keyCode == 13) {
				<?php echo $element->getName(); ?>_search(this);
				return false;
			}
		});

		var <?php echo $element->getName(); ?>_mediaFolderID = '';
		var <?php echo $element->getName(); ?>_searchValue = '';
		function <?php echo $element->getName(); ?>_search(elem) {
			<?php echo $element->getName(); ?>_searchValue = $("#<?php echo $element->getName(); ?>_search_input").attr('value');
			$.get("<?php echo $view->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'load', 'model'=>$element->getAutoloadModelName(), 'useSortColumn'=>$element->getUseForeignColumn(), 'useValueColumn'=>$element->getUseForeignColumn(), 'element'=>$element->getName()), NULL, TRUE); ?>", { fullView: 'true', offset: '0', search: <?php echo $element->getName(); ?>_searchValue, mediaFolderID: <?php echo $element->getName(); ?>_mediaFolderID },
				function(data){
				$(elem).parent().parent().children('div.m2n-form-element-extra').remove();

				$(elem).parent().parent().prepend(data);

				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi a.navi").click(function() {
					<?php echo $element->getName(); ?>_navi(this);
					return false;
				});

				<?php echo $element->getName(); ?>_updateUsed();
			});
		}

<?php

			if ($element->getForeignAlias() == 'Media' ||
				$element->getForeignAlias() == 'MediaImage' ||
				$element->getForeignAlias() == 'MediaShockwave') {

				$urlArray = array(
					'module'=>'system',
					'controller'=>'media',
					'action'=>'create',
					'browserType'=>'popup-select',
					'no-back-button'=>'true',
				);

?>

		$('a.<?php echo $element->getName(); ?>_add').after('<a href="" onclick="prepadd<?php echo $element->getName(); ?>funcM2N(); return false;" class="upload"><?php echo $view->translate('Upload'); ?></a>');
		function prepadd<?php echo $element->getName(); ?>funcM2N() {
			//window.open('<?php echo $view->url($urlArray, NULL, TRUE); ?>?mediaFolderID=' + <?php echo $element->getName(); ?>_mediaFolderID, '', '');

			/**
			 * prepare PopUp Background
			 */
			var popUpBackground = '<div class="mce-reset mce-fade mce-in mediaBrowserPopUpBackground" id="mce-modal-block" style="z-index: 65535;"></div>';
			$('body').append(popUpBackground);

			/**
			 * prepare PopUp
			 */
			var popUp = '<div class="mediaBrowserPopUp" style="z-index: 65537; position: fixed; width:860px; height:610px; top:10px; left:50%; margin-left:-430px; overflow:hidden; background: url(/img/js/ajax-loader.gif) no-repeat scroll center center #FFFFFF; border-radius: 6px 6px 6px 6px; box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">' +
						'	<div class="mce-window-head">' +
						'		<div class="mce-title">MediaBrowser</div>' +
						'		<button style="background-color: #ffffff; border: medium none; font-family: Helvetica Neue, Helvetica, Arial;" class="mce-close close-browser" aria-hidden="true" type="button">x</button>' +
						'	</div>' +
						'	<div class="innerMediaBrowserPopUp">' +
						'	</div>' +
						'</div>';
			$('body').append(popUp);

			/**
			 * close media browser
			 */
			$("button.close-browser").click(function() {
				$("a.<?php echo $element->getName(); ?>_search_link").trigger('click');
				$('div.mediaBrowserPopUp').remove();
				$('div.mediaBrowserPopUpBackground').remove();
			});

			/**
			 * add Browser
			 */
			var popUpClassID = '';
			if ($.browser == 'msie') {
				popUpClassID = ' classid="clsid:25336920-03F9-11CF-8FD0-00AA00686F13"';
			}

			var iframeObj = '<object' + popUpClassID + ' type="text/html" data="<?php echo $view->url($urlArray, NULL, TRUE); ?>?mediaFolderID=' + <?php echo $element->getName(); ?>_mediaFolderID + '" style="width:860px; height:570px;" width="570" height="860"></object>';
			iframeObj = '<iframe src="<?php echo $view->url($urlArray, NULL, TRUE); ?>?mediaFolderID=' + <?php echo $element->getName(); ?>_mediaFolderID + '" style="width:860px; height:570px;" width="570" height="860"></iframe>';
			$('div.innerMediaBrowserPopUp').append(iframeObj);
		}
<?php

			}

?>

		//]]>
	</script>
<?php

		return ob_get_clean();
	}

	/**
	 * Retrieve all folders in an array
	 *
	 * @param $parentMediaFolder
	 * @return array
	 */
	private function _getMediaFolders($parentMediaFolder = NULL, $allFolders = array(), $parentString = NULL)
	{

		$mediaFolderQuery = Doctrine_Query::create()
			->from('Default_Model_MediaFolder m')
		;

		if (!$parentMediaFolder) {
			$mediaFolderQuery = $mediaFolderQuery
				->where('m.media_folder_id IS NULL ', array())
			;
		} else {
			$mediaFolderQuery = $mediaFolderQuery
				->where('m.media_folder_id = ? ', array($parentMediaFolder))
			;
		}

		$mediaFolderCollection = $mediaFolderQuery->orderBy('m.name ASC')
			->execute()
		;

		foreach ($mediaFolderCollection as $mediaFolderModel) {
			$tempParentString = $parentString . DIRECTORY_SEPARATOR . $mediaFolderModel->name;
			$allFolders[$tempParentString] = $mediaFolderModel->id;
			$allFolders = $this->_getMediaFolders($mediaFolderModel->id, $allFolders, $tempParentString);
		}

		return $allFolders;
	}
}