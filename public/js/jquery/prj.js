////////////////////////////////////////////////////////////
//
// L8M
//
//
// Contains base JS (jQuery).
//
// Filesource  /public/js/jquery/prj.js
// Version     $Id: prj.js 407 2015-09-10 10:39:52Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {

	$("#mobile-menu").mmenu({
		"extensions": [
			"pagedim-black"
		],
		"offCanvas": {
			"position": "left"
		},
		"navbar": {
			"title": "Menü"
		}
	});

	var API = $('#mobile-menu').data('mmenu');

	$('#mobile-menu').find('.dropdown-menu').removeClass('dropdown-menu');


	////////////////////////////////////////////////////////////
	// video options
	////////////////////////////////////////////////////////////
	$( window ).resize(function() {
		if ($(window).width() > 1199) {
			 API.close();
		}
	});

	checkScroll();

	$(window).scroll(function() {

		checkScroll();
	});

	$('a#back-to-top').on('click', function(event) {
		$('html, body').stop().animate({
			scrollTop: 0
		}, 500);
		event.preventDefault();
	});

	$("form .control-group").addClass('form-group');

	$('#formSearchProtocol div:first-child').addClass('col-xs-12 p-0');
	$('#formSearchProtocol #searchProtocolInput-container').addClass('col-xs-9 col-sm-10 col-md-11 pl-0');
	$('#formSearchProtocol #formSearchSubmit-container').addClass('col-xs-3 col-sm-2 col-md-1 p-0');

});

function checkScroll() {

	scroll = $(window).scrollTop();

	if (scroll > 100) {
		$('a#back-to-top').addClass('show');
	} else {
		$('a#back-to-top').removeClass('show');
	}

	if (scroll > 0 &&
		$(window).width() > 768) {
		$('body').addClass('fixed');
	} else {
		$('body').removeClass('fixed');
	}

}