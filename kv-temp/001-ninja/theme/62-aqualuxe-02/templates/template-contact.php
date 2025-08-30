<?php
/**
 * Template Name: Contact
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part( 'templates/template-parts/contact/hero' );
    
    // Contact Form Section
    get_template_part( 'templates/template-parts/contact/form' );
    
    // Contact Info Section
    get_template_part( 'templates/template-parts/contact/info' );
    
    // Map Section
    get_template_part( 'templates/template-parts/contact/map' );
    
    // FAQ Section
    get_template_part( 'templates/template-parts/contact/faq' );
    
    // CTA Section
    get_template_part( 'templates/template-parts/contact/cta' );
    ?>

</main><!-- #primary -->

<?php
get_footer();