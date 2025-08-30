<?php
/**
 * Template Name: Services Page
 * 
 * The template for displaying the Services page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    
    <?php get_template_part( 'template-parts/services/hero' ); ?>
    
    <div class="container mx-auto px-4 py-12">
        <?php get_template_part( 'template-parts/services/intro' ); ?>
        
        <?php get_template_part( 'template-parts/services/design' ); ?>
        
        <?php get_template_part( 'template-parts/services/maintenance' ); ?>
        
        <?php get_template_part( 'template-parts/services/quarantine' ); ?>
        
        <?php get_template_part( 'template-parts/services/breeding' ); ?>
        
        <?php get_template_part( 'template-parts/services/consultation' ); ?>
        
        <?php get_template_part( 'template-parts/services/pricing' ); ?>
        
        <?php get_template_part( 'template-parts/services/testimonials' ); ?>
        
        <?php get_template_part( 'template-parts/services/cta' ); ?>
    </div>

</main><!-- #primary -->

<?php
get_footer();