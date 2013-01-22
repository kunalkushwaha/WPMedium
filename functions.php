<?php

// Adds locales
load_theme_textdomain( 'wpmedium', get_template_directory() . '/lang' );

// Adds RSS feed links to <head> for posts and comments.
add_theme_support( 'automatic-feed-links' );

// This theme supports a variety of post formats.
//add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

// Useless, but required...
register_sidebar( array( 'name' => 'some sidebar' ) );

// This theme uses wp_nav_menu() in one location.
register_nav_menu( 'primary', __( 'Primary Menu', 'wpmedium' ) );

// This theme uses a custom image size for featured images, displayed on "standard" posts.
add_theme_support( 'post-thumbnails' );
// Unlimited height, soft crop
set_post_thumbnail_size( 624, 9999 );

if ( !isset( $content_width ) )
    $content_width = 900;

require( 'inc/custom-header.php' );

$options = array();
$options['general'] = get_option( 'wpmedium_theme_general_options' );
$options['display'] = get_option( 'wpmedium_theme_display_options' );
$options['social']  = get_option( 'wpmedium_theme_social_options'  );

// Custom methods
function get_short_excerpt( $excerpt, $length = 15 ) {
    return implode( ' ', array_slice( explode( ' ', strip_shortcodes( strip_tags( $excerpt ) ) ), 0, $length ) ).' [...]';
}

function get_long_excerpt( $excerpt, $length = 125 ) {
    return implode( ' ', array_slice( explode( ' ', strip_shortcodes( strip_tags( $excerpt ) ) ), 0, $length ) ).' [...]';
}

function the_short_excerpt( $excerpt, $length = 15 ) {
    echo get_short_excerpt( $excerpt, $length );
}

function the_long_excerpt( $excerpt, $length = 125 ) {
    echo get_long_excerpt( $excerpt, $length );
}

function wpmedium_post_thumbnail() {
    global $post;
    
    if ( has_post_thumbnail( $post->ID ) )
        $ret = get_the_post_thumbnail( $post->ID );
    else
        $ret = '<img src="'.get_template_directory_uri().'/images/post_thumbnail.jpg" alt="'.get_the_title( $post->ID ).'" class="attachment-post-thumbnail wp-post-image" />';
    
    return $ret;
}

function wpmedium_the_post_thumbnail() {
    echo wpmedium_post_thumbnail();
}

function wpmedium_post_thumbnail_credit() {
    global $post;
    
    if ( has_post_thumbnail( $post->ID ) )
        $ret = sprintf( '<span class="entry-thumb-credit">%s</span>', get_post( get_post_thumbnail_id( $post->ID ) )->post_content );
    else
        $ret = '';
    
    return $ret;
    
}

function wpmedium_the_post_thumbnail_credit() {
    echo wpmedium_post_thumbnail_credit();
}

function get_category_count() {
    $category = get_the_category();
    return $category[0]->category_count;
}

function wpmedium_get_the_category_list( $limit = 3 ) {
    $categories = explode( ', ', get_the_category_list( ', ' ) );
    if ( count( $categories ) > $limit )
        return implode( ', ', array_slice( $categories, 0, $limit ) ) . ', <a href="'.get_permalink().'">...</a>';
    else
        return get_the_category_list( ', ' );
}

function get_archive_controls() {
    
    $recommended = '';
    $recent      = '';
    
    if ( $_GET['order_by'] == '' || $_GET['order_by'] == 'comment_count' ) {
        $recommended .= '<li class="archive-recommended-posts"><span class="active">'.__( 'Recommend', 'wpmedium' ).'</span></li>';
        $recent      .= '<li class="archive-recent-posts"><a href="?order_by=date&amp;order=DESC" class="">'.__( 'Recent', 'wpmedium' ).'</a></li>';
    }
    else if ( $_GET['order_by'] == 'date' ) {
        $recommended .= '<li class="archive-recommended-posts"><a href="?order_by=comment_count&amp;order=DESC" class="">'.__( 'Recommend', 'wpmedium' ).'</a></li>';
        $recommended .= '<li class="archive-recent-posts"><span class="active">'.__( 'Recent', 'wpmedium' ).'</span></li>';
    }
    
    return $recommended."\n".$recent;
}

function the_archive_controls() {
    echo get_archive_controls();
}

function get_index_controls() {
    
    $newest = '';
    $oldest = '';
    
    if ( $_GET['order'] == '' || $_GET['order'] == 'DESC' ) {
        $newest .= '<li class="site-categories-newest"><a class="active" href="?order=DESC">'.__( 'Newest', 'wpmedium' ).'</a></li>';
        $oldest .= '<li class="site-categories-oldest"><a href="?order=ASC">'.__( 'Oldest', 'wpmedium' ).'</a></li>';
    }
    else if ( $_GET['order'] == 'ASC' ) {
        $newest .= '<li class="site-categories-newest"><a href="?order=DESC">'.__( 'Newest', 'wpmedium' ).'</a></li>';
        $oldest .= '<li class="site-categories-oldest"><a class="active" href="?order=ASC">'.__( 'Oldest', 'wpmedium' ).'</a></li>';
    }
    
    return $newest."\n".$oldest;
}

function the_index_controls() {
    echo get_index_controls();
}

function get_site_logo() {
    $options = get_option( 'wpmedium_theme_general_options' );
    if ( $options['site_logo'] != '' )
        return '<img class="site-avatar" src="'.esc_url( $options['site_logo'] ).'" alt="" />';
    else
        return '<img class="site-avatar" src="'.get_template_directory_uri().'/images/wp-badge.png" alt="" style="height:auto;margin:-22px 0 0 -42px;" />';
}

function the_site_logo() {
    echo get_site_logo();
}

function get_social_links() {
    global $options;
    
    $ret = '';
    
    if ( count( $options['social'] ) > 0 ) {
        foreach ( $options['social'] as $network => $url ) {
            if ( $url != '' )
                $ret .= '<a href="'.esc_url( $url ).'"><i class="'.str_replace( '_profile', '', $network ).'" style="background-image:url('.get_template_directory_uri().'/images/icons/picon_social/'.str_replace( '_profile', '', $network ).'.png)"></i></a> ';
        }
    }
    
    return $ret;
}

function the_social_links() {
    echo get_social_links();
}

/**
 * Return the custom category image
 * If no image is properly defined, fallback to the latest category's post
 * thumbnail. If the category is empty, use the theme's logo
 */
function get_the_category_image() {
    
    global $options;
    
    $category = get_the_category();
    
    if ( $category ) {
        
        $category_images = get_option( 'wpmedium_category_images' );
        $category_image = '';
        
        if ( is_array( $category_images ) && array_key_exists( $category[0]->term_id, $category_images ) && $category_images[$category[0]->term_id] != '' ) {
            $ret = $category_images[$category[0]->term_id];
        }
        else {
            $category_posts = get_posts( array( 'numberposts' => 1, 'category' => $category[0]->term_id ) );
            if ( has_post_thumbnail( $category_posts[0]->ID ) ) {
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $category_posts[0]->ID ), 'medium' );
                $ret = $thumbnail[0];
            }
        }
    }
    else {
        $ret = $options['general']['site_logo'];
    }
    
    return $ret;
}

/**
 * Display the custom category image
 */
function the_category_image() {
    echo get_the_category_image();
}

/**
 * Hexadecimal to RGB color conversion
 */
function hex2rgb( $colour ) {
    if ( $colour[0] == '#' )
        $colour = substr( $colour, 1 );
    
    if ( strlen( $colour ) == 6 )
        list( $r, $g, $b ) = array( $colour[0].$colour[1], $colour[2].$colour[3], $colour[4].$colour[5] );
    elseif ( strlen( $colour ) == 3 )
        list( $r, $g, $b ) = array( $colour[0].$colour[0], $colour[1].$colour[1], $colour[2].$colour[2] );
    else
        return false;
    
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    
    return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Add custom images to display along with category description and title
 * selection/upload using WP media-upload
 */
function add_image_cat( $taxinomy ) {
    
    $category_images = get_option( 'wpmedium_category_images' );
    $category_image = '';
    
    if ( is_array( $category_images ) && array_key_exists( $taxinomy->term_id, $category_images ) )
        $category_image = $category_images[$taxinomy->term_id] ;
    
    if ( '' != $category_image )
        $style = 'style="width: 100px;"';
    else
        $style = 'style="display:none;width: 100px;"';
?>
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row" valign="top">
				<label for="auteur_revue_image"><?php _e( 'Category Image', 'wpmedium' ); ?></label>
			</th>
			<td>
				<div id="upload_category_image_preview" style="">
<?php ?>
<?php if ( '' != $category_image ) { ?>
					<img style="max-width:100%;" src="<?php echo $category_image; ?>" />
<?php } ?>
				</div>
				
				<input type="hidden" id="wpmedium_category_image" name="wpmedium_category_image" value="<?php echo $category_image; ?>" />
				<input id="upload_category_image" type="button" class="button-primary" value="<?php _e( 'Upload Image', 'wpmedium' ); ?>" style="width: 100px;" />
				<input id="delete_category_image" name="wpmedium_category_image_delete" type="submit" class="button-primary" value="<?php _e( 'Delete Image', 'wpmedium' ); ?>" <?php echo $style; ?> />
				<p class="description"><?php _e( 'Category Image Help', 'wpmedium' ); ?></p>
			</td>
		</tr>
<?php
}
add_action ( 'edit_category_form_fields', 'add_image_cat' );

/**
 * Save previously selected custom category images
 */
function save_image( $term_id ){
    if ( isset( $_POST['wpmedium_category_image'] ) ) {
        $category_images = get_option( 'wpmedium_category_images' );
        $category_images[$term_id] =  $_POST['wpmedium_category_image'];
        update_option( 'wpmedium_category_images', $category_images );
    }
}
add_action ( 'edited_category', 'save_image' );

/**
 * Add the theme's custom settings to <head>, overriding default stylesheets
 */
function wpmedium_wp_head() {
    global $options;
?>
    <style type="text/css">
<?php
    // background_color
    if ( $options['display']['background_color'] != '' )
        echo '    body, .site {background: '.$options['display']['background_color'].' !important;}'."\n";
    // text_color
    if ( $options['display']['text_color'] != '' )
        echo '    body, .site {color: '.$options['display']['text_color'].' !important;}'."\n";
    // header_overlay_color
    if ( $options['display']['header_overlay_color'] != '' )
        echo '    .site-header-overlay {background-color: '.$options['display']['header_overlay_color'].' !important;}'."\n";
    // header_overlay_opacity
    if ( $options['display']['header_overlay_opacity'] != '' )
        echo '    .site-header-overlay {opacity: '.($options['display']['header_overlay_opacity'] / 100).' !important;}'."\n";
    // link_color
    if ( $options['display']['link_color'] != '' )
        echo '    a {color: '.$options['display']['link_color'].' !important;}'."\n";
    // link_hover_color
    if ( $options['display']['link_hover_color'] != '' )
        echo '    a:hover {color: '.$options['display']['link_hover_color'].' !important;}'."\n";
    // header_title_color
    if ( $options['display']['header_title_color'] != '' )
        echo '    .entry-header .entry-title a {color: '.$options['display']['header_title_color'].' !important;}'."\n";
    // header_title_hover_color
    if ( $options['display']['header_title_hover_color'] != '' )
        echo '    .entry-header .entry-title a:hover {color: '.$options['display']['header_title_hover_color'].' !important;}'."\n";
?>
    </style>
<?php
    
    if ( $options['general']['toggle_ajax'] == 1 ) {
        wp_register_script( 'wpmedium-ajax-browsing', get_template_directory_uri() . '/inc/js/wpmedium-ajax-browsing.js', array( 'jquery' ) );
        wp_register_script( 'history', get_template_directory_uri() . '/inc/js/jquery.history.js', array( 'jquery' ) );
        wp_enqueue_script( 'wpmedium-ajax-browsing' );
        wp_enqueue_script( 'history' );
    }
}
add_action('wp_enqueue_scripts', 'wpmedium_wp_head');

/**
 * Add custom style to the theme options page
 */
function wpmedium_admin_scripts() {
    
    wp_register_style( 'style-admin', get_template_directory_uri() . '/css/style-admin.css', array(), '20130119', 'all' );
    wp_register_style( 'GoogleFonts', 'http://fonts.googleapis.com/css?family=PT+Sans+Narrow', array(), '', 'all' );
    
    wp_enqueue_style( 'style-admin' );
    wp_enqueue_style( 'GoogleFonts' );
    wp_enqueue_style( 'farbtastic' );
    wp_enqueue_style( 'thickbox' );
    
    wp_register_script( 'wpmedium-display-options', get_template_directory_uri() . '/inc/js/wpmedium-options.js', array( 'farbtastic', 'jquery' ) );
    wp_register_script( 'wpmedium-image-upload', get_template_directory_uri() .'/inc/js/media-upload.js', array('jquery','media-upload','thickbox') ); 
    
    wp_enqueue_script( 'farbtastic' );
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'wpmedium-display-options' );
    wp_enqueue_script( 'wpmedium-image-upload' );
    
}
add_action('admin_enqueue_scripts', 'wpmedium_admin_scripts');

// We need our default settings
require_once( trailingslashit( get_template_directory() ). 'theme-options.php' );

/**
 * Theme options page
 */
function wpmedium_theme_menu() {
    global $wpmedium_options;
    
    add_theme_page(
        // The title to be displayed in the browser window for this page.
        $wpmedium_options['theme_menu']['name'],
        // The text to be displayed for this menu item
        $wpmedium_options['theme_menu']['name'],
        // Which type of users can see this menu item
        $wpmedium_options['theme_menu']['access'],
        // The unique ID - that is, the slug - for this menu item
        $wpmedium_options['theme_menu']['slug'],
        // The name of the function to call when rendering this menu's page
        $wpmedium_options['theme_menu']['callback']
    );
}
add_action('admin_menu', 'wpmedium_theme_menu');

/**
 * Display theme options page
 */
function wpmedium_theme_display() {
    global $wpmedium_options;
?>
    <div class="wrap theme_options">
      
      <div class="theme_options_menu">
        
        <h1><?php _e( 'WPMedium Theme Options', 'wpmedium' ); ?></h1>
        <ul>
<?php foreach ( $wpmedium_options['options'] as $slug => $options ) :  ?>
          <li><a id="<?php echo $slug; ?>__" href="#" class="<?php echo ($_GET['tab'] == $slug ? 'active' : ($slug == 'general_options' ? 'active' : '')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $slug; ?>_.png" alt="" /><span><?php echo $options['title']; ?></span></a></li>
<?php endforeach; ?>
        </ul>
        <div style="clear:both"></div>
      </div>
      
      <div class="theme_options_content">
      
        <div id="settings_errors"><?php settings_errors(); ?></div>
        
<?php foreach ( $wpmedium_options['options'] as $slug => $options ) :  ?>
        <form id="<?php echo $slug; ?>" class="theme_options_panel <?php echo ($_GET['tab'] == $slug ? 'active' : ($slug == 'general_options' ? 'active' : '')); ?>" method="post" action="options.php">
          <input type="hidden" id="tab" name="tab" value="<?php echo $slug; ?>" />
          <?php settings_fields( $wpmedium_options['options'][$slug]['page'] ); ?>
          <?php do_settings_sections( $wpmedium_options['options'][$slug]['page'] ); ?>
          <?php submit_button( '', 'button-primary theme-panel-submit', 'submit', true, array( 'id' => 'submit_'.$slug ) ); ?>  
        </form>
<?php endforeach; ?>
        
        <div class="color-picker">
          <div style="position: absolute;" id="colorpicker"></div>
        </div>
      </div>
      
    </div>
<?php
}

/**
 * Settings Registration
 */
function wpmedium_theme_initialize_options() {
    global $wpmedium_options;
    
    foreach ( $wpmedium_options['options'] as $options ) {
        
        if( false == get_option( $options['page'] ) )
            add_option( $options['page'] );
        
        add_settings_section(
            $options['id'],
            $options['title'],
            $options['callback'],
            $options['page']
        );
        
        foreach ( $options['fields'] as $o ) {
            add_settings_field(  
                $o['_id'],
                $o['_title'],
                $options['callback'], 
                $options['page'],  
                $options['id'],
                $o['_options']
            );
        }
        
        register_setting(  
            $options['page'],
            $options['page']
        );  
    }
}
add_action( 'admin_init', 'wpmedium_theme_initialize_options' ); 

/**
 * Section Callback
 */
function wpmedium_options_callback( $section ) {
    global $wpmedium_options;
    switch ( $section['id'] ) { 
        // General options
        case 'toggle_ajax':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $html = '<input type="checkbox" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="1" '.checked( (int) $options[$section['id']], 1, false ).' />';
            $html .= '<label for="'.$section['id'].'"> '.$section['label'].'</label>';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        case 'site_logo':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $url = esc_url( $options[$section['id']] );
            $style = ($url == '' ? 'display:none;' : '' );
            $html = '<div id="upload_logo_preview" style="">';
            $html .= '<img style="max-width:100%;" src="'.$url.'" />';
            $html .= '</div>';
            $html .= '<input type="hidden" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="'.esc_attr($options[$section['id']]).'" />';
            $html .= '<input id="upload_logo_button" type="button" class="button-primary" value="'.__( 'Upload Logo', 'wpmedium' ).'" />';
            if ( '' != $options[$section['id']] )
                $html .= '<input id="delete_logo_button" name="'.$wpmedium_options['options']['general_options']['page'].'[delete_logo]" type="submit" class="button-primary" value="'.__( 'Delete Logo', 'wpmedium' ).'" />';
            echo $html;
            break;
        case 'general_settings_section':
            if ( $wpmedium_options['options']['general_options']['page'] != '' )
                echo '<p class="section_intro">'.$wpmedium_options['options']['general_options']['intro'].'</p>';
            break;
        // Display options
        case 'background_color':
        case 'text_color':
        case 'header_overlay_color':
        case 'link_color':
        case 'link_hover_color':
        case 'header_title_color':
        case 'header_title_hover_color':
            $options = get_option( $wpmedium_options['options']['display_options']['page'] );
            $value = ($options[$section['id']] == '' ? $section['value'] : $options[$section['id']] );
            $style = 'class="background-color:'.($options[$section['id']] == '' ? $section['value'] : $options[$section['id']] ).';"';
            $html = '<input type="text" class="colorpicker" id="'.$section['id'].'" name="'.$wpmedium_options['options']['display_options']['page'].'['.$section['id'].']" value="'.esc_attr($value).'" '.$style.' />';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        case 'header_overlay_opacity':
            $options = get_option( $wpmedium_options['options']['display_options']['page'] );
            if ( $options[$section['id']] != '' && (int) $options[$section['id']] >= 0 && (int) $options[$section['id']] <= 100 )
                $value = $options[$section['id']];
            else
                $value = $section['value'];
            
            $html .= '<div id="slider-range-max"></div><input type="text" id="'.$section['id'].'" name="'.$wpmedium_options['options']['display_options']['page'].'['.$section['id'].']" value="'.$value.'" style="border: 0;" />';
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $( '#slider-range-max' ).slider({
        range: 'max',
        min: 0,
        max: 100,
        value: <?php echo $value; ?>,
        slide: function( event, ui ) { $('#header_overlay_opacity').val( ui.value ); update_color(); },
    });
    $( '#header_overlay_opacity' ).val( $( '#slider-range-max' ).slider( 'value' ) );
});
</script>
<?php
            $html .= '<div style="clear:both"></div>';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        case 'display_settings_section':
            if ( $wpmedium_options['display_options']['page'] != '' )
                echo '<p class="section_intro">'.$wpmedium_options['options']['display_options']['intro'].'</p>';
            break;
        // Social options
        case 'facebook_profile':
        case 'twitter_profile':
        case 'google+_profile':
        case 'flickr_profile':
        case 'deviantart_profile':
        case 'blogger_profile':
        case 'tumblr_profile':
        case 'reddit_profile':
        case 'lastfm_profile':
        case 'vimeo_profile':
        case 'youtube_profile':
            $options = get_option( $wpmedium_options['options']['social_options']['page'] );
            $value = ($options[$section['id']] == '' ? $section['value'] : $options[$section['id']] );
            $style = 'style="background-image: url('.get_template_directory_uri().'/images/icons/social_media/_social_'.str_replace( '_profile', '', $section['id'] ).'.png);"';
            $html = '<input type="text" id="'.$section['id'].'" name="'.$wpmedium_options['options']['social_options']['page'].'['.$section['id'].']" value="'.esc_attr($value).'" '.$style.' />';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        
        case 'display_settings_section':
            if ( $wpmedium_options['social_options']['page'] != '' )
                echo '<p class="section_intro">'.$wpmedium_options['options']['social_options']['intro'].'</p>';
            break;
        default:
            break;
    }
} // end wpmedium_theme_social_options

function wpmedium_media_upload_setup() {
    global $pagenow;
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow )
        add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
}
add_action( 'admin_init', 'wpmedium_media_upload_setup' ); 

function replace_thickbox_text( $translated_text, $text, $domain ) { 
    if ( 'Insert into Post' == $text ) { 
        $referer = strpos( wp_get_referer(), 'wpmedium_theme_options' ); 
        if ( $referer != '' ) { 
            return __('Use As Logo', 'wpmedium' );  
        }  
    }  
    return $translated_text;  
}

?> 
