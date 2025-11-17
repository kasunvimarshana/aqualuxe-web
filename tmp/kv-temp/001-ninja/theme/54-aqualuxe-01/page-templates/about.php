<?php
/**
 * Template Name: About Page
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
        get_template_part('templates/about/hero');
        
        // Mission & Vision Section
        get_template_part('templates/about/mission-vision');
        
        // History Section
        get_template_part('templates/about/history');
        
        // Team Section
        get_template_part('templates/about/team');
        
        // Values Section
        get_template_part('templates/about/values');
        
        // Stats Section
        get_template_part('templates/about/stats');
        
        // Testimonials Section
        get_template_part('templates/about/testimonials');
        
        // Partners Section
        get_template_part('templates/about/partners');
        
        // CTA Section
        get_template_part('templates/about/cta');
        ?>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();