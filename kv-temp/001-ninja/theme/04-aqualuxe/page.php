<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
         * Functions hooked into aqualuxe_page action
         *
         * @hooked aqualuxe_page_header - 10
         * @hooked aqualuxe_page_content - 20
         * @hooked aqualuxe_page_footer - 30
         * @hooked aqualuxe_page_comments - 40
         */
        do_action('aqualuxe_page');

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