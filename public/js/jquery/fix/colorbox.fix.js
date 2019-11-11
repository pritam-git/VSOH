/**
 * this unbinds the close-event for colorbox and
 * setups some defaults to colorbox
 */

$().ready(function() {
	/**
	 * disable esc-button
	 */
	$().bind('cbox_load', function(){ 
		$().unbind("keydown.cbox_close"); 
	}); 
	
	/**
	 * disable close after clicking on overlay
	 */
	$.fn.colorbox.settings.overlayClose=false;
	
	/**
	 * some defaults on init
	 */
	$.fn.colorbox.settings.closeOopacity=0.7;
	$.fn.colorbox.settings.initialWidth=45;
	$.fn.colorbox.settings.initialHeight=45;
	
	/**
	 * doing the TinyMCE-Stuff
	 */
	$.fn.colorbox.settings.onComplete = function(){
		if(typeof localheinzTinyMCEinit == 'function') { 
			localheinzTinyMCEinit();
			localheinzMultiTABinit();
		}
	}
});