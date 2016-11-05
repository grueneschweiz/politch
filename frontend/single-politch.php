<?php
/**
 * The template for displaying all single persons.
 *
 * @package Politch
 * @since 1.4.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
               
               <?php the_title( '<h1 class="entry-title entry-title-short">', '</h1>' ); ?>
               
               <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="entry-content politch-single-person">
                         
                         <?php 
                              /**
                               * Echo the person by shortcode. Don't display
                               * the election info.
                               */
                              $post = get_post();
                              echo do_shortcode( '[politch type="person" id="' . $post->ID . '"]' );
                         ?>
                         
                    </div><!-- .entry-content -->

               </article><!-- #post-## -->

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>