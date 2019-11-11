/* 
Simple JQuery menu.

*/

function initMenus() {
	$('ul.sidenav ul').hide();
	$.each($('ul.sidenav li.active'), function(){
		$('#ul' + this.id).show();
	});
	$('ul.sidenav li a').click(
		function() {
			var checkElement = $(this).next();
			var parent = this.parentNode.parentNode.id;

			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#' + parent + ' ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
}
$(document).ready(function() {initMenus();});