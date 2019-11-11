////////////////////////////////////////////////////////////
//
// L8M
//
// 
// Contains base JS (jQuery).
//
// Filesource  /public/js/jquery/base.js
// Version     $Id: base.js 407 2015-09-10 10:39:52Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {
		
	////////////////////////////////////////////////////////////
	// open links classed with "external" in new window
	////////////////////////////////////////////////////////////
	
	$(this).on('click', 'a.external', function (event) {
		window.open(this.href);
		event.preventDefault();
	});
	
	////////////////////////////////////////////////////////////
	// print link
	////////////////////////////////////////////////////////////
	
	$(this).on('click', 'a#print', function (event) {
		window.print();
		event.preventDefault();
	});

	////////////////////////////////////////////////////////////
	// required form elements
	////////////////////////////////////////////////////////////
	
	$("form label.required").append(' <span class="required-sign">*</span>');
	
	////////////////////////////////////////////////////////////
	// clear cache link
	////////////////////////////////////////////////////////////	
	
	$(this).on('click', 'a#cache-clear', function (event) {
		$.ajax({
			url: "/system/cache/clear/format/html",
			type: "GET",  
			cache: false,  
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("#ZFDebug_debug span.cache-tab").html(data);
				$("#ZFDebug_debug div.cache-data").html("");
			},
			error: function (request, textStatus, errorThrown) {
			}
		});
		event.preventDefault();
	});
	
	////////////////////////////////////////////////////////////
	// clear session link
	////////////////////////////////////////////////////////////
	
	$(this).on('click', 'a#session-clear', function (event) {
		$.ajax({
			url: "/system/session/clear/format/html",
			type: "GET",  
			cache: false,  
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("#ZFDebug_debug span.session-tab").html(data);
				$("#ZFDebug_debug div.session-data").html("");
			},
			error: function (request, textStatus, errorThrown) {
			}
		});
		event.preventDefault();
	});
	
	////////////////////////////////////////////////////////////
	// clear ZFDebug timer
	////////////////////////////////////////////////////////////
	
	$(this).on('click', 'a#timers-clear', function (event) {
		$.ajax({
			url: "/?ZFDEBUG_RESET",
			type: "GET",  
			cache: false,  
			complete: function (request, textStatus) {
			},
			success: function (data, textStatus) {
				$("div#ZFDebug_timers").html("");
			},
			error: function (request, textStatus, errorThrown) {
			}
		});
		event.preventDefault();
	});
	
});