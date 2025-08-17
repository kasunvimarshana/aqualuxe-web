<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main front-page">
    <?php
    // Hero Section
    get_template_part( 'template-parts/home/hero' );
    
    // Featured Products Section (if WooCommerce is active)
    if ( aqualuxe_is_woocommerce_active() ) {
        get_template_part( 'template-parts/home/featured-products' );
    } else {
        get_template_part( 'template-parts/home/featured-content' );
    }
    
    // Categories Section (if WooCommerce is active)
    if ( aqualuxe_is_woocommerce_active() ) {
        get_template_part( 'template-parts/home/product-categories' );
    }
    
    // About Section
    get_template_part( 'template-parts/home/about' );
    
    // Services Section
    get_template_part( 'template-parts/home/services' );
    
    // Testimonials Section
    get_template_part( 'template-parts/home/testimonials' );
    
    // Latest Posts Section
    get_template_part( 'template-parts/home/latest-posts' );
    
    // Newsletter Section
    get_template_part( 'template-parts/home/newsletter' );
    
    // CTA Section
    get_template_part( 'template-parts/home/cta' );
    ?>
</main><!-- #main -->

<?php
get_footer();