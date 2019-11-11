////////////////////////////////////////////////////////////
//
// L8M Slider v1.00
//
// 
// Contains slider JS (jQuery).
//
// Filesource  /public/js/jquery.slider.js
// Version     $Id: base.js 4 2013-01-23 13:15:12Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

$(document).ready(function() {
	
	////////////////////////////////////////////////////////////
	// slider show
	////////////////////////////////////////////////////////////
	
	
	i = 1;
	max = $("div.slider").size();
	
	for(j = 2; j <= max; j++) {
		start_div = "div#img-" + j;
		$(start_div).removeClass("active");
	}
	slideTimer = window.setInterval("slide()", 6500);

	$("div#img-slider").width(width);
	$("div.slider").width(width);
	
	$("a#slide-right").click(function() {
		
		if(!$("a#slide-left").is(".active") &&
			!$("a#slide-right").is(".active")) {
		
			clearInterval(slideTimer);
			$("a#slide-left").addClass("active");
			var next = i + 1;
			if(next > max) {
				next = 1;
			}
			var div = "div#img-" + i;
			var div_next = "div#img-" + next;
			var point = "div#point-" + i;
			var point_next = "div#point-" + next;
			
			$(div_next).css("display", "block");
			$(div).animate({
				"right": "100%"
			},
			1500, function() {
				$(div).css("right", "-100%");
				$(div).css("display", "none");
				$(div).removeClass("active");
				$(div_next).addClass("active");
				$(point).removeClass("active");
				$(point_next).addClass("active");
				$("a#slide-left").removeClass("active");
				slideTimer = window.setInterval("slide()", 6500);
			});
			
			$(div_next).animate({
				"right": "0%"
			},
			1500
			);
			
			i++;
			
			if (i > max) {
				i = 1;
			}
		}
		
		return false;
	});
	
	$("a#slide-left").click(function() {
		
		if(!$("a#slide-left").is(".active") &&
			!$("a#slide-right").is(".active")) {
		
			clearInterval(slideTimer);
			$("a#slide-right").addClass("active");
			
			var next = i - 1;
			
			if(next < 1) {
				
				next = max;
			}
			
			var div_next = "div#img-" + next;
			var point_next = "div#point-" + next;
			
			$(div_next).css("right", "100%");
			$(div_next).css("display", "block");
			
			var div = "div#img-" + i;
			var point = "div#point-" + i;
			
			$(div).animate({
				"right": "-100%"
			},
			1500, function() {
				$(div).css("display", "none");
				$(div).removeClass("active");
				$(div_next).addClass("active")
				$(point).removeClass("active");
				$(point_next).addClass("active");
				$("a#slide-right").removeClass("active");
				slideTimer = window.setInterval("slide()", 6500);
			});
			
			$(div_next).animate({
				"right": "0%"
			},
			1500
			);
			
			i--;
			
			if (i < 1) {
				i = max;
			}
			
		}
		return false;
	});
	
	$(window).resize(function() {
		
		width = $(this).width();
		//if(width > 960) {
			$("div#img-slider").width(width);
			$("div.slider").width(width);
		//} else {
		//	$("div#img-slider").width(960);
		//	$("div.slider").width(960);
		//}
		
		
	});
	

	
});

var i = null;
var max = null;
var slideTimer = null;
var width = window.width;
var start_div = null;

	
function slide() {
	
	window.clearInterval(slideTimer);
	$("a#slide-left").addClass("active");
	$("a#slide-right").addClass("active");
	
	var next = i + 1;
	if(next > max) {
		next = 1;
	}
	var div = "div#img-" + i;
	var div_next = "div#img-" + next;
	var point = "div#point-" + i;
	var point_next = "div#point-" + next;
	
	$(div_next).css("display", "block");
	$(div).animate({
		"right": "100%"
	},
	1500, function() {
		$(div).css("right", "-100%");
		$(div).css("display", "none");
		$(div).removeClass("active");
		$(div_next).addClass("active");
		$(point).removeClass("active");
		$(point_next).addClass("active");
		$("a#slide-left").removeClass("active");
		$("a#slide-right").removeClass("active");
		slideTimer = window.setInterval("slide()", 6500);
	});
	
	$(div_next).animate({
		"right": "0%"
	},
	1500
	);
	
	i++;
	
	if (i > max) {
		i = 1;
	}
	
	return false;
}