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
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-12">

    <?php
    // Hook for WooCommerce fallback content
    do_action('aqualuxe_before_page_content');
    
    // Only show regular page content if not displaying a fallback
    if (!get_query_var('load_woocommerce_fallback', false)) {
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content/content', 'page');

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
    }
    ?>

</main><!-- #main -->

<?php
get_footer();