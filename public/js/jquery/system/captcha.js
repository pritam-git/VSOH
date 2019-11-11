////////////////////////////////////////////////////////////
//
// L8M
//
// 
// Contains captcha JS (jQuery).
//
// Filesource  /public/js/jquery/system/captcha.js
// Version     $Id: captcha.js 7 2014-03-11 16:18:40Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {
		
	////////////////////////////////////////////////////////////
	// add link for re-captcha
	////////////////////////////////////////////////////////////
	
	$('div.l8m-model-form-base form #captcha-element img').after('<a class="recaptcha">Re-Captcha</a>');
	
	$('div.l8m-model-form-base form #captcha-element a.recaptcha').click(function() {
		$.getJSON(this.href + '?do=re-captcha&geoCapId=' + $('div.l8m-model-form-base form #captcha-element #captcha-id').val(), function(data) {
			$('div.l8m-model-form-base form #captcha-element img').attr('src', data.src);
			$('div.l8m-model-form-base form #captcha-element #captcha-id').attr('value', data.id);
			$('div.l8m-model-form-base form #captcha-element #captcha-input').val();
		});
		return false;
	});
});