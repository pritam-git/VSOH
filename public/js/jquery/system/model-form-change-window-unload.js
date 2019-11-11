////////////////////////////////////////////////////////////
//
// L8M
//
// 
// Contains some model-form-base JS (jQuery) for marking form values as changed.
//
// Filesource  /public/js/jquery/model-form-change-window-unload.js
// Version     $Id: model-form-change-window-unload.js 410 2015-09-10 17:34:10Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

var l8mModelFormUnloadInterval = null;
var l8mModelFormSubmitFormOkay = true;

$(document).ready(function() {
		
	////////////////////////////////////////////////////////////
	// start interval
	////////////////////////////////////////////////////////////
	l8mModelFormUnloadInterval = window.setInterval("l8mModelFormUnload()", 2000);
	$(window).bind('beforeunload', function(e){
		if (!l8mModelFormSubmitFormOkay) {
			if (l8mGetModelFormDataWithoutIdentifier() != $('div.l8m-model-form-base form').data('serialize')) {
				e.returnValue = ' '
				e.preventDefault();
				return ' ';
			} else {
				// i.e; if form state change show box not.
				e = null;
			}
		} else {
			// i.e; if form state change show box not.
			e = null;
		}
	});
});


////////////////////////////////////////////////////////////
//functions
////////////////////////////////////////////////////////////

function l8mModelFormUnload() {
	window.clearInterval(l8mModelFormUnloadInterval);
	
	$('div.l8m-model-form-base form').data('serialize', l8mGetModelFormDataWithoutIdentifier());
	l8mModelFormSubmitFormOkay = false;
}

function l8mGetModelFormDataWithoutIdentifier() {
	var serilizedForm = $('div.l8m-model-form-base form').serialize();
	var identifierValue = $('#l8m_model_form_base_element_identitfier').attr('value');
	if (typeof identifierValue == 'undefined') {
		identifierValue = '';
	}
	var serilizedIdentifierValue = identifierValue.replace(/\s/g,'+');
	var newSerilizedForm = serilizedForm.replace("&l8m_model_form_base_element_identitfier=" + serilizedIdentifierValue,"");
	return newSerilizedForm;
}