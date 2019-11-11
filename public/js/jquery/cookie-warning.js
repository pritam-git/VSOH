////////////////////////////////////////////////////////////
//
// L8M
//
// 
// cookie-warning JS (jQuery).
//
// Filesource  /public/js/jquery/prj/cookie-warning.js
// Version     $Id: cookie-warning.js 174 2014-10-31 11:13:24Z nm $
//
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
// content arrays
////////////////////////////////////////////////////////////
var text = {};
text.de = '<b>Diese Webseite verwendet Cookies:</b><br>Nach den Cookie-Einstellungen auf dieser Webseite werden alle Cookies akzeptiert. Nur auf diese Weise können Sie unsere Webseite ohne Einschränkungen nutzen. Sie können Ihre Cookie-Einstellungen jederzeit in Ihren Browser-Einstellungen ändern.';
text.en = 'Cookies help us to provide our services. By using our services, you agree that we use cookies.';
text.fr = '<b>Ce site web utilise des cookies:</b><br>Les paramètres des cookies sur ce site sont réglés sur «Autoriser tous les cookies». Ceci a pour objectif de vous garantir la meilleure expérience possible lorsque vous consultez notre site. Vous pouvez régler les paramètres des cookies à tout moment dans les préférences de votre navigateur.';

var link = {};
link.de = '/datenschutz/';
link.en = '/en/privacy-policy/';
link.fr = '/en/privacy-policy/';

var label = {};
label.de = 'Mehr erfahren';
label.en = 'Learn more';
label.fr = 'En savoir plus';

////////////////////////////////////////////////////////////
// CSS
////////////////////////////////////////////////////////////
var backgroundColor = 'rgb(204, 204, 204)';
var padding			= '16px 40px 16px 12px';
var shadow			= '0 0 3px #fff';
var fontColor		= '#000';

////////////////////////////////////////////////////////////
// vars
////////////////////////////////////////////////////////////
var cookieName = 'cookie-warning';
var position = 'bottom';
var elementToPrepend = 'body';

function createCookie(name,value) {
	var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
	var container = $('div.cookie-warning').fadeOut();
}

$(document).ready(function() {
	
	var cookie = false;
	var language = $('html').attr('lang');
	
	var nameEQ = cookieName + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) {
			var cookie = true;
		}
	}
	
	if (cookie === false) {
		$(elementToPrepend).prepend('<div class="cookie-warning"><a href="" class="ink-button blue ok"><i class="fa fa-times fa-2x" aria-hidden="true"></i></a>' + text[language] + ' <a href="" class="ink-button blue privacy" data-href="' + link[language] + '">' + label[language] + '</a> </div>');
		var container = $('div.cookie-warning');
		//container.css('position', 'fixed');
		container.css('font-family', 'Arial, sans-serif');
		container.css('width', '100%');
		container.css(position, '0px');
		container.css('font-size', '14px');
		container.css('color', fontColor);
		container.css('line-height', '20px');
		container.css('z-index', '1000');
		container.css('min-height', '44px');
		container.css('box-sizing', 'border-box');
		container.css('background-color', backgroundColor);
		container.css('padding', padding);
		container.css('box-shadow', shadow);
		container.css('min-width', '320px');
		var buttons = $('div.cookie-warning a.ink-button');
		buttons.css('color', '#000');
		var buttonP = $('div.cookie-warning a.ink-button.privacy');
		buttonP.css('text-decoration', 'underline');
		var buttonO = $('div.cookie-warning a.ink-button.ok');
		buttonO.css('float', 'right');
		buttonO.css('margin-top', '-16px');
		buttonO.css('margin-right', '-40px');
		buttonO.css('background-color', '#fff');
		buttonO.css('padding', '5px 10px');
	}
	
	$('div.cookie-warning a.ok').click(function(e){
		createCookie(cookieName, true);
		e.preventDefault();
	});
	
	$('div.cookie-warning a.privacy').click(function(e){
		window.location.href = $('div.cookie-warning a.privacy').attr('data-href');
		e.preventDefault();
	});
	
});