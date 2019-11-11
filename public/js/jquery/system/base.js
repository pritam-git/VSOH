////////////////////////////////////////////////////////////
//
// L8M
//
//
// Contains base JS (jQuery).
//
// Filesource  /public/js/jquery/system/base.js
// Version     $Id: base.js 7 2014-03-11 16:18:40Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {

	////////////////////////////////////////////////////////////
	// second menu
	////////////////////////////////////////////////////////////

	$("div#second-menu a.second-menu").click(function() {
		return false;
	});
	if($("form").length){
		var FormId = $("form").attr('id');
		var FormAction = $("form").attr('action');
		actionArr = FormAction.split('/');

		if(actionArr.length >= 3  && (actionArr[3] == "create" || actionArr[3] == "edit"))
		{
			var SubmitBtnId = $('#'+FormId+" input[type=submit]").attr('id');
			var SubmitBtnName = $('#'+FormId+" input[type=submit]").attr('name');
			if(SubmitBtnId && SubmitBtnName && SubmitBtnId != "undefined" && SubmitBtnName != "undefined" && ScrollSaveBtnTitle != "undefined")
			{
				$('#page').prepend('<a href="#" title='+ScrollSaveBtnTitle+' class="ScrollSaveBtn" id="Custom_'+SubmitBtnId+'" ></a>');
			}

			// var submitPosition = $('#'+SubmitBtnId).position();
			// var submitPositionTop = submitPosition.top;
			$('#Custom_'+SubmitBtnId).click(function(event){
				event.preventDefault();
				$("#"+FormId).submit(); // Submit the form
			});
			var height = $(window).outerHeight();
			var scrollHeight = height + $(window).scrollTop();
			var submitPosition = $('#'+SubmitBtnId).offset().top + $('#'+SubmitBtnId).outerHeight();
			if(scrollHeight >= submitPosition){
				$('#Custom_'+SubmitBtnId).hide();
			}else{
				$('#Custom_'+SubmitBtnId).show();
			}
			$(window).scroll(function(){
				height = $(this).outerHeight();
				scrollHeight = height + $(this).scrollTop();
				submitPosition = $('#'+SubmitBtnId).offset().top + $('#'+SubmitBtnId).outerHeight();
				if(scrollHeight >= submitPosition){
					$('#Custom_'+SubmitBtnId).hide();
				}else{
					$('#Custom_'+SubmitBtnId).show();
				}
			});
		}
	}
	$('#page').prepend('<a href="#" class="scrollup">Top</a>');

	$(window).scroll(function(){
		if ($(this).scrollTop() > 170) {
			$('a.scrollup').fadeIn();
		} else {
			$('a.scrollup').fadeOut();
		}
	});

	$('a.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});
});
