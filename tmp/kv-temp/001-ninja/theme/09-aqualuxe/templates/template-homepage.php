<?php
/**
 * Template Name: Homepage
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part('templates/parts/homepage', 'hero');
    
    // Featured Products Section
    if (class_exists('WooCommerce')) {
        get_template_part('templates/parts/homepage', 'featured-products');
    }
    
    // Services Section
    get_template_part('templates/parts/homepage', 'services');
    
    // About Section
    get_template_part('templates/parts/homepage', 'about');
    
    // Testimonials Section
    get_template_part('templates/parts/homepage', 'testimonials');
    
    // Latest Events Section
    get_template_part('templates/parts/homepage', 'events');
    
    // Blog Section
    get_template_part('templates/parts/homepage', 'blog');
    
    // Newsletter Section
    get_template_part('templates/parts/homepage', 'newsletter');
    ?>

</main><!-- #main -->

<?php
get_footer();