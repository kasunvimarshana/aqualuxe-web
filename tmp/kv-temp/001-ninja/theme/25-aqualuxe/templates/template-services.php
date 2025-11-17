<?php
/**
 * Template Name: Services Page
 *
 * The template for displaying the services page
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
    get_template_part('template-parts/services/hero');
    
    // Services Overview Section
    get_template_part('template-parts/services/overview');
    
    // Services List Section
    get_template_part('template-parts/services/services-list');
    
    // Process Section
    if (get_theme_mod('aqualuxe_services_show_process', true)) {
        get_template_part('template-parts/services/process');
    }
    
    // Testimonials Section
    if (get_theme_mod('aqualuxe_services_show_testimonials', true)) {
        get_template_part('template-parts/services/testimonials');
    }
    
    // FAQ Section
    if (get_theme_mod('aqualuxe_services_show_faq', true)) {
        get_template_part('template-parts/services/faq');
    }
    
    // Call to Action Section
    get_template_part('template-parts/services/cta');
    ?>
</main><!-- #main -->

<?php
get_footer();