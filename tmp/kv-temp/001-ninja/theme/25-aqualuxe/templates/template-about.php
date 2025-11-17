<?php
/**
 * Template Name: About Page
 *
 * The template for displaying the about page
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
    get_template_part('template-parts/about/hero');
    
    // Our Story Section
    get_template_part('template-parts/about/our-story');
    
    // Mission & Vision Section
    get_template_part('template-parts/about/mission-vision');
    
    // Team Members Section
    get_template_part('template-parts/about/team');
    
    // Sustainability Section
    if (get_theme_mod('aqualuxe_about_show_sustainability', true)) {
        get_template_part('template-parts/about/sustainability');
    }
    
    // Testimonials Section
    if (get_theme_mod('aqualuxe_about_show_testimonials', true)) {
        get_template_part('template-parts/about/testimonials');
    }
    
    // Partners Section
    if (get_theme_mod('aqualuxe_about_show_partners', true)) {
        get_template_part('template-parts/about/partners');
    }
    
    // Call to Action Section
    get_template_part('template-parts/about/cta');
    ?>
</main><!-- #main -->

<?php
get_footer();