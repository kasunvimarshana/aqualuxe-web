<?php
/**
 * Template Name: Legal Page
 *
 * The template for displaying legal pages like Terms & Conditions, Privacy Policy, etc.
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
    get_template_part('template-parts/legal/hero');
    
    // Content Section
    get_template_part('template-parts/legal/content');
    
    // Related Legal Pages Section
    if (get_theme_mod('aqualuxe_legal_show_related', true)) {
        get_template_part('template-parts/legal/related');
    }
    
    // Contact Section
    if (get_theme_mod('aqualuxe_legal_show_contact', true)) {
        get_template_part('template-parts/legal/contact');
    }
    ?>
</main><!-- #main -->

<?php
get_footer();