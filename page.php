<?php
/**
 * @package WordPress
 * @subpackage WPMedium
 * @since WPMedium 1.0
 */
get_header(); ?>
    
    <div id="page" class="hfeed site">

      <div id="main" class="wrapper">

        <div id="primary" class="site-content">
          
          <div id="content" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
              <header class="entry-header">
                <div class="entry-thumb">
                  <?php wpmedium_the_post_thumbnail(); ?>
                  <?php wpmedium_the_post_thumbnail_credit(); ?>
                </div>
              <div class="entry-meta">
                <?php printf( '<span class="by-author">%s</span> %s <span class="in-category">%s</span>', get_the_author_link(), __( 'In', 'wpmedium' ), wpmedium_get_the_taxonomy_list( $wpmedium['general']['default_taxonomy'] ) ); ?> | <?php edit_post_link( __( 'Edit', 'wpmedium' ), '<span class="edit-link">', '</span>' ); ?>
              </div><!-- .entry-meta -->
              <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'wpmedium' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
              </h1>
            </header><!-- .entry-header -->
            
            <div class="entry-content">
              <?php the_content(); ?>
              <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'wpmedium' ), 'after' => '</div>' ) ); ?>
            </div><!-- .entry-content -->
            
<?php if ( get_the_tags() ) : ?>
            <div class="entry-tags">
              <?php the_tags( __( 'This post has been tagged as', 'wpmedium' ), ', ', '' ); ?>
            </div><!-- .entry-tags -->
<?php endif; ?>
            
            <div class="entry-author">
              <div class="author-avatar">
                <?php echo get_avatar( get_the_author_meta( 'user_email' ) ); ?>
              </div><!-- .author-avatar -->
              <div class="author-description">
                <h6><?php the_author_link(); ?></h6>
                <p><?php the_author_meta( 'description' ); ?></p>
                <!--<div class="author-link">
                  <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                    <?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'wpmedium' ), get_the_author() ); ?>
                  </a>
                </div>--><!-- .author-link	-->
              </div><!-- .author-description -->
              <?php printf( '<div class="entry-date"><h6>%s</h6> <span class="date">%s</span></div>', __( 'Published', 'wpmedium' ), get_the_date( 'F j, Y' ) ); ?>
              <div style="clear:both"></div>
            </div><!-- .entry-author -->
            
            <div class="entry-comment">
              <?php comments_template(); ?>
            </div>
            
          </article>
<?php endwhile; else : ?>
          <article id="post-0" class="post no-results not-found">
<?php if ( current_user_can( 'edit_posts' ) ) : ?>
            <header class="entry-header">
              <h1 class="entry-title"><?php _e( 'No posts', 'wpmedium' ); ?></h1>
            </header>
            
            <div class="entry-content">
              <p><?php printf( __( 'Get Started', 'wpmedium' ), admin_url( 'post-new.php' ) ); ?></p>
            </div><!-- .entry-content -->
            
<?php else : ?>
            <header class="entry-header">
              <h1 class="entry-title"><?php _e( 'Nothing Found', 'wpmedium' ); ?></h1>
            </header>
            
            <div class="entry-content">
              <p><?php _e( 'No Results Found', 'wpmedium' ); ?></p>
              <?php get_search_form(); ?>
            </div><!-- .entry-content -->
<?php endif; // end current_user_can() check ?>
          </article><!-- #post-0 -->
<?php endif; // end have_posts() check ?>
          </div><!-- #content -->
        </div><!-- #primary -->

<?php get_footer(); ?> 