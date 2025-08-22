<?php
/**
 * Template Name: Homepage
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part( 'templates/template-parts/homepage/hero' );
    
    // Featured Products Section
    if ( class_exists( 'WooCommerce' ) ) {
        get_template_part( 'templates/template-parts/homepage/featured-products' );
    }
    
    // Categories Section
    if ( class_exists( 'WooCommerce' ) ) {
        get_template_part( 'templates/template-parts/homepage/product-categories' );
    }
    
    // About Section
    get_template_part( 'templates/template-parts/homepage/about' );
    
    // Features Section
    get_template_part( 'templates/template-parts/homepage/features' );
    
    // Testimonials Section
    get_template_part( 'templates/template-parts/homepage/testimonials' );
    
    // Latest Posts Section
    get_template_part( 'templates/template-parts/homepage/latest-posts' );
    
    // Newsletter Section
    get_template_part( 'templates/template-parts/homepage/newsletter' );
    
    // CTA Section
    get_template_part( 'templates/template-parts/homepage/cta' );
    ?>

</main><!-- #primary -->

<?php
get_footer();