////////////////////////////////////////////////////////////
//
// L8M
//
//
// Contains session JS (jQuery).
//
// Filesource  /public/js/jquery/session.js
// Version     $Id: session.js 285 2019-04-02 06:17:03Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {
	if ($('#first-overlay-box').length > 0) {
		$('#first-overlay-box a').click(function(event) {
			event.preventDefault();
			var elem = $(this);
			var url = '/index/set-brand/brand/' + $(this).attr('data-brand');
			setBrandAjax(url,elem);
		});
	}

	$('#changeBrandLink').click(function (e) {
		e.preventDefault();
		var elem = $(this);
		var url = '/index/set-brand/';
		setBrandAjax(url,elem);
	});
});

function setBrandAjax(url,elem) {
	$.ajax({
		method: 'GET',
		url: url,
		async: false
	}).done(function(data) {
		document.location.href = elem.attr('href');
	});
}