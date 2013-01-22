<?php
/**
 * @package WordPress
 * @subpackage WPMedium
 * @since WPMedium 1.0
 */
?><!DOCTYPE html>
  
<html <?php language_attributes(); ?>>

  <head>
    
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    
    <title><?php is_home() ? printf( '%s | %s', get_bloginfo( 'name' ), get_bloginfo( 'description' ) ) : printf( '%s | %s', wp_title(''), get_bloginfo( 'name' ) ); ?></title>
    
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow" type="text/css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Libre+Baskerville' type='text/css'>
    
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/basic.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />
    
    <?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
    
    <?php wp_head(); ?>
    
  </head>
  
  <body <?php body_class(); ?>>
    
    <a id="WP" href="<?php echo home_url(); ?>">W</a>