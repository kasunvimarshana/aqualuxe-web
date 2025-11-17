<?php
/**
 * The template for displaying all single posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main">
    <?php aqualuxe_breadcrumbs(); ?>
    
    <div class="container mx-auto px-4 py-8">
        
        <?php
        while ( have_posts() ) :
            the_post();
            
            get_template_part( 'template-parts/content', get_post_type() );
            
            // Post navigation
            aqualuxe_post_navigation();

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
        
    </div>
</main><!-- #primary -->

<?php
get_sidebar();
get_footer();