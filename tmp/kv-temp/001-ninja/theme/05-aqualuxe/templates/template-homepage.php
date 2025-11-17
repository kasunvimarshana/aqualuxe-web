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
        get_template_part( 'templates/blocks/homepage', 'hero' );
        
        // Featured Products Section
        get_template_part( 'templates/blocks/homepage', 'featured-products' );
        
        // Fish Species Showcase
        get_template_part( 'templates/blocks/homepage', 'fish-species' );
        
        // Testimonials Section
        get_template_part( 'templates/blocks/homepage', 'testimonials' );
        
        // Blog/News Section
        get_template_part( 'templates/blocks/homepage', 'blog' );
        
        // Call to Action Section
        get_template_part( 'templates/blocks/homepage', 'cta' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();