<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        do_action( 'aqualuxe_post_before' );

        while ( have_posts() ) :
            the_post();

            get_template_part( 'templates/content', 'single' );

            // Display post navigation
            aqualuxe_post_navigation();

            // Display related posts
            aqualuxe_related_posts();

            // Display author bio
            aqualuxe_author_bio();

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.

        do_action( 'aqualuxe_post_after' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
if ( aqualuxe_has_sidebar() ) {
    get_sidebar();
}
get_footer();