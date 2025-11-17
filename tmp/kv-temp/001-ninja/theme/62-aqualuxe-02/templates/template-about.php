<?php
/**
 * Template Name: About
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part( 'templates/template-parts/about/hero' );
    
    // Our Story Section
    get_template_part( 'templates/template-parts/about/our-story' );
    
    // Mission & Values Section
    get_template_part( 'templates/template-parts/about/mission-values' );
    
    // Team Section
    get_template_part( 'templates/template-parts/about/team' );
    
    // Stats Section
    get_template_part( 'templates/template-parts/about/stats' );
    
    // Testimonials Section
    get_template_part( 'templates/template-parts/about/testimonials' );
    
    // Partners Section
    get_template_part( 'templates/template-parts/about/partners' );
    
    // CTA Section
    get_template_part( 'templates/template-parts/about/cta' );
    ?>

</main><!-- #primary -->

<?php
get_footer();