<?php
/**
 * Template Name: Homepage
 *
 * The template for displaying the homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    get_template_part('template-parts/homepage/hero');
    
    // Featured Products Section
    if (class_exists('WooCommerce') && get_theme_mod('aqualuxe_homepage_show_featured_products', true)) {
        get_template_part('template-parts/homepage/featured-products');
    }
    
    // About Section
    if (get_theme_mod('aqualuxe_homepage_show_about', true)) {
        get_template_part('template-parts/homepage/about');
    }
    
    // Services Section
    if (get_theme_mod('aqualuxe_homepage_show_services', true)) {
        get_template_part('template-parts/homepage/services');
    }
    
    // Testimonials Section
    if (get_theme_mod('aqualuxe_homepage_show_testimonials', true)) {
        get_template_part('template-parts/homepage/testimonials');
    }
    
    // Latest Products Section
    if (class_exists('WooCommerce') && get_theme_mod('aqualuxe_homepage_show_latest_products', true)) {
        get_template_part('template-parts/homepage/latest-products');
    }
    
    // Blog Section
    if (get_theme_mod('aqualuxe_homepage_show_blog', true)) {
        get_template_part('template-parts/homepage/blog');
    }
    
    // Call to Action Section
    if (get_theme_mod('aqualuxe_homepage_show_cta', true)) {
        get_template_part('template-parts/homepage/cta');
    }
    
    // Instagram Feed Section
    if (get_theme_mod('aqualuxe_homepage_show_instagram', false)) {
        get_template_part('template-parts/homepage/instagram');
    }
    ?>
</main><!-- #main -->

<?php
get_footer();