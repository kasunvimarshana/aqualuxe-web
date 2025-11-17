<?php
/**
 * Template Name: Privacy Policy
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
        get_template_part( 'templates/blocks/privacy', 'hero' );
        
        // Privacy Policy Content
        get_template_part( 'templates/blocks/privacy', 'content' );
        
        // Contact CTA
        get_template_part( 'templates/blocks/privacy', 'cta' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();