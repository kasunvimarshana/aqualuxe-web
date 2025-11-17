<?php
/**
 * Template Name: FAQ
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part( 'templates/template-parts/faq/hero' );
    
    // FAQ Categories Section
    get_template_part( 'templates/template-parts/faq/categories' );
    
    // FAQ List Section
    get_template_part( 'templates/template-parts/faq/list' );
    
    // Search Section
    get_template_part( 'templates/template-parts/faq/search' );
    
    // Contact Section
    get_template_part( 'templates/template-parts/faq/contact' );
    ?>

</main><!-- #primary -->

<?php
get_footer();