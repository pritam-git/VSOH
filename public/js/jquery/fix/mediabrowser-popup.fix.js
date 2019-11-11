function selectUrlImage(url, description, width, height, jsObjRefSrc, jsObjRefAlt, jsObjRefWidth, jsObjRefHeight, jsObjRefAbsUrl) {
	if ($('#' + jsObjRefAbsUrl, parent.document).length &&
		$('#' + jsObjRefAbsUrl, parent.document).attr('aria-checked') == 'true') {
		
		var wUrl = window.location.href;
		var arr = wUrl.split("/");
		var absUrl = arr[0] + "//" + arr[2];
		url = absUrl + url;
	}
	$('#' + jsObjRefSrc, parent.document).val(url);
	$('#' + jsObjRefAlt, parent.document).val(description);
	$('#' + jsObjRefWidth, parent.document).val(width);
	$('#' + jsObjRefHeight, parent.document).val(height);
	$('div.mediaBrowserPopUp', parent.document).remove();
} 

function selectUrlData(url, description, jsObjRefSrc, jsObjRefAlt) {
	$('#' + jsObjRefSrc, parent.document).val(url);
	if (description.length > 0) {
		$('#' + jsObjRefAlt, parent.document).val(description);
	}
	$('div.mediaBrowserPopUp', parent.document).remove();
}