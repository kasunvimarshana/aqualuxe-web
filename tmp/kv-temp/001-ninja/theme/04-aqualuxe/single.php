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

<main id="primary" class="site-main col-md-9">

    <?php
    while (have_posts()) :
        the_post();

        /**
         * Functions hooked into aqualuxe_single_post action
         *
         * @hooked aqualuxe_post_header - 10
         * @hooked aqualuxe_post_meta - 20
         * @hooked aqualuxe_post_content - 30
         * @hooked aqualuxe_post_footer - 40
         * @hooked aqualuxe_post_navigation - 50
         * @hooked aqualuxe_post_author_bio - 60
         * @hooked aqualuxe_post_related - 70
         * @hooked aqualuxe_post_comments - 80
         */
        do_action('aqualuxe_single_post');

    endwhile; // End of the loop.
    ?>

</main><!-- #primary -->

<?php
/**
 * Functions hooked into aqualuxe_sidebar action
 *
 * @hooked aqualuxe_get_sidebar - 10
 */
do_action('aqualuxe_sidebar');

get_footer();