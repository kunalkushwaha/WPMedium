<?php
/**
 * @package WordPress
 * @subpackage WPMedium
 * @since WPMedium 1.0
 * 
 * This file contains the theme's default settings and declarations.µ
 * 
 * /!\ DON'T ALTER ANYTHING UNLESS YOU KNOW EXACTLY WHAT YOU'RE DOING!
 * 
 */

$wpmedium_options = array(
    
    'theme_menu' => array(
        'name'     => 'WPMedium options',
        'slug'     => 'wpmedium_theme_options',
        'prefix'   => 'wpmedium_',
        'access'   => 'administrator',
        'callback' => 'wpmedium_theme_display',
    ),
    
    'options' => array(
        'general_options' => array(
            'id'        => 'general_settings_section',
            'title'     => __( 'General Settings', 'wpmedium' ),
            'callback'  => 'wpmedium_options_callback',
            'page'      => 'wpmedium_theme_general_options',
            'intro'     => __( 'General Settings Help', 'wpmedium' ),
            'fields'    => array(
                array(
                    '_id'         => 'site_logo',
                    '_title'      => __( 'Site Logo', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'site_logo',
                        'help'  => sprintf( __( 'Site Logo Help', 'wpmedium' ) ),
                        'label' => '',
                        'value' => get_template_directory_uri().'/images/wp-badge.png',
                    ),
                ),
                array(
                    '_id'         => 'default_taxonomy',
                    '_title'      => __( 'Default Taxonomy', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'default_taxonomy',
                        'help'  => sprintf( __( 'Default Taxonomy Help', 'wpmedium' ) ),
                        'label' => '',
                        'value' => 'category',
                    ),
                ),
                array(
                    '_id'         => 'toggle_default_post_thumbnail',
                    '_title'      => __( 'Toggle Default Post Thumbnail', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'toggle_default_post_thumbnail',
                        'help'  => sprintf( __( 'Toggle Default Post Thumbnail Help', 'wpmedium' ) ),
                        'label' => '',
                        'value' => '1',
                    ),
                ),
                array(
                    '_id'         => 'default_post_thumbnail',
                    '_title'      => __( 'Default Post Thumbnail', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'default_post_thumbnail',
                        'help'  => sprintf( __( 'Default Post Thumbnail Help', 'wpmedium' ) ),
                        'label' => '',
                        'value' => get_template_directory_uri().'/images/wpmedium-post-thumbnail.jpg',
                    ),
                ),
                array(
                    '_id'         => 'W_image',
                    '_title'      => __( 'W Image', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'W_image',
                        'help'  => sprintf( __( 'W Image Help', 'wpmedium' ) ),
                        'label' => '',
                        'value' => get_template_directory_uri().'/images/WPMedium-logo-simple-64.png',
                    ),
                ),
                /*array(
                    '_id'         => 'toggle_ajax',
                    '_title'      => __( 'Ajax Browsing', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'toggle_ajax',
                        'help'  => __( 'Ajax Browsing Help', 'wpmedium' ),
                        'label' => __( 'Toggle Ajax Browsing', 'wpmedium' ),
                        'value' => '',
                    ),
                ),*/
            ),
        ),
        'display_options' => array(
            'id'        => 'display_settings_section',
            'title'     => __( 'Display Settings', 'wpmedium' ),
            'callback'  => 'wpmedium_options_callback',
            'page'      => 'wpmedium_theme_display_options',
            'intro'     => __( 'Display Settings Help', 'wpmedium' ),
            'fields'    => array(
                // Background color
                array(
                    '_id'         => 'background_color',
                    '_title'      => __( 'Background Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'background_color',
                        'help'  => sprintf( __( 'Background Color Help %s', 'wpmedium' ), '#f9f9f9' ),
                        'label' => '',
                        'value' => '#f9f9f9',
                    ),
                ),
                // Link color
                array(
                    '_id'         => 'text_color',
                    '_title'      => __( 'Text Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'text_color',
                        'help'  => sprintf( __( 'Text Color Help %s', 'wpmedium' ), '#1d1d1d' ),
                        'label' => '',
                        'value' => '#1d1d1d',
                    ),
                ),
                // Header overlay color
                array(
                    '_id'         => 'header_overlay_color',
                    '_title'      => __( 'Header Overlay Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'header_overlay_color',
                        'help'  => sprintf( __( 'Header Overlay Color Help %s', 'wpmedium' ), '#000000' ),
                        'label' => '',
                        'value' => '#000000',
                    ),
                ),
                // Header overlay opacity
                array(
                    '_id'         => 'header_overlay_opacity',
                    '_title'      => __( 'Header Overlay Opacity', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'header_overlay_opacity',
                        'help'  => sprintf( __( 'Header Overlay Opacity Help %s', 'wpmedium' ), '50%' ),
                        'label' => '',
                        'value' => '50',
                    ),
                ),
                // .header-sidebar color
                array(
                    '_id'         => 'header_sidebar_color',
                    '_title'      => __( 'Header Sidebar Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'header_sidebar_color',
                        'help'  => sprintf( __( 'Header Sidebar Color Help %s', 'wpmedium' ), '#ffffff' ),
                        'label' => '',
                        'value' => '#ffffff',
                    ),
                ),
                // Link color
                array(
                    '_id'         => 'link_color',
                    '_title'      => __( 'Link Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'link_color',
                        'help'  => sprintf( __( 'Link Color Help %s', 'wpmedium' ), '#444444' ),
                        'label' => '',
                        'value' => '#444444',
                    ),
                ),
                // Link:hover color
                array(
                    '_id'         => 'link_hover_color',
                    '_title'      => __( 'Link Hover Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'link_hover_color',
                        'help'  => sprintf( __( 'Link Hover Color Help %s', 'wpmedium' ), '#45568c' ),
                        'label' => '',
                        'value' => '#45568c',
                    ),
                ),
                // .header-title color
                array(
                    '_id'         => 'header_title_color',
                    '_title'      => __( 'Title Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'header_title_color',
                        'help'  => sprintf( __( 'Title Color Help %s', 'wpmedium' ), '#444444' ),
                        'label' => '',
                        'value' => '#444444',
                    ),
                ),
                // .header-title:hover color
                array(
                    '_id'         => 'header_title_hover_color',
                    '_title'      => __( 'Title Hover Color', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'header_title_hover_color',
                        'help'  => sprintf( __( 'Title Hover Color Help %s', 'wpmedium' ), '#45568c' ),
                        'label' => '',
                        'value' => '#45568c',
                    ),
                ),
            ),
        ),
        'social_options'  => array(
            'id'        => 'social_settings_section',
            'title'     => __( 'Social Settings', 'wpmedium' ),
            'callback'  => 'wpmedium_options_callback',
            'page'      => 'wpmedium_theme_social_options',
            'intro'     => __( 'Social Settings Help', 'wpmedium' ),
            'fields'    => array(
                array(
                    '_id'         => 'facebook_profile',
                    '_title'      => __( 'Facebook Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'facebook_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'twitter_profile',
                    '_title'      => __( 'Twitter Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'twitter_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'google+_profile',
                    '_title'      => __( 'Google+ Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'google+_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'flickr_profile',
                    '_title'      => __( 'Flickr Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'flickr_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'deviantart_profile',
                    '_title'      => __( 'DeviantArt Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'deviantart_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'blogger_profile',
                    '_title'      => __( 'Blogger Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'blogger_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'tumblr_profile',
                    '_title'      => __( 'Tumblr Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'tumblr_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'reddit_profile',
                    '_title'      => __( 'Reddit Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'reddit_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'lastfm_profile',
                    '_title'      => __( 'LastFm Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'lastfm_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'vimeo_profile',
                    '_title'      => __( 'Vimeo Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'vimeo_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                array(
                    '_id'         => 'youtube_profile',
                    '_title'      => __( 'Youtube Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => 'youtube_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),
                /*array(
                    '_id'         => '_profile',
                    '_title'      => __( ' Profile', 'wpmedium' ),
                    '_options'    => array(
                        'id'    => '_profile',
                        'help'  => '',
                        'label' => '',
                        'value' => '',
                    ),
                ),*/
            ),
        ),
    ),
);

?>