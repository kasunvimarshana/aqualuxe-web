<?php
/**
 * Template Name: FAQ Page
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
        get_template_part( 'templates/blocks/faq', 'hero' );
        
        // FAQ Categories
        get_template_part( 'templates/blocks/faq', 'categories' );
        
        // FAQ Items
        get_template_part( 'templates/blocks/faq', 'items' );
        
        // Contact CTA
        get_template_part( 'templates/blocks/faq', 'cta' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();