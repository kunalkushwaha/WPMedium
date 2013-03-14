<?php
/**
 * @package WordPress
 * @subpackage WPMedium
 * @since WPMedium 1.0
 */
get_header(); ?>
    
    <div id="home" class="hfeed site">

     <header id="masthead" class="site-header" role="banner" style="background-image:url(<?php wpmedium_the_header_image(); ?>);">
        
        <div class="site-header-overlay"></div>
        
        <hgroup>
          <div class="site-logo"><?php wpmedium_the_site_logo(); ?></div>
          <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
          <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
          <p><?php wpmedium_the_social_links(); ?></p>
        </hgroup>
        
<?php if ( is_active_sidebar( 'header-sidebar' ) ) : ?>
        <div id="header-sidebar" class="widget-area header-sidebar" role="complementary">
          <?php dynamic_sidebar( 'header-sidebar' ); ?>
        </div><!-- #secondary -->
<?php endif; ?>
        
      </header><!-- #masthead -->
      
      <div id="main" class="wrapper">
        
        <div id="primary" class="site-content">
          
          <nav class="site-menu">
            <ul class="site-menu-links">
<?php if ( has_nav_menu( 'primary' ) ) : ?>
              <?php wp_nav_menu( array( 'menu' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) ); ?>
<?php else : ?>
              <?php wp_list_categories( array( 'title_li' => '', 'hierarchical' => 0 ) ); ?>
<?php endif; ?>
              <li id="menu-item-search" class="menu-item menu-item-search"><?php get_search_form(); ?></li>
            </ul>

          </nav>
          
          <nav class="site-categories">
            <ul class="site-categories-order">
              <?php wpmedium_the_index_controls(); ?>
              <li class="site-categories-count"><a><?php printf( _n( '%d Post', '%d Posts', $wp_query->found_posts, 'wpmedium' ), $wp_query->found_posts ); ?></a></li>
            </ul>
          </nav>
          
          <div id="content" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $class = ''; ?>
<?php if ( is_sticky() && !is_paged() ) $class .= ''; ?>
<?php if ( !has_post_thumbnail() && !$wpmedium['general']['toggle_default_post_thumbnail'] ) $class .= ' no-thumbnail'; ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
              <header class="entry-header">
                <div class="entry-header-image">
                  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php wpmedium_the_post_thumbnail(); ?></a>
                </div>
                <h1 class="entry-title">
                  <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpmedium' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h1>
              </header><!-- .entry-header -->
                
              <div class="entry-content">
                <?php the_excerpt(); ?>
              </div><!-- .entry-content -->
              
              <footer class="entry-meta">
                <div class="wrap">
                  <?php printf( '<span class="by-author">%s</span> %s %s', get_the_author(), __( 'In', 'wpmedium' ), wpmedium_get_the_taxonomy_list( $wpmedium['general']['default_taxonomy'] ) ); ?><?php edit_post_link( __( 'Edit', 'wpmedium' ), '<span class="edit-link"> | ', '</span>' ); ?>
                </div>
              </footer><!-- .entry-meta -->
            </article>
<?php endwhile; ?>
            <div style="clear:both"></div>
            <div class="pagination">
              <?php posts_nav_link( ' &#183; ', sprintf( '<span class="pagination-left">%s</span>', __( 'Prev page', 'wpmedium' ) ), sprintf( '<span class="pagination-right">%s</span>', __( 'Next page', 'wpmedium' ) ) ); ?> 
            </div>
<?php else : ?>
            <article id="post-0" class="post no-results not-found">
<?php if ( current_user_can( 'edit_posts' ) ) : ?>
              <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'No posts', 'wpmedium' ); ?></h1>
              </header>
              
              <div class="entry-content">
                <p><?php printf( __( 'Get started %s', 'wpmedium' ), admin_url( 'post-new.php' ) ); ?></p>
              </div><!-- .entry-content -->
<?php else : ?>
              <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'Nothing Found', 'wpmedium' ); ?></h1>
              </header>
              
              <div class="entry-content">
                <p><?php _e( 'No results found', 'wpmedium' ); ?></p>
                <?php get_search_form(); ?>
              </div><!-- .entry-content -->
<?php endif; ?>
            </article><!-- #post-0 -->
            <article id="post-0-1" class="post empty">
              <h1 class="entry-title"><?php _e( 'Coming soon', 'wpmedium' ); ?></h1>
            </article><!-- #post-0-1 -->
            <article id="post-0-2" class="post empty">
              <h1 class="entry-title"><?php _e( 'Coming soon', 'wpmedium' ); ?></h1>
            </article><!-- #post-0-2 -->
            </article><!-- #post-0 -->
<?php endif; // end have_posts() check ?>
          </div><!-- #content -->
        </div><!-- #primary -->

<?php get_footer(); ?> 