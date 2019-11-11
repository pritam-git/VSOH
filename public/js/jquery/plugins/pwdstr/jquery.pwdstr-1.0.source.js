/**
 * Thanks to Jan Jarfalk <jan.jarfalk@unwrongest.com> (http://unwrongest.com/projects/password-strength/)
 * 
 * Some major Changes made by Norbert Marks <nm@l8m.com>
 * [x] Translateable
 * [x] Changes in Output
 */
(function($){ 
	$.fn.extend({  
		pwdstr: function(el, translatedStrings) {
		
			defaultStrings = new Object({
				/** VARS - OPTIONS **/
				thePasswordIs: 'Your password is forceable in',
				oneYear: '1 year',
				xYears: 'years',
				oneMonth: '1 month',
				xMonths: 'months',
				oneDay: '1 day',
				xDays: 'days',
				oneHour: '1 hour',
				xHours: 'hours',
				oneMinute: '1 minute',
				xMinutes: 'minutes',
				oneSecond: '1 second',
				xSeconds: 'seconds',
				lessThan: 'less than one second'
			});

			if (typeof translatedStrings == 'object'){
				var key = '';
				for (key in translatedStrings) {
					if (defaultStrings.hasOwnProperty(key)) {
						defaultStrings[key] = translatedStrings[key];
					}
				}
			}

			return this.each(function() {
					$(this).keyup(function(){
						$(el).html(getTime($(this).val()));
					});
					
					$(this).bind('input propertychange', function() {
						$(el).html(getTime($(this).val()));
					});
					
					function getTime(str){
					
						var chars = 0;
						var rate = 2800000000;
						
						if((/[a-z]/).test(str)) chars +=  26;
						if((/[A-Z]/).test(str)) chars +=  26;
						if((/[0-9]/).test(str)) chars +=  10;
						if((/[^a-zA-Z0-9]/).test(str)) chars +=  32;
	
						var pos = Math.pow(chars,str.length);
						var s = pos/rate;
						
						var decimalYears = s/(3600*24*365);
						var years = Math.floor(decimalYears);
						
						var decimalMonths =(decimalYears-years)*12;
						var months = Math.floor(decimalMonths);
						
						var decimalDays = (decimalMonths-months)*30;
						var days = Math.floor(decimalDays);
						
						var decimalHours = (decimalDays-days)*24;
						var hours = Math.floor(decimalHours);
						
						var decimalMinutes = (decimalHours-hours)*60;
						var minutes = Math.floor(decimalMinutes);
						
						var decimalSeconds = (decimalMinutes-minutes)*60;
						var seconds = Math.floor(decimalSeconds);
						
						var time = [];
						
						if(years > 0){
							if(years == 1)
								time.push(defaultStrings.oneYear);
							else
								time.push(years + " " + defaultStrings.xYears);
						}
						if(months > 0){
							if(months == 1)
								time.push(defaultStrings.oneMonth);
							else
								time.push(months + " " + defaultStrings.xMonths);
						}
						if(days > 0){
							if(days == 1)
								time.push(defaultStrings.oneDay);
					 		else
								time.push(days + " " + defaultStrings.xDays);
						}
						if(hours > 0){
							if(hours == 1)
								time.push(defaultStrings.oneHour);
							else
								time.push(hours + " " + defaultStrings.xHours);
						}
						if(minutes > 0){
							if(minutes == 1)
								time.push(defaultStrings.oneMinute);
							else
								time.push(minutes + " " + defaultStrings.xMinutes);
						}
						if(seconds > 0){
							if(seconds == 1)
								time.push(defaultStrings.oneSecond);
							else
								time.push(seconds + " " + defaultStrings.xSeconds);
						}
						
						var outputString = '';
						if(time.length <= 0) {
							outputString = defaultStrings.lessThan;
						} else { 
							for (var i=0; i < time.length; i++) {
								if (i > 0) {
									outputString += ', ';
								}
								outputString += time[i];
							}
						}
						return defaultStrings.thePasswordIs + ' ' + outputString + '.';
					}
					
			 });
        } 
    }); 
})(jQuery); 