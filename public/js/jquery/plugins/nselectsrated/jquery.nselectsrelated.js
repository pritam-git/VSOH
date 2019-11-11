/*
 * jQuery plugin nSelectsRelated 	1.0.0	jQuery Plugin
 * http://www..com/projects/nselectsrelated/
 * 
 * Copyright (c) 2009 by Gus Waddell	guswaddell at gmail.com
 * 
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */
(function($) {
	var nsrIndex = 0;
	
	$.fn.nselectsrelated = function(customOptions) {

		var options = {
			selectBoxSelector:			'select',	
			optionSelector:				'option',	
			
			nsrAttribute:				'nsr',			// attribute containing rules (eg <select... nsr="{home: true} will match any <option home="true"...>
			nsrFilterAttribute:			'nsrFilter',	// attribute containing the current filtering on an <option.... item
				
			matchOnClassIfNoAttribute:	true,			// if no nsrAttribute appears in selected value - do a match on the class=value instead?
			matchAtLeastOne:			true,			// needs to match at least one item in each select box or dont filter anything..
			filterOutMatched:			false,			// If a match is found ... do I filter out the matches or the non-matches?
			removeNoValue:				true,			// remove options with no value if only 1 valid selection remains.
			
			wrap:						'span',			// wrap the orig select box in this ( if anything )
			wrapClass:					'nsrWrap',		// class to assign to the wrapper

			hideOptionClass:			'nsrHidden',	// Class to assign to a hidden element
			
			alwaysClone:				false,			// Always use a clone of the select box?
			hideOriginal:				true,			// Hide the original select box (if cloned)
			originalCSSDisplay:			'inline',		// css display to re-apply to select box

			classToAdd:					'nsrOrig',		// class to add to the orig select box
			newClass:					'nsr',			// class to add to the new select box
			cloneLinkAtt:				'nsrFor',		// clone select box link to the orig

			animate:					true,			// Animate the boxes that are changed?
			animateCol:					'yellow',		// animate colour - Indicates the affected boxes
			animateTime:				1500,			// time to fade back to orig colour

			debug: 						false	
		}
		
		$.extend(options, customOptions);
	
		return this.each(function(index) {
			var idSplit = '_NSR_';
			var noValueRemoved = options.nsrAttribute + 'NoValue' 

			nsrIndex++;
			var thisAtt = options.nsrAttribute + '_' + nsrIndex + '_' + index;
			var $original = $(this); 				// the original select box
			var $cloneobj = '';

			(options.alwaysClone || $.browser.msie)
				? cloneOrHide = 'clone'
				: cloneOrHide = 'hide';
			
			function init() {
				$original.change(relateSelects);
				$original.change();
			}
			
			function createChildSelectors(){
				// Check if there are any [options.nsrAttribute] attributes..if not use the class value
				var childSelectors = {};
				var retStr = '';
				
				$original.children('option:selected').each( function(){
					var nsrAtts = $(this).attr(options.nsrAttribute);
					
					if (nsrAtts){
						if ( nsrAtts.indexOf( '{' ) <0 ) nsrAtts = "{" + nsrAtts + "}";
						nsrAtts = eval("(" + nsrAtts + ")");
						$.extend(childSelectors, nsrAtts);
					}
				});
				
				childSelectors && $.each(childSelectors, function(key, val){
					var filterOut = options.filterOutMatched;
					var alertStr = 'Key: ' + key + '\tValue: ' + val;
					
					if (typeof(val) == 'object'){
						var thisFilterOut, thisVal;
						var attProcessed = false;
						alertStr += '\nval is an object...';
						
						$(val).each(function( intIndex, objValue ){
							alertStr += '\n\tintIndex: ' + intIndex + '\tobjValue: ' + objValue;

							if (typeof(objValue) == 'object'){
								alertStr += '\n\t\tobjValue is an object...';

								$(objValue).each(function( intIndex1, objValue1 ){
									alertStr += '\n\t\tintIndex1: ' + intIndex1 + '\tobjValue1: ' + objValue1;
									if (intIndex1==0){ thisVal = objValue1; } 
									if (intIndex1==1){ thisFilterOut = objValue1; } 
								});
								retStr = retStr + childSelectorPart(key, thisVal, thisFilterOut);
								alertStr += '\n\t\tchildSelector is now: ' + retStr;
								attProcessed = true;
							} else {
								if (intIndex==0){ thisVal = objValue; } 
								if (intIndex==1){ thisFilterOut = objValue; } 
							}
						});
						if (!attProcessed){
							retStr = retStr + childSelectorPart(key, thisVal, thisFilterOut);
							alertStr += '\n\tchildSelector is now: ' + retStr;
						}
					} else {
						retStr = retStr + childSelectorPart(key, val, filterOut);
						alertStr += '\nchildSelector is now: ' + retStr;
					}
				});
				
				if (options.matchOnClassIfNoAttribute && retStr == '' && $original.val() != ''){ 
					retStr = childSelectorClass($original.val(), options.filterOutMatched);
				}

				return options.optionSelector + retStr;
			}
			
			function childSelectorPart(key, val, filterOut){
				key = key.toLowerCase();
				
				if (key == 'class'){
					return childSelectorClass(val, filterOut);
				} else {
					if (filterOut){
						return "[" + key + "!='" + val + "']";
					} else {
						return "[" + key + "='" + val + "']";
					}
				}
			}
			
			function childSelectorClass(classname, filterOut){
				if (filterOut){
					return ":not(." + classname + ') ';
				} else {
					return "." + classname + ' ';
				}
			}
			
			function relateSelects(){
				var childSelectorStr = createChildSelectors();

				$(options.selectBoxSelector).each(function(i){
					var sel; 
					$currobj = $(this);
					var doThis = true;
					var cloneID = options.nsrAttribute + idSplit + $currobj.attr('id') + idSplit + $currobj.attr('name'); 
					
					// don't act on the original select box or any clone select boxes
					if ( 
							( $(this).attr('name') == $original.attr('name') )
							|| ( $(this).hasClass(options.newClass) )
						) doThis = false; 

					if (doThis){
						// remove any current filters for this index
						sel = "[" + options.nsrFilterAttribute + "*='" + thisAtt + "']";
						$(this).children(sel).each(function(q){
							var newVal = $(this).attr(options.nsrFilterAttribute);
							if (newVal){ $(this).attr(options.nsrFilterAttribute, newVal.replace(thisAtt,'')); }
							$(this).removeClass(options.hideOptionClass).css('display','block');
						});

						// if theres a clone object then delete it
						$('#' + cloneID).remove();

						if (childSelectorStr != ''){
							var doThisChildSelect = true;
							var $matchedChildren = $(this).children().not(childSelectorStr);
							
							if (options.matchAtLeastOne){
								if ( $matchedChildren.size() == $currobj.children().size()) doThisChildSelect = false;
							}
							
							if (doThisChildSelect){
								$matchedChildren.each( function(j){
									// append thisAtt to the nsrFilterAttribute att
									if ($(this).val() != ''){
										currAtt = $(this).attr(options.nsrFilterAttribute);
										if (currAtt){
											currAtt += thisAtt;
										} else {
											currAtt = thisAtt;
										}
										$(this).attr(options.nsrFilterAttribute,currAtt);
									}
								});
								
								// check to see if there is 1 valid options - and if so which of these is valid and remove Please Select where appropriate
								sel = ":not([" + options.nsrFilterAttribute + "^='" + options.nsrAttribute + "'])";
								if (options.removeNoValue){
									if ($currobj.children("[value!='']").filter(sel).size() == 1){
										$currobj.children("[value='']").filter(sel).each( function(){
											currAtt = $(this).attr(options.nsrFilterAttribute);
											if (currAtt){
												currAtt += noValueRemoved;
											} else {
												currAtt = noValueRemoved;
											}
											$(this).attr(options.nsrFilterAttribute,currAtt);
										});
									} else {
										sel = "[" + options.nsrFilterAttribute + '*=' + noValueRemoved + "]";
										$currobj.children(sel).each( function(){
											$(this).attr(options.nsrFilterAttribute, $(this).attr(options.nsrFilterAttribute).replace(noValueRemoved,'') );
											$(this).removeClass(options.hideOptionClass).css('display','block');;
										});
									}
								}
							}
						}

						var bgColour = $(this).css('background-color');
						sel = "[" + options.nsrFilterAttribute + "^='" + options.nsrAttribute + "']";
						
						if (cloneOrHide=='clone'){
							if ( $(this).children(sel).size() > 0 ){
								// need a clone
								(options.hideOriginal)
									? $currobj.addClass(options.classToAdd).css('display','none')
									: $currobj.addClass(options.classToAdd);
								
								var $cloneobj = $(this)
												.clone()
												.attr('id', cloneID )
												.attr('selectedIndex',$currobj.attr('selectedIndex'))
												.addClass(options.newClass)
												.removeClass(options.classToAdd)
												.css('display',options.originalCSSDisplay)
												.removeAttr('name')
												.removeAttr('style')
												.change(function(){
													var origselectatts = $(this).attr('id').split(idSplit);
													var $origselect;
													if ( origselectatts[1] != 'undefined'){
														$origselect = $('#' + origselectatts[1]);
													} else {
														if ( origselectatts[2] != 'undefined'){
															$origselect = $("*[name='" + origselectatts[2] + "']");
														} else {
															$origselect = $currobj;
														}
													}
													var selOption = $(this).children("option:selected").val();
													$origselect.children("option[value='" + selOption + "']").attr('selected','selected');
													$origselect.change();		// call the change event on select box
												});
								
								
								if ($.fn.effect && options.animate && options.animateCol != ''){
									$cloneobj.effect("highlight", {color: options.animateCol}, options.animateTime);
								}
								var numValid = 0;
								var numNoVal = 0;
	
								$cloneobj.children("[" + options.nsrFilterAttribute + "*=" + options.nsrAttribute + "]").each(function(c){
									$(this).remove();
								});
								
								if ( options.wrap != '' && $currobj.parent().attr('nsrwrap') != $cloneobj.attr('id')){
									$currobj.wrap('<' + options.wrap + ' class=' + options.wrapClass + ' nsrwrap=' + $cloneobj.attr('id') + '></' + options.wrap + '>');
								}
								$cloneobj.insertAfter( $currobj ).change();
							} else {
								// doesn't need a clone...remove any trace of nsr from the current select box
								$currobj.removeClass(options.classToAdd).css('display',options.originalCSSDisplay);
							}
						} else {
							if ( $(this).children(sel).size() > 0 ){
								$(this).children(sel).addClass(options.hideOptionClass).css('display','none');
								
								// check if the current selection is still visible - otherwise select the first visible option.
								if($currobj.children(':visible').filter(':selected').size() == 0){
									$currobj.children(':visible:first').attr('selected','selected');
								}
								
								if (options.animate && options.animateCol != ''){
									$(this)
										.css('background-color',options.animateCol)
										.animate({backgroundColor: bgColour}, options.animateTime);
								}
							}
							//$(this).change();
						}
					}
				});
			}
			
			init();
		});
	};

})(jQuery); 