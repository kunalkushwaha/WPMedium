jQuery(window).load(function() {
	jQuery('input#s').focusin(function() {
		jQuery(this).animate({width: 250}, 250);
	});
	jQuery('input#s').focusout(function() {
		jQuery(this).css({width: ''});
	});
});