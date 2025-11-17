<?php
/**
 * The front page template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part('template-parts/front-page/hero');

    // Featured Products Section
    if ( class_exists( 'WooCommerce' ) ) {
        get_template_part('template-parts/front-page/featured-products');
    }

    // Testimonials Section
    get_template_part('template-parts/front-page/testimonials');

    // Blog Highlights Section
    get_template_part('template-parts/front-page/blog-highlights');

    // Call to Action Section
    get_template_part('template-parts/front-page/cta');
    ?>

</main><!-- #main -->

<?php
get_footer();
