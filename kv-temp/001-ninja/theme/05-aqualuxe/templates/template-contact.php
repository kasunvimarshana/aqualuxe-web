<?php
/**
 * Template Name: Contact Page
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
        get_template_part( 'templates/blocks/contact', 'hero' );
        
        // Contact Information
        get_template_part( 'templates/blocks/contact', 'info' );
        
        // Contact Form
        get_template_part( 'templates/blocks/contact', 'form' );
        
        // Map
        get_template_part( 'templates/blocks/contact', 'map' );
        
        // FAQ
        get_template_part( 'templates/blocks/contact', 'faq' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();