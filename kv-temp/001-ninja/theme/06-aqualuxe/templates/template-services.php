<?php
/**
 * Template Name: Services Page
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
        get_template_part( 'templates/blocks/services', 'hero' );
        
        // Services Overview
        get_template_part( 'templates/blocks/services', 'overview' );
        
        // Individual Services
        get_template_part( 'templates/blocks/services', 'individual' );
        
        // Pricing
        get_template_part( 'templates/blocks/services', 'pricing' );
        
        // Testimonials
        get_template_part( 'templates/blocks/services', 'testimonials' );
        
        // Call to Action
        get_template_part( 'templates/blocks/services', 'cta' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();