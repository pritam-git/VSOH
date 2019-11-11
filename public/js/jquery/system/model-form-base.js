////////////////////////////////////////////////////////////
//
// L8M
//
// 
// Contains some model-form-base JS (jQuery).
//
// Filesource  /public/js/jquery/system/model-form-base.js
// Version     $Id: model-form-base.js 568 2018-06-21 08:02:25Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {
		
	////////////////////////////////////////////////////////////
	// some changes if errors exist
	////////////////////////////////////////////////////////////
	
	$('div.l8m-model-form-base form ul.errors').parent().css({
		'background-color':'#FCFCCF',
		'border': '1px solid #E7E77E',
		'border-radius': '3px 3px 3px 3px',
		'margin-left': '-5px',
		'margin-top': '-25px',
		'margin-bottom': '10px',
		'padding': '25px 5px 5px',
		'width': '813px'
	});

	$('div.l8m-model-form-base form ul.errors').addClass('iconized');
	$('div.l8m-model-form-base form ul.errors li')
		.addClass('exclamation')
		.css('color', '#ff0000')
	;
	
	$('div.l8m-model-form-base form #captcha-element ul.errors').parent().find('img').css({
		'box-shadow': '0px 0px 5px #cccccc'
	});
	

	$('div.l8m-model-form-base form #captcha-element ul.errors').parent().find('img').parent().css({
		'margin-top': '-5px',
		'padding-top': '5px'
	});
	

	////////////////////////////////////////////////////////////
	// max length
	////////////////////////////////////////////////////////////
	
	$('input[data-max-length]').each(function() {
		$(this).after('<div class="max-length">' + $(this).val().length + ' von ' + $(this).attr('data-max-length') + ' Zeichen</div>');
	});

	$('input[data-max-length]').on('input', function() {
		$(this).parent().find('.max-length').html($(this).val().length + ' von ' + $(this).attr('data-max-length') + ' Zeichen');
	});

	$('textarea[data-max-length]').each(function() {
		$(this).after('<div class="max-length">' + $(this).val().length + ' von ' + $(this).attr('data-max-length') + ' Zeichen</div>');
	});

	$('textarea[data-max-length]').on('input', function() {
		$(this).parent().find('.max-length').html($(this).val().length + ' von ' + $(this).attr('data-max-length') + ' Zeichen');
	});
});