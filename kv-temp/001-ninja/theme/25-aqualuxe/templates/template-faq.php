<?php
/**
 * Template Name: FAQ Page
 *
 * The template for displaying the FAQ page
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
    get_template_part('template-parts/faq/hero');
    
    // FAQ Categories Section
    get_template_part('template-parts/faq/categories');
    
    // FAQ List Section
    get_template_part('template-parts/faq/faq-list');
    
    // Contact Section
    if (get_theme_mod('aqualuxe_faq_show_contact', true)) {
        get_template_part('template-parts/faq/contact');
    }
    ?>
</main><!-- #main -->

<?php
get_footer();