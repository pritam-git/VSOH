////////////////////////////////////////////////////////////
//
// L8M
//
// 
// Contains geo JS (jQuery).
//
// Filesource  /public/js/jquery/system/geo.js
// Version     $Id: geo.js 35 2014-04-04 21:56:07Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// addOnLoad
////////////////////////////////////////////////////////////

var l8mLoginGeoTimeoutErrorCounter = 0;

$(document).ready(function() {
		
	////////////////////////////////////////////////////////////
	// do some geo stuff
	////////////////////////////////////////////////////////////
	
	if (navigator.geolocation) {
		changeGeoHint('hint');
		$.getJSON(document.location.href + '?do=geo&hasGeo=true&geoCapId=' + $('div.l8m-model-form-base form #captcha-element #captcha-id').val(), function(data) {});
		$('div.l8m-model-form-base').addClass('geo-form-locator');
		
		// Request a position. We accept positions whose age is not
		// greater than 1 minutes. If the user agent does not have a
		// fresh enough cached position object, it will automatically
		// acquire a new one.
		navigator.geolocation.getCurrentPosition(navSuccessCallback, navErrorCallback, {enableHighAccuracy:true, timeout:10000, maximumAge:60000});
	} else {
		changeGeoHint('unavailable');
		$('div.l8m-model-form-base').addClass('geo-form-error');
	}
	function navSuccessCallback(position) {
		// By using the 'maximumAge' option above, the position
		// object is guaranteed to be at most 1 minutes old.
		$('div.l8m-model-form-base form #lat').val(position.coords.latitude);
		$('div.l8m-model-form-base form #lon').val(position.coords.longitude);
		$('div.l8m-model-form-base form #alt').val(position.coords.altitude);
		$('div.l8m-model-form-base form #acc').val(position.coords.accuracy);
		$('div.l8m-model-form-base form #altacc').val(position.coords.altitudeAccuracy);
		if (position.coords.heading) {
			$('div.l8m-model-form-base form #hea').val(position.coords.heading);
		}
		if (position.coords.speed) {
			$('div.l8m-model-form-base form #spe').val(position.coords.speed);
		}
		$('div.l8m-model-form-base').addClass('geo-form-success');
		changeGeoHint('success');

		$('div.required-form-hint').after('<input type="submit" value="' + $('div.box.geo').attr('data-login') + '" id="formUserLoginSubmit" name="formUserLoginSubmit">');
	}

	function changeGeoHint(hintVal) {
		if (hintVal == "hint") {
			$('div.box.geo').removeClass('geo-error');
			$('div.box.geo').addClass('geo-hint');
		} else 
		if (hintVal == "success") {
			$('div.box.geo').removeClass('geo-hint');
			$('div.box.geo').removeClass('geo-error');
			$('div.box.geo').addClass('geo-success');
		} else {
			$('div.box.geo').removeClass('geo-hint');
			$('div.box.geo').addClass('geo-error');
		}
		$('div.box.geo').html($('div.box.geo').attr('data-' + hintVal));
	}
	
	function navErrorCallback(error) {
		var error = 'UNKNOWN_ERROR';
		switch(error.code) {
			case error.TIMEOUT:
				error = 'TIMEOUT';
				changeGeoHint('timeout');
				break;
			case error.PERMISSION_DENIED:
				error = 'PERMISSION_DENIED';
				changeGeoHint('denied');
				break;
			case error.POSITION_UNAVAILABLE:
				error = 'POSITION_UNAVAILABLE';
				changeGeoHint('unavailable');
				break;
			case error.UNKNOWN_ERROR:
				error = 'UNKNOWN_ERROR';
				changeGeoHint('unknown');
				break;
		};
		if (error == 'TIMEOUT') {
			if (l8mLoginGeoTimeoutErrorCounter <= 6) {
				l8mLoginGeoTimeoutErrorCounter++;
				navigator.geolocation.getCurrentPosition(navSuccessCallback, navErrorCallback, {enableHighAccuracy:true, timeout:10000, maximumAge:60000});
			} else {
				$.getJSON(document.location.href + '?do=geo&hasGeo=false&geoCapId=' + $('div.l8m-model-form-base form #captcha-element #captcha-id').val() + '&geoError=' + error, function(data) {});
				$('div.l8m-model-form-base').addClass('geo-form-error');
			}
		} else {
			$.getJSON(document.location.href + '?do=geo&hasGeo=false&geoCapId=' + $('div.l8m-model-form-base form #captcha-element #captcha-id').val() + '&geoError=' + error, function(data) {});
			$('div.l8m-model-form-base').addClass('geo-form-error');
		}
	}
});