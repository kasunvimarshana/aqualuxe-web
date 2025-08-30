<?php
/**
 * Template Name: Homepage
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        
        <?php
        // Hero Section
        get_template_part('templates/homepage/hero');
        
        // Featured Products Section
        get_template_part('templates/homepage/featured-products');
        
        // Categories Section
        get_template_part('templates/homepage/categories');
        
        // About Section
        get_template_part('templates/homepage/about');
        
        // Services Section
        get_template_part('templates/homepage/services');
        
        // Testimonials Section
        get_template_part('templates/homepage/testimonials');
        
        // Latest Blog Posts Section
        get_template_part('templates/homepage/latest-posts');
        
        // Newsletter Section
        get_template_part('templates/homepage/newsletter');
        
        // Partners Section
        get_template_part('templates/homepage/partners');
        ?>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();