jQuery(window).load(function() {
// 	if ( jQuery('input#s').length > 0 ) {
// 		jQuery('input#s').focusin(function() {
// 			if ( jQuery(this).outerWidth() < 36 )
// 				jQuery(this).animate({width: 250}, 250);
// 		});
// 		jQuery('input#s').focusout(function() {
// 			jQuery(this).css({width: ''});
// 		});
// 	}
	
	if ( jQuery('#show_comments').length > 0 ) {
		jQuery('#show_comments').bind('click', function(e) {
			e.preventDefault();
			jQuery('#comments, #hide_comments').show();
			jQuery('body, html').animate({scrollTop: jQuery('#comments').offset().top}, 200);
			jQuery(this).hide();
		});
	}
	if ( jQuery('#hide_comments').length > 0 ) {
		jQuery('#hide_comments').bind('click', function(e) {
			e.preventDefault();
			jQuery('#comments').hide();
			jQuery(this).hide();
			jQuery('#show_comments').show();
		});
	}
	
	if ( jQuery(window).width() < 760 ) {
		jQuery('.site-menu ul').children('li').hide();
		jQuery('.site-menu ul').prepend('<li id="menu-item-toggle"><a href="#" class="off">Menu »</a></li>');
		jQuery('#menu-item-toggle a').bind('click', function(e) {
			e.preventDefault();
			if ( jQuery(this).hasClass('off') ) {
				jQuery('.site-menu ul').children('li.menu-item').show();
				jQuery(this).removeClass('off').addClass('on').text('Menu «');
			}
			else {
				jQuery('.site-menu ul').children('li.menu-item').hide();
				jQuery(this).removeClass('on').addClass('off').text('Menu »');
			}
		});
		
		
	}
});