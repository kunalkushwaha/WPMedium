jQuery(document).ready(function() {
    
    if ( jQuery('#colorpicker').length > 0 ) {
        var f = jQuery.farbtastic('#colorpicker');
        var p = jQuery('#colorpicker').hide(0);
        var selected;
        
        jQuery('.colorpicker')
            .each(function () { f.linkTo(this); })
            .focus(function() {
                if (selected)
                    jQuery(selected).removeClass('colorpicker-selected');
                f.linkTo(this);
                p.show(0);
                pos = jQuery(this).position();
                jQuery('.color-picker').css({position: 'absolute', top: (pos.top), left: pos.left + 100});
            jQuery(selected = this).addClass('colorpicker-selected');
        });
        
        jQuery(document).mousedown(function() {
            jQuery('#colorpicker').each(function() {
                var display = jQuery(this).css('display');
                if ( display == 'block' )
                    jQuery(this).hide(0);
            });
        });
    }
});