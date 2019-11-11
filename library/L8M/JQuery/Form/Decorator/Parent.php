<?php
class L8M_JQuery_Form_Decorator_Parent extends Zend_Form_Decorator_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

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
		if (!$element instanceof L8M_JQuery_Form_Element_Parent) {
			// wir wollen nur das Element
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}


		$loadAction = Doctrine_Query::create()
			->from('Default_Model_Action m')
			->addWhere('m.resource = ? ', array('system.auto-complete.load'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$html = $this->_renderComplexHTML($element, $view);

		ob_start();

?>
<div class="m2n-form-element box">
	<?php echo $html; ?>
	<ul class="selected-values">
<?php

		$itemModel = $element->getSelectedOptionValueForRender();
		$foreignAlias = $element->getClassName();
		if ($itemModel) {
			$columnToUse = $element->getUseValueColumn();
			$columnValue = $itemModel->$columnToUse;
			echo '<li class="selecte_tab" id="' . $element->getName() . '_selected_id"><input type="text" class="hidden" id="' . $element->getName() . '" name="' . $element->getName() . '" value="' . $itemModel[$element->getUseIDColumn()] . '" data-id="' . L8M_Library::getUsableUrlStringOnly($element->getName() . '_selector_link_' . $itemModel[$element->getUseIDColumn()] . '_li') . '" /><span>' . $columnValue . '</span> <a class="remove ' . $element->getName() . '_remove" href="" id="' . $element->getName() . '_selected" onclick="' . $element->getName() . '_remove(this); return false;">' . $view->translate('Remove') . '</a></li>';
		}
?>
	</ul>
<?php

		if (!$itemModel) {
			echo '<input type="text" class="hidden" id="' . $element->getName() . '" name="' . $element->getName() . '" value="" />';
		}

?>
</div>
<script type="text/javascript">
	//<![CDATA[

		$("a.<?php echo $element->getName(); ?>_add").click(function() {

			var m2nValue = '';
			var m2nInfo = '';
			var m2nID = '';

			m2nValue = $("#<?php echo $element->getName(); ?>_selector li.active span.value").html();
			m2nInfo = $("#<?php echo $element->getName(); ?>_selector li.active span.info").html();
			m2nID = $("#<?php echo $element->getName(); ?>_selector li.active").attr('id');

			$("ul#<?php echo $element->getName(); ?>_selector li").removeClass('active');

			if (m2nValue &&
				m2nInfo &&
				m2nID) {

				$("#<?php echo $element->getName(); ?>-element ul.selected-values li").remove();
				$("#<?php echo $element->getName(); ?>").remove();

				var appendString = '<li class="selecte_tab" id="<?php echo $element->getName(); ?>_selected_id"><input type="text" class="hidden" id="<?php echo $element->getName(); ?>" name="<?php echo $element->getName(); ?>" value="' + m2nValue + '" data-id="' + m2nID + '" /><span>' + m2nInfo + '</span> <a class="remove <?php echo $element->getName(); ?>_remove" href="" id="<?php echo $element->getName(); ?>_selected" onclick="<?php echo $element->getName(); ?>_remove(this); return false;"><?php echo $view->translate('Remove'); ?></a></li>';

				$("#<?php echo $element->getName(); ?>-element ul.selected-values").append(appendString);
			}
			<?php echo $element->getName(); ?>_updateUsed();
			<?php echo $element->getName(); ?>_updatePosition();
			return false;
		});

		function <?php echo $element->getName(); ?>_remove(item) {
			$("#" + item.id + '_id').remove();
			<?php echo $element->getName(); ?>_updateUsed();
			$("#<?php echo $element->getName(); ?>-element div.m2n-form-element").append('<input type="text" class="hidden" id="<?php echo $element->getName(); ?>" name="<?php echo $element->getName(); ?>" value="" />');
			<?php echo $element->getName(); ?>_updatePosition();
			return false;
		};

		function <?php echo $element->getName(); ?>_updatePosition() {
			retrievePositionContent('load', 'append');
		}

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

			if (maxResults < 75) {
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi li:first-child").html(maxResults.toString() + ' <?php echo $view->translate('of'); ?> ' + maxResults.toString());
			} else {
				var showItems = actPos * 75;
				if (showItems > maxResults) {
					showItems = maxResults;
				}
				$("#<?php echo $element->getName(); ?>-element ul.m2n-form-element-extra-navi li:first-child").html(showItems.toString() + ' <?php echo $view->translate('of'); ?> ' + maxResults.toString());
			}

			return false;
		}

		$(document).ready(function(){
			<?php echo $element->getName(); ?>_updateUsed();
			<?php echo $element->getName(); ?>_updateNavi()
		});

	//]]>
</script>
<?php

		$markup = ob_get_clean();

		switch ($this->getPlacement()) {
			case self::PREPEND:
				return $markup . $this->getSeparator() . $content;
			case self::APPEND:
			default:
				return $content . $this->getSeparator() . $markup;
		}
	}

	private function _renderComplexHTML($element, $view)
	{
		ob_start();

?>
	<div class="m2n-form-element-extra">

		<div class="m2n-form-element-extra-container">
			<ul id="<?php echo $element->getName(); ?>_selector" rel="0">
<?php

			foreach ($element->getOptionValuesForRender() as $key => $value) {
				if ($key !== '') {
					if ($element->getClassName() == 'Default_Model_MediaImage') {
						$itemImageModel = Doctrine_Query::create()
							->from('Default_Model_MediaImage m')
							->addWhere('m.id = ? ', array($key))
							->limit(1)
							->execute()
							->getFirst()
						;
						if ($itemImageModel) {
							$maxImageLink = $itemImageModel->maxBox(100, 54)->getLink();
						} else {
							$maxImageLink = NULL;
						}
						echo '<li id="' . $element->getName() . '_selector_link_' . $key . '_li" onclick="' . $element->getName() . '_selector_li_click(this); return false;" title="' . $value . '"><span class="value">' . $key . '</span><span class="image" style="background-image:url(' . $maxImageLink . ');"></span><span class="info img">' . $value . '</span></li>';
					} else {
						echo '<li id="' . $element->getName() . '_selector_link_' . L8M_Library::getUsableUrlStringOnly($key) . '_li" onclick="' . $element->getName() . '_selector_li_click(this); return false;" title="' . $value . '"><span class="value">' . $key . '</span><span class="info">' . $value . '</span></li>';
					}
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
	<a href="" class="add <?php echo $element->getName(); ?>_add"><?php echo $view->translate('Select'); ?></a>
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
<?php

			$conditionCounter = 0;
			$sConditions = array();
			foreach ($element->getOne2NmultiRelationCondition() as $key => $value) {
				$sCondition = array(
					'like'=>'',
					'difference'=>'',
					'isnull'=>'',
					'value'=>'',
					'key'=>$key,
				);

				if ($value === NULL) {
					$sCondition['isnull'] = 1;
				} else
				if (is_array($value) &&
					array_key_exists('like', $value) &&
					$value['like'] &&
					array_key_exists('value', $value)) {

					$sCondition['like'] = 1;
					$sCondition['value'] = $value['value'];
				} else
				if (is_array($value) &&
					array_key_exists('like', $value) &&
					!$value['like'] &&
					array_key_exists('value', $value)) {

					$sCondition['like'] = 0;
					$sCondition['value'] = $value['value'];
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'lt' &&
					array_key_exists('value', $value)) {

					$sCondition['difference'] = 'lt';
					$sCondition['value'] = $value['value'];
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'lte' &&
					array_key_exists('value', $value)) {

					$sCondition['difference'] = 'lte';
					$sCondition['value'] = $value['value'];
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'gt' &&
					array_key_exists('value', $value)) {

					$sCondition['difference'] = 'gt';
					$sCondition['value'] = $value['value'];
				} else
				if (is_array($value) &&
					array_key_exists('difference', $value) &&
					$value['difference'] == 'gte' &&
					array_key_exists('value', $value)) {

					$sCondition['difference'] = 'gte';
					$sCondition['value'] = $value['value'];
				} else {
					$sCondition['value'] = $value['value'];
				}
				$sConditions[] = 'like[' . $conditionCounter . ']=' . $sCondition['like'] . '&difference[' . $conditionCounter . ']=' . $sCondition['difference'] . '&isnull[' . $conditionCounter . ']=' . $sCondition['isnull'] . '&value[' . $conditionCounter . ']=' . $sCondition['value'] . '&key[' . $conditionCounter . ']=' . $sCondition['key'];
				$conditionCounter++;
			}

			$sConditionString = NULL;
			if (count($sConditions)) {
				$sConditionString = '?' . implode('&', $sConditions) . '&';
			}

?>
					$.get("<?php echo $view->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'load', 'model'=>$element->getClassName(), 'useIDColumn'=>$element->getUseIDColumn(), 'useValueColumn'=>$element->getUseValueColumn(), 'element'=>$element->getName()), NULL, TRUE) . $sConditionString; ?>", { offset: reloadResults, search: <?php echo $element->getName(); ?>_searchValue },
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

		var <?php echo $element->getName(); ?>_searchValue = '';
		function <?php echo $element->getName(); ?>_search(elem) {
			<?php echo $element->getName(); ?>_searchValue = $("#<?php echo $element->getName(); ?>_search_input").attr('value');
			$.get("<?php echo $view->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'load', 'model'=>$element->getClassName(), 'useIDColumn'=>$element->getUseIDColumn(), 'useValueColumn'=>$element->getUseValueColumn(), 'element'=>$element->getName()), NULL, TRUE) . $sConditionString; ?>", { fullView: 'true', offset: '0', search: <?php echo $element->getName(); ?>_searchValue },
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

		//]]>
	</script>
<?php

		return ob_get_clean();
	}
}