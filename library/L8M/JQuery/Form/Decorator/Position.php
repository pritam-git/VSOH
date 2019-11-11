<?php
class L8M_JQuery_Form_Decorator_Position extends Zend_Form_Decorator_Abstract
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
		if (!$element instanceof L8M_JQuery_Form_Element_Position) {
			// wir wollen nur das Element
			return $content;
		}

		$view = $element->getView();
		if (!$view instanceof Zend_View_Interface) {
			// verwenden von View Helfers, deshalb ist nichts zu tun
			// wenn keine View vorhanden ist
			return $content;
		}

		ob_start();

?>
<div class="position-form-element box">
	<input type="text" class="hidden" value="" id="<?php echo $element->getName(); ?>" name="<?php echo $element->getName(); ?>" />
	<div class="position-container">
		<ul class="selected-values" data-xmargin="0" data-last-loaded="" data-first-loaded="" data-act-show="" data-max-to-load="" data-first-position="">
		</ul>
	</div>
	<ul class="position-navi">
		<li>
<?php

			echo '0 ' . $view->translate('of') . ' 0';

?>
		</li>
		<li>
			<a href="" class="navi prev"></a>
		</li>
		<li>
			<a href="" class="navi next" rel=""></a>
		</li>
	</ul>
</div>
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

			/**
			 * view from MVC
			 */
			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

			/**
			 * @var L8M_JQuery_Form_Element_Position
			 */
			$element = $this->getElement();

			ob_start();

?>
<script type="text/javascript">
	//<![CDATA[

		var startItemPosition<?php echo $element->getName(); ?> = '';
		var maxItemsToLoadPosition<?php echo $element->getName(); ?> = 0;
		var itemsCountPosition<?php echo $element->getName(); ?> = 0;
		var itemPositionHelper = 0;
		var itemPositionFirst = 0;
		var itemPositionLast = 0;

		$(document).ready(function(){
			$("div.position-form-element ul.selected-values").sortable({
				opacity: 0.6,
				containment:'parent',
				cursor: 'move',
				cancel: ':input, button, .unsortable, div.multitabs',
				tolerance: 'pointer',
				items: '> li',
				stop: function(event, ui) {
					$("div.position-form-element ul.selected-values li span.counter").each( function(i) {
						if (i == 0) {
							itemPositionHelper = 0;
							itemPositionFirst = 0;
							itemPositionLast = 0;
						}
						if (!$(this).parent().hasClass('pos-element')) {
							if (itemPositionFirst == 0) {
								itemPositionFirst = parseInt($(this).attr('data-position'));
							}
							itemPositionLast = parseInt($(this).attr('data-position'));
						}
					});
					$("div.position-form-element ul.selected-values li span.counter").each( function(i) {
						if ($(this).parent().hasClass('pos-element')) {
							if (itemPositionHelper == 0) {
								if ($("div.position-form-element ul.selected-values li span.counter").length >= 2) {
									itemPositionHelper = itemPositionFirst - 1;
								} else {
									itemPositionHelper = itemPositionHelper + 1;
								}
							}
							$(this).attr('data-position', itemPositionHelper);
						} else {
							itemPositionHelper = parseInt($(this).attr('data-position'));
						}
						$(this).text(parseInt($("div.position-form-element ul.selected-values").attr('data-first-loaded')) + i + 1);
//						$(this).attr('data-position', parseInt($("div.position-form-element ul.selected-values").attr('data-first-position')) + i);
					});
					$("#<?php echo $element->getName(); ?>").val($("div.position-form-element ul.selected-values li.pos-element span.counter").attr('data-position'));
				},
			});
//			$("#<?php echo $element->getName(); ?>").val($("div.position-form-element ul.selected-values li.pos-element span.counter").html());

			retrievePositionContent('load', 'append');
		});

		$("div.position-form-element a.navi").click(function() {
			var xMargin = parseInt($("div.position-form-element ul.selected-values").attr('data-xmargin'));
			var maxResults = parseInt($("div.position-form-element a.next").attr('rel'));
			var firstLoaded = parseInt($("div.position-form-element ul.selected-values").attr('data-first-loaded'));
			var lastLoaded = parseInt($("div.position-form-element ul.selected-values").attr('data-last-loaded'));
			var actShown = startItemPosition<?php echo $element->getName(); ?>;
			var maxToLoad = parseInt($("div.position-form-element ul.selected-values").attr('data-max-to-load'));

			$("div.position-form-element ul.selected-values").stop();
			$("div.position-form-element ul.selected-values").css('marginTop', xMargin);

			var parentRelation = '<?php echo $element->getParentRelationAliasName(); ?>';
			var parentFormElementName = '<?php echo $element->getFormElementNameIdOfParentFormElement(); ?>';
			var parentIdValue = '';
			if (parentFormElementName.length > 0) {
				parentIdValue = $('#' + parentFormElementName).attr('value');
			}

			if ($(this).hasClass('prev') &&
				actShown > 0) {

				var newStart = actShown - 20
				startItemPosition<?php echo $element->getName(); ?> = startItemPosition<?php echo $element->getName(); ?> - 20;
				if (newStart < firstLoaded) {
					var loadOffset = firstLoaded - 20;
					$("div.position-form-element ul.selected-values").attr('data-act-show', newStart);

					$("div.position-form-element ul.selected-values").animate({marginTop:108});
					$.get("<?php echo $viewFromMVC->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'position', 'model'=>$element->getLocalClassName()), NULL, TRUE); ?>", { offset: loadOffset, parentID: parentIdValue, parentRelation: parentRelation, modelID: '<?php echo $element->getModelID(); ?>', function: 'update', posDirection: 'prepend' }, function(data){
						buildUpPosition(data);
					});
					xMargin = 0;
				} else {
					xMargin = xMargin + 108;
					$("div.position-form-element ul.selected-values").animate({marginTop:xMargin});
					$("div.position-form-element ul.selected-values").attr('data-act-show', newStart);
				}

			} else
			if ($(this).hasClass('next')) {
				var newStart = actShown + 20;
				var newLastLoaded = newStart + 60;

				startItemPosition<?php echo $element->getName(); ?> = startItemPosition<?php echo $element->getName(); ?> + 20
				if (newLastLoaded > lastLoaded) {
					if (newLastLoaded <= maxToLoad) {
						var loadOffset = lastLoaded;
						$("div.position-form-element ul.selected-values").attr('data-act-show', newStart);

						$.get("<?php echo $viewFromMVC->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'position', 'model'=>$element->getLocalClassName()), NULL, TRUE); ?>", { offset: loadOffset, parentID: parentIdValue, parentRelation: parentRelation, modelID: '<?php echo $element->getModelID(); ?>', function: 'update', posDirection: 'append' }, function(data){
							buildUpPosition(data);
						});
						xMargin = xMargin - 108;
					}
				} else {
					xMargin = xMargin - 108;
				}

				$("div.position-form-element ul.selected-values").animate({marginTop:xMargin});
				$("div.position-form-element ul.selected-values").attr('data-act-show', newStart);
			}

			$("div.position-form-element ul.selected-values").attr('data-xmargin', xMargin);

			position_updateNavi();

			return false;
		});

		function retrievePositionContent(funcMe, posDirection) {
			var parentRelation = '<?php echo $element->getParentRelationAliasName(); ?>';
			var parentFormElementName = '<?php echo $element->getFormElementNameIdOfParentFormElement(); ?>';
			var parentIdValue = '';
			if (parentFormElementName.length > 0) {
				parentIdValue = $('#' + parentFormElementName).attr('value');
			}

			$('div.position-form-element input').attr('value', '0');
			if (funcMe == 'load') {
				$('div.position-form-element ul.selected-values').html('');
				startItemPosition<?php echo $element->getName(); ?> = '';
			}
			$('div.position-form-element ul.position-navi li:first-child').html('0' + ' <?php echo $viewFromMVC->translate('of'); ?> ' + '0');
			$('div.position-form-element ul.position-navi li a.next').attr('rel', '0');
			maxItemsToLoadPosition<?php echo $element->getName(); ?> = 0;
			itemsCountPosition<?php echo $element->getName(); ?> = 0;


			$.get("<?php echo $viewFromMVC->url(array('module'=>'system', 'controller'=>'auto-complete', 'action'=>'position', 'model'=>$element->getLocalClassName()), NULL, TRUE); ?>", { offset: startItemPosition<?php echo $element->getName(); ?>, parentID: parentIdValue, parentRelation: parentRelation, modelID: '<?php echo $element->getModelID(); ?>', function: funcMe, posDirection: posDirection }, function(data){
				buildUpPosition(data);
				position_updateNavi();
			});
		}

		function buildUpPosition(data) {
			if (typeof data == 'object' &&
				typeof data.loaded != 'undefined' &&
				typeof data.items != 'undefined' &&
				typeof data.maxToLoad != 'undefined' &&
				typeof data.actShow != 'undefined' &&
				typeof data.firstLoaded != 'undefined' &&
				typeof data.lastLoaded != 'undefined' &&
				typeof data.countAll != 'undefined' &&
				typeof data.function != 'undefined' &&
				typeof data.posDirection != 'undefined' &&
				typeof data.firstPosition != 'undefined') {

				if (data.posDirection == 'append') {
					for (var i = 0; i < data.loaded; i++) {
						$("div.position-form-element ul.selected-values").append('<li class="selecte_tab unsortable" id="' + data.items[i].id +  '_selected_id" title="' + data.items[i].short +  '"><span class="counter" data-position="' + data.items[i].position + '">' + data.items[i].posCounter +  '</span><span class="name">.&nbsp;&nbsp;' + data.items[i].short +  '</span></li>');
					}
				} else {
					for (var i = data.loaded - 1; i >= 0; i--) {
						$("div.position-form-element ul.selected-values").prepend('<li class="selecte_tab unsortable" id="' + data.items[i].id +  '_selected_id" title="' + data.items[i].short +  '"><span class="counter" data-position="' + data.items[i].position + '">' + data.items[i].posCounter +  '</span><span class="name">.&nbsp;&nbsp;' + data.items[i].short +  '</span></li>');
						$("div.position-form-element ul.selected-values").css({marginTop:0});
					}
				}

				var myID = '<?php echo $element->getModelID(); ?>';
				var myShort = '<?php echo $element->getModelShort(); ?>';
				$("div.position-form-element ul.selected-values li#" + myID + "_selected_id").removeClass('unsortable');
				$("div.position-form-element ul.selected-values li#" + myID + "_selected_id").addClass('pos-element');

				if ($("div.position-form-element ul.selected-values li#" + myID + "_selected_id").length == 0 &&
					$("div.position-form-element ul.selected-values li#new_selected_id").length == 1) {

					$("div.position-form-element ul.selected-values li#new_selected_id").removeClass('unsortable');
					$("div.position-form-element ul.selected-values li#new_selected_id").addClass('pos-element');
				}

				$("div.position-form-element ul.selected-values").attr('data-max-to-load', data.maxToLoad);
				if (data.function == 'load') {
					$("div.position-form-element ul.selected-values").attr('data-act-show', data.actShow);
					$("div.position-form-element ul.selected-values").attr('data-xmargin', 0);
					$("div.position-form-element ul.selected-values").css({marginTop:0});
					$("div.position-form-element ul.selected-values").attr('data-first-loaded', data.firstLoaded);
					$("div.position-form-element ul.selected-values").attr('data-last-loaded', data.lastLoaded);
					$("div.position-form-element ul.selected-values").attr('data-first-position', data.firstPosition);
					startItemPosition<?php echo $element->getName(); ?> = data.firstLoaded;
					$("#<?php echo $element->getName(); ?>").val($("div.position-form-element ul.selected-values li.pos-element span.counter").attr('data-position'));
				} else {
					var firstLoaded = parseInt($("div.position-form-element ul.selected-values").attr('data-first-loaded'));
					var lastLoaded = parseInt($("div.position-form-element ul.selected-values").attr('data-last-loaded'));
					var firstPosition = parseInt($("div.position-form-element ul.selected-values").attr('data-first-position'));

					if (data.firstLoaded < firstLoaded) {
						$("div.position-form-element ul.selected-values").attr('data-first-loaded', data.firstLoaded);
					}
					if (data.lastLoaded > lastLoaded) {
						$("div.position-form-element ul.selected-values").attr('data-last-loaded', data.lastLoaded);
					}
					if (data.firstPosition < firstPosition) {
						$("div.position-form-element ul.selected-values").attr('data-first-position', data.firstPosition);
					}
				}
//				$("div.position-form-element ul.position-navi li:first-child").html(data.lastLoaded + ' <?php echo $viewFromMVC->translate('of'); ?> ' + data.countAll);

				maxItemsToLoadPosition<?php echo $element->getName(); ?> = data.maxToLoad;
				itemsCountPosition<?php echo $element->getName(); ?> = data.countAll;
			}
		}

		function position_updateNavi() {
			var showItems = startItemPosition<?php echo $element->getName(); ?> + 60;

			if (showItems == 60) {
				$("div.position-form-element ul.position-navi li a.prev").hide();
			} else {
				$("div.position-form-element ul.position-navi li a.prev").show();
			}

			if (showItems >= maxItemsToLoadPosition<?php echo $element->getName(); ?>) {
				$("div.position-form-element ul.position-navi li a.next").hide();
				showItems = itemsCountPosition<?php echo $element->getName(); ?>;
			} else {
				$("div.position-form-element ul.position-navi li a.next").show();
			}

			$("div.position-form-element ul.position-navi li:first-child").html(showItems.toString() + '<?php echo ' ' .$viewFromMVC->translate('of'); ?> ' + itemsCountPosition<?php echo $element->getName(); ?>.toString());

		}

	//]]>
</script>
<?php

			$returnValue = ob_get_clean();
		}

		return $returnValue;
	}
}