jQuery(document).ready(function($) {
    
    if ( $('#upload_logo_button').length > 0 ) {
    
        $('#upload_logo_button').click(function(e) {
            tb_show('Mediacenter', 'media-upload.php?referer=wpmedium_theme_options&type=image&TB_iframe=true&post_id=0', false);  
            e.preventDefault();
        });
        
        $('#delete_logo_button').click(function(e) {
            $('#site_logo').val('');
            $('#upload_logo_preview').find('img').remove();
            $(this).hide();
        });
        
        window.send_to_editor = function(html) {  
            var image_url = $('img',html).attr('src');
            $('input#site_logo').val(image_url);
            tb_remove();
            $('#delete_logo_button').show();
            if ( $('#upload_logo_preview img').length > 0 )
                $('#upload_logo_preview img').attr('src', image_url);
            else
                $('#upload_logo_preview').html('<img style="max-width:100%;" src="'+image_url+'" />');
            $('#submit_general_options').trigger('click');
        }
    }
    
    if ( $('#upload_taxonomy_image').length > 0 ) {
    
        $('#upload_taxonomy_image').click(function(e) {
            tb_show('Mediacenter', 'media-upload.php?referer=wpmedium_taxonomy_image&type=image&TB_iframe=true&post_id=0', false);
            e.preventDefault();
        });
        
        $('#delete_taxonomy_image').click(function(e) {
            $('input#wpmedium_taxonomy_image').val('');
            $('#upload_taxonomy_image_preview img').remove();
            $(this).hide();
        });
        
        window.send_to_editor = function(html) {
            var image_url = $('img',html).attr('src');  
            $('input#wpmedium_taxonomy_image').val(image_url);  
            tb_remove();
            $('#delete_taxonomy_image').show();
            if ( $('#upload_taxonomy_image_preview img').length > 0 )
                $('#upload_taxonomy_image_preview img').attr('src', image_url);
            else
                $('#upload_taxonomy_image_preview').html('<img style="max-width:100%;" src="'+image_url+'" />');
        }
    }
    
    $('.theme_options_menu a').click(function(e) {
        id = this.id.replace('__','');
        $('.theme_options_panel').removeClass('active');
        $('#'+id).addClass('active');
        $('.theme_options_menu a').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    
    $('.theme-panel-submit').click(function(e) {
        id = this.id.replace('submit_','');
        referer = $('#'+id).find('input[name=_wp_http_referer]');
        referer.val(referer.prop('value')+'&tab='+id);
    });
    
    $('#header_overlay_opacity').change(function() {
        update_color();
    });
    
    $('#header_overlay_opacity').keyup(function() {
        val = $(this).val();
        if ( val >= 0 && val <= 100 ) {
            $('#slider-range-max').slider({value: val});
            update_color();
        }
        else if ( val < 0 ) {
            $('#slider-range-max').slider({value: 0});
            $(this).val('0');
            update_color();
        }
        else if ( val > 100 ) {
            $('#slider-range-max').slider({value: 100});
            $(this).val('100');
            update_color();
        }
    });
    
    
    
});

function hex2rgb( colour ) {
    var r,g,b;
    if ( colour.charAt(0) == '#' )
        colour = colour.substr(1);

    r = colour.charAt(0)+''+colour.charAt(1);
    g = colour.charAt(2)+''+colour.charAt(3);
    b = colour.charAt(4)+''+colour.charAt(5);

    r = parseInt( r, 16 );
    g = parseInt( g, 16 );
    b = parseInt( b, 16 );
    
    return 'rgb('+r+','+g+','+b+')';
}

function hex2rgba( colour, opacity ) {
    return hex2rgb(colour).replace('rgb', 'rgba').replace(')', ','+opacity+')');
}

function update_color() {
    val = jQuery('#header_overlay_opacity').val();
    if ( jQuery('#header_overlay_color').val() != '' )
        jQuery('#slider-range-max').css({backgroundColor: hex2rgba(jQuery('#header_overlay_color').val(), (val/100))});
    else
        jQuery('#slider-range-max').css({backgroundColor: hex2rgba('#000000', (val/100))});
}