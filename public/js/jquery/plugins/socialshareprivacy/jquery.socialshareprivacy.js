/*
 * jquery.socialshareprivacy.js | 2 Klicks fuer mehr Datenschutz
 *
 * http://www.heise.de/extras/socialshareprivacy/
 * http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html
 *
 * Copyright (c) 2011 Hilko Holweg, Sebastian Hilbig, Nicolas Heiringhoff, Juergen Schmidt,
 * Heise Zeitschriften Verlag GmbH & Co. KG, http://www.heise.de
 *
 * is released under the MIT License http://www.opensource.org/licenses/mit-license.php
 *
 * Spread the word, link to us if you can.
 * 
 * jquery.socialshareprivacy-xl.js | Erweiterung für Xing und LinkedIn
 * 
 * http://www.illusions-schmiede.com/Tools/Socialshareprivacy-XL
 * 
 * Copyright (c) 2012 David Sann
 * Illusions-Schmiede Gmbh http://www.illusions-schmiede.com
 *
 * is released under the MIT License http://www.opensource.org/licenses/mit-license.php 
 * 
 * Some Changes concerning jquery 1.7 compatibility by Norbert Marks nm@l8m.com
 * 
 */
(function ($) {

	"use strict";

	/*
	 * helper functions
	 */ 

	// abbreviate at last blank before length and add "\u2026" (horizontal ellipsis)
	function abbreviateText(text, length) {
		var abbreviated = decodeURIComponent(text);
		if (abbreviated.length <= length) {
			return text;
		}

		var lastWhitespaceIndex = abbreviated.substring(0, length - 1).lastIndexOf(' ');
		abbreviated = encodeURIComponent(abbreviated.substring(0, lastWhitespaceIndex)) + "\u2026";

		return abbreviated;
	}

	// returns content of <meta name="" content=""> tags or '' if empty/non existant
	function getMeta(name) {
		var metaContent = $('meta[name="' + name + '"]').attr('content');
		return metaContent || '';
	}

	// create tweet text from content of <meta name="DC.title"> and <meta name="DC.creator">
	// fallback to content of <title> tag
	function getTweetText() {
		var title = getMeta('DC.title');
		var creator = getMeta('DC.creator');

		if (title.length > 0 && creator.length > 0) {
			title += ' - ' + creator;
		} else {
			title = $('title').text();
		}

		return encodeURIComponent(title);
	}

	// build URI from rel="canonical" or document.location
	function getURI() {
		var uri = document.location.href;
		var canonical = $("link[rel=canonical]").attr("href");

		if (canonical && canonical.length > 0) {
			if (canonical.indexOf("http") < 0) {
				canonical = document.location.protocol + "//" + document.location.host + canonical;
			}
			uri = canonical;
		}

		return uri;
	}

	function cookieSet(name, value, days, path, domain) {
		var expires = new Date();
		expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
		document.cookie = name + '=' + value + '; expires=' + expires.toUTCString() + '; path=' + path + '; domain=' + domain;
	}
	function cookieDel(name, value, path, domain) {
		var expires = new Date();
		expires.setTime(expires.getTime() - 100);
		document.cookie = name + '=' + value + '; expires=' + expires.toUTCString() + '; path=' + path + '; domain=' + domain;
	}

	// extend jquery with our plugin function
	$.fn.socialSharePrivacy = function (settings) {
		var defaults = {
				'services' : {
					'facebook' : {
						'status'            : 'on',
						'perma_option'      : 'on',
						'referrer_track'    : '',
						'action'            : 'recommend'
					}, 
					'twitter' : {
						'status'            : 'on', 
						'dummy_img'         : '/js/jquery/plugins/socialshareprivacy/socialshareprivacy/images/dummy_twitter.png',
						'perma_option'      : 'on',
						'referrer_track'    : '', 
						'tweet_text'        : getTweetText
					},
					'gplus' : {
						'status'            : 'on',
						'dummy_img'         : '/js/jquery/plugins/socialshareprivacy/socialshareprivacy/images/dummy_gplus.png',
						'perma_option'      : 'on',
						'referrer_track'    : ''
					},
					'xing' : {
						'status'            : 'on',
						'dummy_img'         : '/js/jquery/plugins/socialshareprivacy/socialshareprivacy/images/dummy_xing.png',
						'perma_option'      : 'on',
						'referrer_track'    : ''
					},
					'linkedin' : {
						'status'            : 'on',
						'dummy_img'         : '/js/jquery/plugins/socialshareprivacy/socialshareprivacy/images/dummy_linkedin.png',
						'perma_option'      : 'on',
						'referrer_track'    : ''
					}
				},
				'info_link'         : 'http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html',
				'cookie_path'       : '/',
				'cookie_domain'     : document.location.host,
				'cookie_expires'    : '365',
				'language'          : 'de',
				'css_path'          : '/js/jquery/plugins/socialshareprivacy/socialshareprivacy/socialshareprivacy.css',
				'lang_path'         : '/js/jquery/plugins/socialshareprivacy/socialshareprivacy/lang/',
				'uri'               : getURI
		};

		// Standardwerte des Plug-Ings mit den vom User angegebenen Optionen ueberschreiben
		var options = $.extend(true, defaults, settings);

		var facebook_on = (options.services.facebook.status === 'on');
		var twitter_on  = (options.services.twitter.status  === 'on');
		var gplus_on    = (options.services.gplus.status    === 'on');
		var xing_on     = (options.services.xing.status     === 'on');
		var linkedin_on = (options.services.linkedin.status === 'on');

		// check if at least one service is "on"
		if (!facebook_on && !twitter_on && !gplus_on && !xing_on && !linkedin_on) {
			return;
		}

		// insert stylesheet into document and prepend target element
		if (options.css_path.length > 0) {
			// IE fix (noetig fuer IE < 9 - wird hier aber fuer alle IE gemacht)
			if (document.createStyleSheet) {
				document.createStyleSheet(options.css_path);
			} else {
				$('head').append('<link rel="stylesheet" type="text/css" href="' + options.css_path + '" />');
			}
		}

		// language
		var languageInfo = null;
		var langCode = $('html').attr('lang');

		function loadLangFile() {
			var d = $.Deferred();
			
			$.getJSON(options.lang_path + langCode + '.lang', function(data) {
				languageInfo = data;
				d.resolve();
			}).fail(function(s){
				if (typeof console !== "undefined") {
					console.log('Error ' + s.status + ' while loading the language file (' + options.lang_path + langCode + '.lang)');
				}
				$.getJSON(options.lang_path + options.language + '.lang', function(data) {
					languageInfo = data;
					d.resolve();
				}).fail(function(s){
					if (typeof console !== "undefined") {
						console.log('Error ' + s.status + ' while loading the language file (' + options.lang_path + options.language + '.lang)');
					}
					d.reject();
				});
			});
			
			return d.promise();
		}

		return this.each(function () {
			var iteration = this;

			$.when(
				loadLangFile())
			.then( function() {
				$(iteration).prepend('<ul class="social_share_privacy_area"></ul>');
				var context = $('.social_share_privacy_area', iteration);
	
				// canonical uri that will be shared
				var uri = options.uri;
				if (typeof uri === 'function') {
					uri = uri(context);
				}
				
				
				//
				// Facebook
				//
				if (facebook_on) {
					var fb_root = '<div id="fb-root"></div>';
					var fb_script = '<script>(function(d, s, id) {' + "\n";
					var fb_script = fb_script + '  var js, fjs = d.getElementsByTagName(s)[0];' + "\n";
					var fb_script = fb_script + '  if (d.getElementById(id)) return;' + "\n";
					var fb_script = fb_script + '  js = d.createElement(s); js.id = id;' + "\n";
					var fb_script = fb_script + '  js.src = "//connect.facebook.net/' + languageInfo.services.facebook.language + '/sdk.js#xfbml=1&version=v2.4";' + "\n";
					var fb_script = fb_script + '  fjs.parentNode.insertBefore(js, fjs);' + "\n";
					var fb_script = fb_script + '}(document, \'script\', \'facebook-jssdk\'));</script>';
					
					var fb_code = '<div class="fb-like" data-href="' + uri + '" data-layout="button_count" data-action="' + options.services.facebook.action + '" data-show-faces="true" data-share="false"></div>';
					
					var fb_dummy_btn = '<img src="' + languageInfo.services.facebook.dummy_img + '" alt="Facebook &quot;Like&quot;-Dummy" class="fb_like_privacy_dummy" />';
	
					context.append('<li class="facebook help_info"><span class="info">' + languageInfo.services.facebook.txt_info + '</span><span class="switch off">' + languageInfo.services.facebook.txt_fb_off + '</span><div class="fb_like dummy_btn">' + fb_dummy_btn + '</div></li>');
	
					var $container_fb = $('li.facebook', context);
	
					$('li.facebook div.fb_like img.fb_like_privacy_dummy,li.facebook span.switch', context).click(function () {
						if ($container_fb.find('span.switch').hasClass('off')) {
							$container_fb.addClass('info_off');
							$container_fb.find('span.switch').addClass('on').removeClass('off').html(languageInfo.services.facebook.txt_fb_on);
							$container_fb.find('img.fb_like_privacy_dummy').replaceWith(fb_code);
							if (!$("div#fb-root").length) {
								$("body").append(fb_root);
							}
							$("body").append(fb_script);
						} else {
							$container_fb.removeClass('info_off');
							$container_fb.find('span.switch').addClass('off').removeClass('on').html(languageInfo.services.facebook.txt_fb_off);
							$container_fb.find('.fb_like').html(fb_dummy_btn);
						}
					});
				}
	
				//
				// Twitter
				//
				if (twitter_on) {
					var twitter_script = '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>';
					var twitter_code = '<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>';
					
					var twitter_dummy_btn = '<img src="' + options.services.twitter.dummy_img + '" alt="&quot;Tweet this&quot;-Dummy" class="tweet_this_dummy" />';
	
					context.append('<li class="twitter help_info"><span class="info">' + languageInfo.services.twitter.txt_info + '</span><span class="switch off">' + languageInfo.services.twitter.txt_twitter_off + '</span><div class="tweet dummy_btn">' + twitter_dummy_btn + '</div></li>');
	
					var $container_tw = $('li.twitter', context);
	
					$('li.twitter div.tweet img,li.twitter span.switch', context).click(function () {
						if ($container_tw.find('span.switch').hasClass('off')) {
							$container_tw.addClass('info_off');
							$container_tw.find('span.switch').addClass('on').removeClass('off').html(languageInfo.services.twitter.txt_twitter_on);
							$container_tw.find('img.tweet_this_dummy').replaceWith(twitter_code);
							$("body").append(twitter_script);
						} else {
							$container_tw.removeClass('info_off');
							$container_tw.find('span.switch').addClass('off').removeClass('on').html(languageInfo.services.twitter.txt_twitter_off);
							$container_tw.find('.tweet').html(twitter_dummy_btn);
						}
					});
				}
	
				//
				// Google+
				//
				if (gplus_on) {
					// fuer G+ wird die URL nicht encoded, da das zu einem Fehler fuehrt
					var gplus_uri = uri + options.services.gplus.referrer_track;
	
					// we use the Google+ "asynchronous" code, standard code is flaky if inserted into dom after load
					var gplus_code = '<div class="g-plusone" data-size="medium" data-href="' + gplus_uri + '"></div><script type="text/javascript">window.___gcfg = {lang: "' + languageInfo.services.gplus.language + '"}; (function() { var po = document.createElement("script"); po.type = "text/javascript"; po.async = true; po.src = "https://apis.google.com/js/plusone.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s); })(); </script>';
					var gplus_dummy_btn = '<img src="' + options.services.gplus.dummy_img + '" alt="&quot;Google+1&quot;-Dummy" class="gplus_one_dummy" />';
	
					context.append('<li class="gplus help_info"><span class="info">' + languageInfo.services.gplus.txt_info + '</span><span class="switch off">' + languageInfo.services.gplus.txt_gplus_off + '</span><div class="gplusone dummy_btn">' + gplus_dummy_btn + '</div></li>');
	
					var $container_gplus = $('li.gplus', context);
	
					$('li.gplus div.gplusone img,li.gplus span.switch', context).click(function () {
						if ($container_gplus.find('span.switch').hasClass('off')) {
							$container_gplus.addClass('info_off');
							$container_gplus.find('span.switch').addClass('on').removeClass('off').html(languageInfo.services.gplus.txt_gplus_on);
							$container_gplus.find('img.gplus_one_dummy').replaceWith(gplus_code);
						} else {
							$container_gplus.removeClass('info_off');
							$container_gplus.find('span.switch').addClass('off').removeClass('on').html(languageInfo.services.gplus.txt_gplus_off);
							$container_gplus.find('.gplusone').html(gplus_dummy_btn);
						}
					});
				}
	
				// Xing
				//
				if (xing_on) {
					var xing_enc_uri = encodeURIComponent(uri + options.services.xing.referrer_track);
					
					// we don't use the standard inclusion script from Xing, if we would do so the iframe includes the hole enclosing website
					var xing_code = '<div class="Xing"><iframe class="XING" width="161" scrolling="no" height="20" frameborder="0" style="padding:0px;border:none;margin:0px;overflow:hidden;background-color:transparent;" src="https://www.xing-share.com/app/share?op=get_share_button;url='+xing_enc_uri+';counter=right;lang=de;type=iframe;hovercard_position=1" allowtransparency="true"></iframe></div>';				
					var xing_dummy_btn = '<img src="' + options.services.xing.dummy_img + '" alt="Xing &quot;Share&quot;-Dummy" class="xing_privacy_dummy" />';
	
					context.append('<li class="xing help_info"><span class="info">' + languageInfo.services.xing.txt_info + '</span><span class="switch off">' + languageInfo.services.xing.txt_off + '</span><div class="xing dummy_btn">' + xing_dummy_btn + '</div></li>');
	
					var $container_xing = $('li.xing', context);
	
					$('li.xing div.xing img.xing_privacy_dummy,li.xing span.switch', context).click(function () {
						if ($container_xing.find('span.switch').hasClass('off')) {
							$container_xing.addClass('info_off');
							$container_xing.find('span.switch').addClass('on').removeClass('off').html(languageInfo.services.xing.txt_xing_on);
							$container_xing.find('img.xing_privacy_dummy').replaceWith(xing_code);
						} else {
							$container_xing.removeClass('info_off');
							$container_xing.find('span.switch').addClass('off').removeClass('on').html(languageInfo.services.xing.txt_xing_off);
							$container_xing.find('.Xing').html(xing_dummy_btn);
						}
					});
				}
	
				// LinkedIn
				//
				if (linkedin_on) {
					var linkedin_enc_uri = encodeURIComponent(uri + options.services.linkedin.referrer_track);
					
					var linkedin_code = '<div class="LinkedIn"><script src="//platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share"></script></div>';
					var linkedin_dummy_btn = '<img src="' + options.services.linkedin.dummy_img + '" alt="LinkedIn &quot;Share&quot;-Dummy" class="linkedin_privacy_dummy" />';
	
					context.append('<li class="linkedin help_info"><span class="info">' + languageInfo.services.linkedin.txt_info + '</span><span class="switch off">' + languageInfo.services.linkedin.txt_off + '</span><div class="linkedin dummy_btn">' + linkedin_dummy_btn + '</div></li>');
	
					var $container_linkedin = $('li.linkedin', context);
	
					$('li.linkedin div.linkedin img.linkedin_privacy_dummy,li.linkedin span.switch', context).click(function () {
						if ($container_linkedin.find('span.switch').hasClass('off')) {
							$container_linkedin.addClass('info_off');
							$container_linkedin.find('span.switch').addClass('on').removeClass('off').html(languageInfo.services.linkedin.txt_linkedin_on);
							$container_linkedin.find('img.linkedin_privacy_dummy').replaceWith(linkedin_code);
						} else {
							$container_linkedin.removeClass('info_off');
							$container_linkedin.find('span.switch').addClass('off').removeClass('on').html(languageInfo.services.linkedin.txt_linkedin_off);
							$container_linkedin.find('.linkedin').html(linkedin_dummy_btn);
						}
					});
				}
	
				//
				// Der Info/Settings-Bereich wird eingebunden
				//
				context.append('<li class="settings_info"><div class="settings_info_menu off perma_option_off"><a href="' + options.info_link + '"><span class="help_info icon"><span class="info">' + languageInfo.txt_help + '</span></span></a></div></li>');
	
				// Info-Overlays mit leichter Verzoegerung einblenden
				$('.help_info:not(.info_off)', context).bind('mouseenter', function () {
					var $info_wrapper = $(this);
					var timeout_id = window.setTimeout(function () { $($info_wrapper).addClass('display'); }, 500);
					$(this).data('timeout_id', timeout_id);
				});
				$('.help_info', context).bind('mouseleave', function () {
					var timeout_id = $(this).data('timeout_id');
					window.clearTimeout(timeout_id);
					if ($(this).hasClass('display')) {
						$(this).removeClass('display');
					}
				});
	
				var facebook_perma = (options.services.facebook.perma_option === 'on');
				var twitter_perma  = (options.services.twitter.perma_option  === 'on');
				var gplus_perma    = (options.services.gplus.perma_option    === 'on');
				var xing_perma     = (options.services.xing.perma_option     === 'on');
				var linkedin_perma = (options.services.linkedin.perma_option     === 'on');
	
				// Menue zum dauerhaften Einblenden der aktiven Dienste via Cookie einbinden
				// Die IE7 wird hier ausgenommen, da er kein JSON kann und die Cookies hier ueber JSON-Struktur abgebildet werden
				if (((facebook_on && facebook_perma)
						|| (twitter_on && twitter_perma)
						|| (gplus_on && gplus_perma)
						|| (xing_on && xing_perma)
						|| (linkedin_on && linkedin_perma))
						&& (!$.browser.msie || ($.browser.msie && $.browser.version > 7.0))) {
	
					// Cookies abrufen
					var cookie_list = document.cookie.split(';');
					var cookies = '{';
					var i = 0;
					for (; i < cookie_list.length; i += 1) {
						var foo = cookie_list[i].split('=');
						cookies += '"' + $.trim(foo[0]) + '":"' + $.trim(foo[1]) + '"';
						if (i < cookie_list.length - 1) {
							cookies += ',';
						}
					}
					cookies += '}';
					cookies = JSON.parse(cookies);
	
					// Container definieren
					var $container_settings_info = $('li.settings_info', context);
	
					// Klasse entfernen, die das i-Icon alleine formatiert, da Perma-Optionen eingeblendet werden
					$container_settings_info.find('.settings_info_menu').removeClass('perma_option_off');
	
					// Perma-Optionen-Icon (.settings) und Formular (noch versteckt) einbinden
					$container_settings_info.find('.settings_info_menu').append('<span class="settings">Einstellungen</span><form><fieldset><legend>' + languageInfo.settings_perma + '</legend></fieldset></form>');
	
	
					// Die Dienste mit <input> und <label>, sowie checked-Status laut Cookie, schreiben
					var checked = ' checked="checked"';
					if (facebook_on && facebook_perma) {
						var perma_status_facebook = cookies.socialSharePrivacy_facebook === 'perma_on' ? checked : '';
						$container_settings_info.find('form fieldset').append(
							'<input type="checkbox" name="perma_status_facebook" id="perma_status_facebook"'
								+ perma_status_facebook + ' /><label for="perma_status_facebook">'
								+ languageInfo.services.facebook.display_name + '</label>'
						);
					}
	
					if (twitter_on && twitter_perma) {
						var perma_status_twitter = cookies.socialSharePrivacy_twitter === 'perma_on' ? checked : '';
						$container_settings_info.find('form fieldset').append(
							'<input type="checkbox" name="perma_status_twitter" id="perma_status_twitter"'
								+ perma_status_twitter + ' /><label for="perma_status_twitter">'
								+ languageInfo.services.twitter.display_name + '</label>'
						);
					}
	
					if (gplus_on && gplus_perma) {
						var perma_status_gplus = cookies.socialSharePrivacy_gplus === 'perma_on' ? checked : '';
						$container_settings_info.find('form fieldset').append(
							'<input type="checkbox" name="perma_status_gplus" id="perma_status_gplus"'
								+ perma_status_gplus + ' /><label for="perma_status_gplus">'
								+ languageInfo.services.gplus.display_name + '</label>'
						);
					}
	
					if (xing_on && xing_perma) {
						var perma_status_xing = cookies.socialSharePrivacy_xing === 'perma_on' ? checked : '';
						$container_settings_info.find('form fieldset').append(
							'<input type="checkbox" name="perma_status_xing" id="perma_status_xing"'
								+ perma_status_xing + ' /><label for="perma_status_xing">'
								+ languageInfo.services.xing.display_name + '</label>'
						);
					}
	
					if (linkedin_on && linkedin_perma) {
						var perma_status_linkedin = cookies.socialSharePrivacy_linkedin === 'perma_on' ? checked : '';
						$container_settings_info.find('form fieldset').append(
							'<input type="checkbox" name="perma_status_linkedin" id="perma_status_linkedin"'
								+ perma_status_linkedin + ' /><label for="perma_status_linkedin">'
								+ languageInfo.services.linkedin.display_name + '</label>'
						);
					}
	
					// Cursor auf Pointer setzen fuer das Zahnrad
					$container_settings_info.find('span.settings').css('cursor', 'pointer');
	
					// Einstellungs-Menue bei mouseover ein-/ausblenden
					$($container_settings_info.find('span.settings'), context).bind('mouseenter', function () {
						var timeout_id = window.setTimeout(function () { $container_settings_info.find('.settings_info_menu').removeClass('off').addClass('on'); }, 500);
						$(this).data('timeout_id', timeout_id);
					}); 
					$($container_settings_info, context).bind('mouseleave', function () {
						var timeout_id = $(this).data('timeout_id');
						window.clearTimeout(timeout_id);
						$container_settings_info.find('.settings_info_menu').removeClass('on').addClass('off');
					});
	
					// Klick-Interaktion auf <input> um Dienste dauerhaft ein- oder auszuschalten (Cookie wird gesetzt oder geloescht)
					$($container_settings_info.find('fieldset input')).click(function (event) {
						var click = event.target.id;
						var service = click.substr(click.lastIndexOf('_') + 1, click.length);
						var cookie_name = 'socialSharePrivacy_' + service;
	
						if ($('#' + event.target.id + ':checked').length) {
							cookieSet(cookie_name, 'perma_on', options.cookie_expires, options.cookie_path, options.cookie_domain);
							$('form fieldset label[for=' + click + ']', context).addClass('checked');
						} else {
							cookieDel(cookie_name, 'perma_on', options.cookie_path, options.cookie_domain);
							$('form fieldset label[for=' + click + ']', context).removeClass('checked');
						}
					});
	
					// Dienste automatisch einbinden, wenn entsprechendes Cookie vorhanden ist
					if (facebook_on && facebook_perma && cookies.socialSharePrivacy_facebook === 'perma_on') {
						$('li.facebook span.switch', context).click();
					}
					if (twitter_on && twitter_perma && cookies.socialSharePrivacy_twitter === 'perma_on') {
						$('li.twitter span.switch', context).click();
					}
					if (gplus_on && gplus_perma && cookies.socialSharePrivacy_gplus === 'perma_on') {
						$('li.gplus span.switch', context).click();
					}
					if (xing_on && xing_perma && cookies.socialSharePrivacy_xing === 'perma_on') {
						$('li.xing span.switch', context).click();
					}
					if (linkedin_on && linkedin_perma && cookies.socialSharePrivacy_linkedin === 'perma_on') {
						$('li.linkedin span.switch', context).click();
					}
	
				}
			}); // .then()
		});     // this.each(function ()
	};          // $.fn.socialSharePrivacy = function (settings) {
}(jQuery));
