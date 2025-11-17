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
        get_template_part('templates/services/hero');
        
        // Services Overview Section
        get_template_part('templates/services/overview');
        
        // Services Grid Section
        get_template_part('templates/services/grid');
        
        // Featured Service Section
        get_template_part('templates/services/featured');
        
        // Process Section
        get_template_part('templates/services/process');
        
        // Testimonials Section
        get_template_part('templates/services/testimonials');
        
        // Pricing Section
        get_template_part('templates/services/pricing');
        
        // FAQ Section
        get_template_part('templates/services/faq');
        
        // CTA Section
        get_template_part('templates/services/cta');
        ?>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();