<?php
/**
 * @package WordPress
 * @subpackage WPMedium
 * @since WPMedium 1.0
 */
get_header(); ?>
    
    <div id="archive" class="hfeed site">

      <header id="masthead" class="site-header" role="banner">
        <hgroup>
          <div class="site-logo"><img class="site-avatar" src="<?php the_category_image(); ?>" alt="" /></div>
          <h1 class="site-title"><?php single_cat_title( '' ); ?></h1>
<?php if ( category_description() ) : ?>
          <h2 class="site-description"><?php echo category_description(); ?></h2>
<?php endif; ?>
        </hgroup>
      </header><!-- #masthead -->
      
      <div id="main" class="wrapper">
        
        <div id="primary" class="site-content">
          
          <nav class="archive-menu">
            <ul class="archive-controls">
              <?php the_archive_controls(); ?>
            </ul>
            <span class="archive-infos archive-post-count"><?php printf( _n( '%d Post', '%d Posts', get_category_count(), 'wpmedium' ), get_category_count() ); ?></span>
            <span class="archive-infos archive-post-backlink"><?php printf( __( 'Posted On %s %s', 'wpmedium' ), ''.home_url(), get_bloginfo( 'name' ) ); ?></span>
          </nav>
          
          <div id="content" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php if ( is_sticky() && !is_paged() ) : ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'sticky' ); ?>>
<?php else : ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php endif; ?>
              <header class="entry-header">
                <div class="entry-header-image">
                  <?php wpmedium_the_post_thumbnail(); ?>
                </div>
                <h1 class="entry-title">
                  <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpmedium' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h1>
              </header><!-- .entry-header -->
                
              <div class="entry-content">
                <?php ( is_sticky() ? the_long_excerpt( get_the_content() ) : the_excerpt() ); ?>
              </div><!-- .entry-content -->
              
              <footer class="entry-meta">
                <?php printf( '<span class="by-author">%s</span> %s <strong>%s</strong>', get_the_author(), __( 'In', 'wpmedium' ), wpmedium_get_the_category_list() ); ?><?php edit_post_link( __( 'Edit', 'wpmedium' ), '<span class="edit-link"> | ', '</span>' ); ?>
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
                <p><?php printf( __( 'Get Started %s', 'wpmedium' ), admin_url( 'post-new.php' ) ); ?></p>
              </div><!-- .entry-content -->
<?php else : ?>
              <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'Nothing Found', 'wpmedium' ); ?></h1>
              </header>
              
              <div class="entry-content">
                <p><?php _e( 'No Results Found', 'wpmedium' ); ?></p>
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
<?php endif; // end have_posts() check ?>
          </div><!-- #content -->
        </div><!-- #primary -->

<?php get_footer(); ?> 
