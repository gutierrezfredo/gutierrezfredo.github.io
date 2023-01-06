/* Activates magnific popup
------------------------------------------------------ */
jQuery(window).load(function(){
	jQuery('.open-popup-link a').magnificPopup({
	  type:'inline',
	  midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
	});
});