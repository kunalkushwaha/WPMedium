<?php

// Adds locales
load_theme_textdomain( 'wpmedium', get_template_directory() . '/lang' );

// Adds RSS feed links to <head> for posts and comments.
add_theme_support( 'automatic-feed-links' );

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

// We need our default settings
require_once( trailingslashit( get_template_directory() ). 'theme-options.php' );

$wpmedium = array();
$wpmedium['general'] = get_option( 'wpmedium_theme_general_options' );
$wpmedium['display'] = get_option( 'wpmedium_theme_display_options' );
$wpmedium['social']  = get_option( 'wpmedium_theme_social_options'  );

// Available taxonomy to be used
$authorized_taxonomy = array( 'category',
                              'post_tag',
);

// Add a sidebar to the header
register_sidebar( array(
    'name'          => __( 'Header Sidebar', 'wpmedium' ),
    'id'            => 'header-sidebar',
    'description'   => __( 'Header Sidebar help', 'wpmedium' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '',
    'after_title'   => '',
) );


/**
 *********************************
 *         Custom methods
 *********************************
 */

/**
 * Get shorter excerpt. Some times we just need less than 55 words
 * 
 * @since WPMedium 1.0
 *
 * @param string $excerpt post default excerpt.
 * @param int $length new maximum length.
 * @return string New shorten excerpt.
 */
function wpmedium_get_short_excerpt( $excerpt, $length = 15 ) {
    return implode( ' ', array_slice( explode( ' ', strip_shortcodes( strip_tags( $excerpt ) ) ), 0, $length ) ).' [...]';
}

/**
 * Get longer excerpt. Some times we just need more than 55 words
 * 
 * @since WPMedium 1.0
 *
 * @param string $excerpt post default excerpt.
 * @param int $length new maximum length.
 * @return string New longuer excerpt.
 */
function wpmedium_get_long_excerpt( $excerpt, $length = 125 ) {
    return implode( ' ', array_slice( explode( ' ', strip_shortcodes( strip_tags( $excerpt ) ) ), 0, $length ) ).' [...]';
}

/**
 * Display shorter excerpt.
 * 
 * @since WPMedium 1.0
 *
 * @param string $excerpt post default excerpt.
 * @param int $length new maximum length.
 */
function wpmedium_the_short_excerpt( $excerpt, $length = 15 ) {
    echo wpmedium_get_short_excerpt( $excerpt, $length );
}

/**
 * Display longer excerpt.
 * 
 * @since WPMedium 1.0
 *
 * @param string $excerpt post default excerpt.
 * @param int $length new maximum length.
 */
function wpmedium_the_long_excerpt( $excerpt, $length = 125 ) {
    echo wpmedium_get_long_excerpt( $excerpt, $length );
}

/**
 * Return the header image path. If no header image is defined,
 * use the default one.
 * 
 * @since WPMedium 1.0
 *
 * @return string Header image URL.
 */
function wpmedium_get_header_image() {
    $header_image = get_header_image();
    if ( ! empty( $header_image ) )
        return $header_image;
    else
        return get_template_directory_uri() . '/images/wpmedium-header.jpg';
}

/**
 * Display header image path
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_header_image() {
    echo wpmedium_get_header_image();
}

/**
 * Hexadecimal to RGB color conversion
 * 
 * @since WPMedium 1.0
 *
 * @param string $color HTML color code.
 * @return array Submitted color decimal values.
 */
function hex2rgb( $color ) {
    if ( $color[0] == '#' )
        $color = substr( $color, 1 );
    
    if ( strlen( $color ) == 6 )
        list( $r, $g, $b ) = array( $color[0].$color[1], $color[2].$color[3], $color[4].$color[5] );
    elseif ( strlen( $color ) == 3 )
        list( $r, $g, $b ) = array( $color[0].$color[0], $color[1].$color[1], $color[2].$color[2] );
    else
        return false;
    
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    
    return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 *********************************
 *    Post thumbnail support
 *********************************
 */

/**
 * Returns the post's thumbnail if available, default image else
 * 
 * @since WPMedium 1.0
 * 
 * @return string Post's thumbnail HTML code.
 */
function wpmedium_get_post_thumbnail() {
    global $post, $wpmedium;
    
    if ( has_post_thumbnail( $post->ID ) ) {
        $attachment =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
        
        if ( $attachment[1] > $attachment[2] ) {
            if ( $attachment[1] / ( $attachment[2] / 245 ) < 370 )
                $class = "landscape-fit";
            else
                $class = "landscape";
        }
        else if ( $attachment[1] < $attachment[2] )
            $class = "portrait";
        else
            $class = "default";
        
        $ret = '<img src="'.$attachment[0].'" alt="'.get_the_title( $post->ID ).'" class="attachment-post-thumbnail wp-post-image '.$class.'" />';
    }
    else if ( $wpmedium['general']['toggle_default_post_thumbnail'] != '1' && $wpmedium['general']['default_post_thumbnail'] != '' ) {
        $ret = '<img src="'.esc_url( $wpmedium['general']['default_post_thumbnail'] ).'" alt="'.get_the_title( $post->ID ).'" class="attachment-post-thumbnail wp-post-image default" />';
    }
    else if ( ( $wpmedium['general']['toggle_default_post_thumbnail'] == '1' || $wpmedium['general']['default_post_thumbnail'] == '' ) && file_exists( get_template_directory().'/images/wpmedium-post-thumbnail.jpg' ) ) {
        
        $ret = '<img src="'.get_template_directory_uri().'/images/wpmedium-post-thumbnail.jpg" alt="'.get_the_title( $post->ID ).'" class="attachment-post-thumbnail wp-post-image default" />';
    }
    else
        $ret = '';
    
    return $ret;
}

/**
 * Display thumbnail support
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_post_thumbnail() {
    echo wpmedium_get_post_thumbnail();
}

/**
 * If available, returns the post thumbnail's description
 * if no description is found, return empty
 * 
 * @since WPMedium 1.0
 *
 * @return string Post's thumbnail credit
 */
function wpmedium_post_thumbnail_credit() {
    global $post;
    
    if ( has_post_thumbnail( $post->ID ) )
        if ( get_post( get_post_thumbnail_id( $post->ID ) )->post_content != '' )
            $ret = sprintf( '<span class="entry-thumb-credit">%s</span>', get_post( get_post_thumbnail_id( $post->ID ) )->post_content );
        else
            $ret = '';
    
    return $ret;
}

/**
 * Display post thumbnail's description
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_post_thumbnail_credit() {
    echo wpmedium_post_thumbnail_credit();
}

/**
 *********************************
 *        Taxonomy support
 *********************************
 */

/**
 * Get the taxonomy's number of posts
 * 
 * @since WPMedium 1.0
 *
 * @return string Taxonomy count.
 */
function wpmedium_get_taxonomy_count() {
    global $authorized_taxonomy, $term;
    $r = '';
    
    if ( !in_array( $term->taxonomy, $authorized_taxonomy ) )
        return false;
    
    $taxonomy = get_term_by( 'id', $term->term_id, $term->taxonomy );
    return $taxonomy->count;
}

/**
 * Get the taxonomy list
 * 
 * @since WPMedium 1.0
 *
 * @param string $taxonomy_type what taxonomy we're handling
 * @param int $limit returned list's max number of elements to be displayed
 * @return string the taxonomy list, commat separated
 */
function wpmedium_get_the_taxonomy_list( $taxonomy_type = 'category', $limit = 3 ) {
    global $authorized_taxonomy, $post;
    $r = '';
    
    if ( !in_array( $taxonomy_type, $authorized_taxonomy ) )
        return false;
    
    $taxonomy = get_the_term_list( $post->ID, $taxonomy_type, '', ', ', '' );
    $t = explode( ', ', $taxonomy );
    
    if ( count( $t ) > $limit )
        $taxonomy = implode( ', ', array_slice( $t, 0, $limit ) ) . ', <a href="'.get_permalink().'">...</a>';
    
    return $taxonomy;
}

/**
 * Get the post's taxonomy.
 * General alternative to the_tags() and the_category() methods.
 * 
 * @since WPMedium 1.0
 *
 * @param string $before taxonomy prefix.
 * @param string $sep Optional separator.
 * @param string $after taxonomy suffix.
 * @return Taxonomy string
 */
function wpmedium_get_the_taxonomy( $before = '', $sep = ', ', $after = '' ) {
    global $authorized_taxonomy, $wpmedium, $post;
    $r = array();
    
    if ( $wpmedium['general']['default_taxonomy'] == 'category' )
        $taxonomy_type = 'post_tag';
    else if ( $wpmedium['general']['default_taxonomy'] == 'post_tag' )
        $taxonomy_type = 'category';
    
    $terms = get_the_terms( $post->ID, $taxonomy_type );
    
    foreach( $terms as $term )
        $r[] = '<a href="'.get_term_link( $term->slug, $taxonomy_type ).'">'.$term->name.'</a>';
    
    $terms = implode( $sep, $r );
    
    return $before.$terms.$after;
}

/**
 * Display the post's taxonomy.
 * 
 * @since WPMedium 1.0
 *
 * @param string $before taxonomy prefix.
 * @param string $sep Optional separator.
 * @param string $after taxonomy suffix.
 */
function wpmedium_the_taxonomy( $before = '', $sep = ', ', $after = '' ) {
    echo wpmedium_get_the_taxonomy( $before, $sep, $after );
}

/**
 * Get the archive control menu links.
 * 
 * @since WPMedium 1.0
 *
 * @return string The archive menu.
 */
function wpmedium_get_archive_controls() {
    
    $recommended = '';
    $recent      = '';
    
    if ( !isset( $_GET['order_by'] ) || ( $_GET['order_by'] == '' || $_GET['order_by'] == 'comment_count' ) ) {
        $recommended .= '<li class="archive-recommended-posts"><span class="active">'.__( 'Recommend', 'wpmedium' ).'</span></li>';
        $recent      .= '<li class="archive-recent-posts"><a href="?order_by=date&amp;order=DESC" class="">'.__( 'Recent', 'wpmedium' ).'</a></li>';
    }
    else if ( $_GET['order_by'] == 'date' ) {
        $recommended .= '<li class="archive-recommended-posts"><a href="?order_by=comment_count&amp;order=DESC" class="">'.__( 'Recommend', 'wpmedium' ).'</a></li>';
        $recommended .= '<li class="archive-recent-posts"><span class="active">'.__( 'Recent', 'wpmedium' ).'</span></li>';
    }
    
    return $recommended."\n".$recent;
}

/**
 * Get the index control menu links.
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_archive_controls() {
    echo wpmedium_get_archive_controls();
}

/**
 * Display the archive control menu links.
 * 
 * @since WPMedium 1.0
 *
 * @return string The index menu.
 */
function wpmedium_get_index_controls() {
    
    $newest = '';
    $oldest = '';
    
    if ( !isset( $_GET['order'] ) || ( $_GET['order'] == '' || $_GET['order'] == 'DESC' ) ) {
        $newest .= '<li class="site-categories-newest"><a class="active" href="?order=DESC">'.__( 'Newest', 'wpmedium' ).'</a></li>';
        $oldest .= '<li class="site-categories-oldest"><a href="?order=ASC">'.__( 'Oldest', 'wpmedium' ).'</a></li>';
    }
    else if ( $_GET['order'] == 'ASC' ) {
        $newest .= '<li class="site-categories-newest"><a href="?order=DESC">'.__( 'Newest', 'wpmedium' ).'</a></li>';
        $oldest .= '<li class="site-categories-oldest"><a class="active" href="?order=ASC">'.__( 'Oldest', 'wpmedium' ).'</a></li>';
    }
    
    return $newest."\n".$oldest;
}

/**
 * Display the index control menu links.
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_index_controls() {
    echo wpmedium_get_index_controls();
}

/**
 * Get the site logo
 * If no logo is set in the theme's options, use default WP-Badge as logo 
 * 
 * @since WPMedium 1.0
 *
 * @return string The site logo URL.
 */
function wpmedium_get_site_logo() {
    global $wpmedium;
    if ( isset( $wpmedium['general']['site_logo'] ) && $wpmedium['general']['site_logo'] != '' )
        return '<img class="site-avatar" src="'.esc_url( $wpmedium['general']['site_logo'] ).'" alt="" />';
    else if ( file_exists( get_template_directory().'/images/wp-badge.png' ) )
        return '<img class="site-avatar" src="'.get_template_directory_uri().'/images/wp-badge.png" alt="" style="height:auto;margin:-22px 0 0 -42px;" />';
}

/**
 * Display the site logo
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_site_logo() {
    echo wpmedium_get_site_logo();
}

/**
 * Get the WPMedium "W" link image
 * 
 * @since WPMedium 1.1
 */
function wpmedium_get_W() {
    global $wpmedium;
    
    if ( isset( $wpmedium['general']['W_image'] ) && $wpmedium['general']['W_image'] != '' )
        $ret = '<img src="'.esc_url( $wpmedium['general']['W_image'] ).'" alt="W" />';
    else if ( file_exists( get_template_directory().'/images/WPMedium-logo-simple-32.png' ) )
        $ret = '<img src="'.get_template_directory_uri().'/images/WPMedium-logo-simple-32.png" alt="W" />';
    else
        $ret = '';
    
    return $ret;
}

/**
 * Display the WPMedium "W" link image
 * 
 * @since WPMedium 1.1
 */
function wpmedium_the_W() {
    echo wpmedium_get_W();
}

/**
 * 
 * @since WPMedium 1.0
 *
 * @return string .
 */
function wpmedium_get_social_links() {
    global $wpmedium;
    
    $ret = '';
    
    if ( count( $wpmedium['social'] ) > 0 ) {
        foreach ( $wpmedium['social'] as $network => $url ) {
            if ( $url != '' )
                $ret .= '<a href="'.esc_url( $url ).'"><i class="'.str_replace( '_profile', '', $network ).'" style="background-image:url('.get_template_directory_uri().'/images/icons/picon_social/'.str_replace( '_profile', '', $network ).'.png)"></i></a> ';
        }
    }
    
    return $ret;
}

/**
 * 
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_social_links() {
    echo wpmedium_get_social_links();
}


/**
 * Return the custom taxonomy image
 * If no image is properly defined, fallback to the latest taxonomy's post
 * thumbnail. If the taxonomy is empty, use the theme's logo
 * 
 * @since WPMedium 1.0
 *
 * @return string The taxonomy image.
 */
function wpmedium_get_the_taxonomy_image() {
    global $authorized_taxonomy, $wpmedium, $term;
    
    $ret = '';
    
    if ( !in_array( $term->taxonomy, $authorized_taxonomy ) )
        return false;
    
    if ( $term ) {
        
        $taxonomy_images = get_option( 'wpmedium_taxonomy_images' );
        $taxonomy_image  = '';
        
        if ( is_array( $taxonomy_images ) && array_key_exists( $term->term_id, $taxonomy_images ) && $taxonomy_images[$term->term_id] != '' ) {
            $ret = $taxonomy_images[$term->term_id];
        }
        else {
            $query = new WP_Query( array(
                'post_type' => 'post',
                'tax_query' => array(
                   array(
                      'taxonomy' => $term->taxonomy,
                      'field' => 'id',
                      'terms' => $term->term_id,
                   ),
                ),
            ) );
            $post_ = $query->posts[0];
            
            if ( has_post_thumbnail( $post_->ID ) ) {
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_->ID ), 'medium' );
                $ret = $thumbnail[0];
            }
        }
    }
    else {
        $ret = $wpmedium['general']['site_logo'];
    }
    return $ret;
}

/**
 * Display the custom taxonomy image
 * 
 * @since WPMedium 1.0
 */
function wpmedium_the_taxonomy_image() {
    echo wpmedium_get_the_taxonomy_image();
}

/**
 * Add custom images to display along with category description and title
 * selection/upload using WP media-upload
 * 
 * @since WPMedium 1.0
 *
 * @param string $taxonomy 
 */
function wpmedium_add_taxonomy_image( $taxonomy ) {
    
    $taxonomy_images = get_option( 'wpmedium_taxonomy_images' );
    $taxonomy_image = '';
    
    if ( is_array( $taxonomy_images ) && array_key_exists( $taxonomy->term_id, $taxonomy_images ) )
        $taxonomy_image = $taxonomy_images[$taxonomy->term_id] ;
    
    if ( '' != $taxonomy_image )
        $style = 'style="width: 100px;"';
    else
        $style = 'style="display:none;width: 100px;"';
?>
	<table class="form-table">
		<tr class="form-field form-required">
			<th scope="row" valign="top">
				<label for="auteur_revue_image"><?php _e( 'Taxonomy Image', 'wpmedium' ); ?></label>
			</th>
			<td>
				<div id="upload_taxonomy_image_preview" style="">
<?php ?>
<?php if ( '' != $taxonomy_image ) { ?>
					<img style="max-width:100%;" src="<?php echo $taxonomy_image; ?>" />
<?php } ?>
				</div>
				
				<input type="hidden" id="wpmedium_taxonomy_image" name="wpmedium_taxonomy_image" value="<?php echo $taxonomy_image; ?>" />
				<input id="upload_taxonomy_image" type="button" class="button-primary" value="<?php _e( 'Upload Image', 'wpmedium' ); ?>" style="width: 100px;" />
				<input id="delete_taxonomy_image" name="wpmedium_taxonomy_image_delete" type="submit" class="button-primary" value="<?php _e( 'Delete Image', 'wpmedium' ); ?>" <?php echo $style; ?> />
				<p class="description"><?php _e( 'Taxonomy Image Help', 'wpmedium' ); ?></p>
			</td>
		</tr>
<?php
}
add_action ( 'edit_category_form_fields', 'wpmedium_add_taxonomy_image' );
add_action ( 'edit_tag_form_fields', 'wpmedium_add_taxonomy_image' );

/**
 * Save previously selected custom category images
 * 
 * @since WPMedium 1.0
 *
 * @param int $term_id taxonomy ID.
 */
function save_image( $term_id ){
    if ( isset( $_POST['wpmedium_taxonomy_image'] ) ) {
        $taxonomy_images = get_option( 'wpmedium_taxonomy_images' );
        $taxonomy_images[$term_id] =  $_POST['wpmedium_taxonomy_image'];
        update_option( 'wpmedium_taxonomy_images', $taxonomy_images );
    }
}
add_action ( 'edited_term', 'save_image' );

/**
 * Add the theme's custom settings to <head>, overriding default stylesheets 
 * and loading more scripts
 * 
 * @since WPMedium 1.0
 */
function wpmedium_wp_head() {
    wpmedium_wp_head_styles();
    wpmedium_wp_head_scripts();
}
add_action('wp_enqueue_scripts', 'wpmedium_wp_head');

/**
 * Add custom styles the theme based on custom options
 * 
 * @since WPMedium 1.1
 */
function wpmedium_wp_head_styles() {
    global $wpmedium;
    
    echo '    <style type="text/css">'."\n";
    
    // background_color
    if ( $wpmedium['display']['background_color'] != '' )
        echo '    body, .site {background: '.$wpmedium['display']['background_color'].' !important;}'."\n";
    // W_background_color
    if ( $wpmedium['display']['W_background_color'] != '' )
        echo '    #WP {background: '.$wpmedium['display']['W_background_color'].' !important;}'."\n";
    // text_color
    if ( $wpmedium['display']['text_color'] != '' )
        echo '    body, .site {color: '.$wpmedium['display']['text_color'].' !important;}'."\n";
    // header_overlay_color
    if ( $wpmedium['display']['header_overlay_color'] != '' )
        echo '    .site-header-overlay {background-color: '.$wpmedium['display']['header_overlay_color'].' !important;}'."\n";
    // header_overlay_opacity
    if ( $wpmedium['display']['header_overlay_opacity'] != '' )
        echo '    .site-header-overlay {opacity: '.($wpmedium['display']['header_overlay_opacity'] / 100).' !important;}'."\n";
    // header_sidebar_color
    if ( $wpmedium['display']['header_sidebar_color'] != '' )
        echo '    .header-sidebar {color: '.$wpmedium['display']['header_sidebar_color'].' !important;}'."\n";
    // link_color
    if ( $wpmedium['display']['link_color'] != '' )
        echo '    a {color: '.$wpmedium['display']['link_color'].' !important;}'."\n";
    // link_hover_color
    if ( $wpmedium['display']['link_hover_color'] != '' )
        echo '    a:hover {color: '.$wpmedium['display']['link_hover_color'].' !important;}'."\n";
    // header_title_color
    if ( $wpmedium['display']['header_title_color'] != '' )
        echo '    .entry-header .entry-title a {color: '.$wpmedium['display']['header_title_color'].' !important;}'."\n";
    // header_title_hover_color
    if ( $wpmedium['display']['header_title_hover_color'] != '' )
        echo '    .entry-header .entry-title a:hover {color: '.$wpmedium['display']['header_title_hover_color'].' !important;}'."\n";
    
    echo '    </style>'."\n";
}

/**
 * Add custom scripts the theme
 * 
 * @since WPMedium 1.1
 */
function wpmedium_wp_head_scripts() {
    global $wpmedium;
    
    /*if ( $wpmedium['general']['toggle_ajax'] == '1' ) {
        wp_register_script( 'wpmedium-ajax-browsing', get_template_directory_uri() . '/inc/js/wpmedium-ajax-browsing.js', array( 'jquery' ) );
        wp_register_script( 'history', get_template_directory_uri() . '/inc/js/jquery.history.js', array( 'jquery' ) );
        wp_enqueue_script( 'wpmedium-ajax-browsing' );
        wp_enqueue_script( 'history' );
    }*/
    
    wp_enqueue_script( 'jquery' );
    
    if ( !$wpmedium['general']['toggle_default_post_thumbnail'] ) {
        wp_register_script( 'masonry', get_template_directory_uri() . '/inc/js/jquery.masonry.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'masonry' );
        wp_register_script( 'wpmedium-masonry', get_template_directory_uri() . '/inc/js/jquery.wpmedium.masonry.js', array( 'jquery', 'masonry' ) );
        wp_enqueue_script( 'wpmedium-masonry' );
    }
    
    wp_register_script( 'wpmedium', get_template_directory_uri() . '/inc/js/jquery.wpmedium.js', array( 'jquery' ) );
    wp_enqueue_script( 'wpmedium' );
}



/**
 * Add custom style to the theme options page
 * 
 * @since WPMedium 1.0
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

/**
 * Theme options page
 * 
 * @since WPMedium 1.0
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
 * 
 * @since WPMedium 1.0
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
 * 
 * @since WPMedium 1.0
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
 * 
 * @since WPMedium 1.0
 *
 * @param string $section Option section to handle
 */
function wpmedium_options_callback( $section ) {
    global $wpmedium_options;
    switch ( $section['id'] ) { 
        // General options
        case 'toggle_ajax':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $html = '<input type="checkbox" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="1" '.checked( $options[$section['id']], '1', false ).' />';
            $html .= '<label for="'.$section['id'].'"> '.$section['label'].'</label>';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        case 'site_logo':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $url = esc_url( $options[$section['id']] );
            $style = ($url == '' ? 'display:none;' : '' );
            $html = '<div id="upload_logo_preview" style="">';
            $html .= '<img src="'.$url.'" alt="" />';
            $html .= '</div>';
            $html .= '<input type="hidden" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="'.esc_attr($options[$section['id']]).'" />';
            $html .= '<input id="upload_logo_button" type="button" class="button-primary" value="'.__( 'Upload Logo', 'wpmedium' ).'" />';
            if ( '' != $options[$section['id']] )
                $html .= '<input id="delete_logo_button" name="'.$wpmedium_options['options']['general_options']['page'].'[delete_logo]" type="submit" class="button-primary" value="'.__( 'Delete Logo', 'wpmedium' ).'" />';
            echo $html;
            break;
        case 'default_taxonomy':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $html  = '<select id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']">';
            $html .= '<option value="category"'.selected( $options[$section['id']], 'category', false).'>'.__( 'Category', 'wpmedium' ).'</option>';
            $html .= '<option value="post_tag"'.selected( $options[$section['id']], 'post_tag', false).'>'.__( 'Post Tag', 'wpmedium' ).'</option>';
            $html .= '</select>';
            $html .= '<label for="'.$section['id'].'"> '.$section['label'].'</label>';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        case 'toggle_default_post_thumbnail':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $html = '<input type="checkbox" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="1" '.checked( $options[$section['id']], '1', false ).' />';
            $html .= '<label for="'.$section['id'].'"> '.$section['label'].'</label>';
            if ( $section['help'] != '' ) $html .= '<span class="help">'.$section['help'].'</span>';
            echo $html;
            break;
        case 'default_post_thumbnail':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $url = esc_url( $options[$section['id']] );
            $style = ($url == '' ? 'display:none;' : '' );
            $html = '<div id="upload_post_thumbnail_preview" style="">';
            $html .= '<img src="'.$url.'" alt="" />';
            $html .= '</div>';
            $html .= '<input type="hidden" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="'.esc_attr($options[$section['id']]).'" />';
            $html .= '<input id="upload_post_thumbnail_button" type="button" class="button-primary" value="'.__( 'Upload Post Thumbnail', 'wpmedium' ).'" />';
            if ( '' != $options[$section['id']] )
                $html .= '<input id="delete_post_thumbnail_button" name="'.$wpmedium_options['options']['general_options']['page'].'[delete_post_thumbnail]" type="submit" class="button-primary" value="'.__( 'Delete Post Thumbnail', 'wpmedium' ).'" />';
            echo $html;
            break;
        case 'W_image':
            $options = get_option( $wpmedium_options['options']['general_options']['page'] );
            $url = esc_url( $options[$section['id']] );
            $style = ($url == '' ? 'display:none;' : '' );
            $html = '<div id="upload_W_image_preview" style="">';
            $html .= '<img src="'.$url.'" alt="" />';
            $html .= '</div>';
            $html .= '<input type="hidden" id="'.$section['id'].'" name="'.$wpmedium_options['options']['general_options']['page'].'['.$section['id'].']" value="'.esc_attr($options[$section['id']]).'" />';
            $html .= '<input id="upload_W_image_button" type="button" class="button-primary" value="'.__( 'Upload W Image', 'wpmedium' ).'" />';
            if ( '' != $options[$section['id']] )
                $html .= '<input id="delete_W_image_button" name="'.$wpmedium_options['options']['general_options']['page'].'[delete_W_image]" type="submit" class="button-primary" value="'.__( 'Delete W Image', 'wpmedium' ).'" />';
            echo $html;
            break;
        case 'general_settings_section':
            if ( $wpmedium_options['options']['general_options']['page'] != '' )
                echo '<p class="section_intro">'.$wpmedium_options['options']['general_options']['intro'].'</p>';
            break;
        // Display options
        case 'background_color':
        case 'W_background_color':
        case 'text_color':
        case 'header_overlay_color':
        case 'header_sidebar_color':
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

/**
 * Setup WP Media Upload tool
 * 
 * @since WPMedium 1.0
 */
function wpmedium_media_upload_setup() {
    global $pagenow;
    if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow )
        add_filter( 'gettext', 'replace_thickbox_text', 1, 3 );
}
add_action( 'admin_init', 'wpmedium_media_upload_setup' ); 

/**
 * Customize WP Media upload text
 * Not really sure I correctly understand this one's params,
 * but still, it works…
 * 
 * @since WPMedium 1.0
 *
 * @param string $translated_text modified text?
 * @param string $text current media tool text?
 * @param string $domain domain?
 */
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
