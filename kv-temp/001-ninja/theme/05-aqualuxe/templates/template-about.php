<?php
/**
 * Template Name: About Page
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
        get_template_part( 'templates/blocks/about', 'hero' );
        
        // Company History
        get_template_part( 'templates/blocks/about', 'history' );
        
        // Mission and Values
        get_template_part( 'templates/blocks/about', 'mission' );
        
        // Team Section
        get_template_part( 'templates/blocks/about', 'team' );
        
        // Facilities/Farm Images
        get_template_part( 'templates/blocks/about', 'facilities' );
        
        // Call to Action
        get_template_part( 'templates/blocks/about', 'cta' );
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();