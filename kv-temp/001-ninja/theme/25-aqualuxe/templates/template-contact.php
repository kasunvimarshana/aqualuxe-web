<?php
/**
 * Template Name: Contact Page
 *
 * The template for displaying the contact page
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
    get_template_part('template-parts/contact/hero');
    
    // Contact Info & Form Section
    get_template_part('template-parts/contact/contact-form');
    
    // Map Section
    if (get_theme_mod('aqualuxe_contact_show_map', true)) {
        get_template_part('template-parts/contact/map');
    }
    
    // Locations Section
    if (get_theme_mod('aqualuxe_contact_show_locations', true)) {
        get_template_part('template-parts/contact/locations');
    }
    
    // FAQ Section
    if (get_theme_mod('aqualuxe_contact_show_faq', true)) {
        get_template_part('template-parts/contact/faq');
    }
    ?>
</main><!-- #main -->

<?php
get_footer();