////////////////////////////////////////////////////////////
//
// L8M
//
// 
// Contains some model-form-base JS (jQuery) for marking record as edited.
//
// Filesource  /public/js/jquery/system/mark-for-editor.js
// Version     $Id: mark-for-editor.js 37 2014-04-10 13:19:03Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

var l8mMarkForEditorJsonObj = null;
var l8mMarkForEditorInterval = null;

$(document).ready(function() {
		
	////////////////////////////////////////////////////////////
	// start interval
	////////////////////////////////////////////////////////////
	l8mMarkForEditorInterval = window.setInterval("l8mMarkForEditor()", 40000);

});


////////////////////////////////////////////////////////////
// functions
////////////////////////////////////////////////////////////

function l8mMarkForEditor() {
	window.clearInterval(l8mMarkForEditorInterval);
	
	if (l8mMarkForEditorJsonObj &&
			typeof l8mMarkForEditorJsonObj == 'object') {
		
		if (l8mMarkForEditorJsonObj.statusCode().statusText != 'OK' &&
				l8mMarkForEditorJsonObj.statusCode().statusText != 'abort') {
			
			l8mMarkForEditorJsonObj.abort();
		}
	}
	
	l8mMarkForEditorJsonObj = $.ajax({
		type: 'POST',
		url: '/system/mark-for-editor/renew',
		data: 'model='+$('div.l8m_model_form_base_element_identitfier').attr('data-model')+'&id='+$('div.l8m_model_form_base_element_identitfier').attr('data-id')+'&identifier='+$('#l8m_model_form_base_element_identitfier').attr('value'),
		success: function(data){
			if (data.newIdentifier) {
				$('#l8m_model_form_base_element_identitfier').attr('value', data.newIdentifier);
				$('div.l8m-model-form-base div.form-exception ul li.exclamation.marked-for-editor-error').remove();
				if ($('div.l8m-model-form-base div.form-exception ul li').length == 0) {
					$('div.l8m-model-form-base div.form-exception').remove();
				}
				l8mMarkForEditorInterval = window.setInterval("l8mMarkForEditor()", 40000);
			} else {
				$('#l8m_model_form_base_element_identitfier').attr('value', '');
			}
			if (data.error) {
				if ($('div.l8m-model-form-base div.form-exception ul li.exclamation.marked-for-editor-error').length > 0) {
					$('div.l8m-model-form-base div.form-exception ul li.exclamation.marked-for-editor-error').html(data.error);
				} else {
					$('div.l8m-model-form-base form').before('<div class="form-exception"><ul class="iconized"><li class="exclamation marked-for-editor-error">' + data.error + '</li></ul></div>');
				}
			}
		}
	});
}