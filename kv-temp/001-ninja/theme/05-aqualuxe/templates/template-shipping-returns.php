<?php
/**
 * Template Name: Shipping & Returns
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
        get_template_part( 'templates/blocks/shipping', 'hero' );
        
        // Shipping Information
        get_template_part( 'templates/blocks/shipping', 'info' );
        
        // Returns Policy
        get_template_part( 'templates/blocks/shipping', 'returns' );
        
        // Guarantees
        get_template_part( 'templates/blocks/shipping', 'guarantees' );
        
        // FAQ
        get_template_part( 'templates/blocks/shipping', 'faq' );
        
        // Contact CTA
        get_template_part( 'templates/blocks/shipping', 'cta' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();