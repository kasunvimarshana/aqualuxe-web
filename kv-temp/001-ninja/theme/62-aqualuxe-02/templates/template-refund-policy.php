<?php
/**
 * Template Name: Refund Policy
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part( 'templates/template-parts/legal/hero' );
    
    // Content Section
    get_template_part( 'templates/template-parts/legal/content' );
    
    // Table of Contents Section
    get_template_part( 'templates/template-parts/legal/table-of-contents' );
    
    // Contact Section
    get_template_part( 'templates/template-parts/legal/contact' );
    ?>

</main><!-- #primary -->

<?php
get_footer();