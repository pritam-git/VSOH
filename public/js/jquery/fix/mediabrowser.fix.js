function selectMedia(mediaID, previewLink, jsObjRef, fileName) {
	$('#' + jsObjRef + 'IMG', parent.document).attr("src", previewLink);
	$('#' + jsObjRef + 'fileName', parent.document)
		.removeClass('file-name')
		.html(fileName)
	;
	$('#' + jsObjRef + 'download', parent.document).attr('href', '/media/' + mediaID);
	$('li#' + jsObjRef + 'LiDownload', parent.document).show();
	var pos = jsObjRef.search("media_image_id");
	if(pos != -1){
		$('li#' + jsObjRef + 'EditImage', parent.document).show();
		$('.' + jsObjRef + 'EditImageClass', parent.document).attr('id',mediaID);
	}
	$('#' + jsObjRef, parent.document).val(mediaID);
	$('div.mediaBrowserPopUpBackground', parent.document).remove();
	$('div.mediaBrowserPopUp', parent.document).remove();
}