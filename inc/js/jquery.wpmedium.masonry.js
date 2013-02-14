jQuery(window).load(function() {
	jQuery('#home #content, #archive #content').masonry({
		itemSelector: '.post',
	});
	
	jQuery(window).resize(function() {
		jQuery('#home #content, #archive #content').masonry({
			itemSelector: '.post',
		});
	});
});