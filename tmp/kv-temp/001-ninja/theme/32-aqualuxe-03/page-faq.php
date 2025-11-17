<?php
/**
 * Template Name: FAQ Page
 * 
 * The template for displaying the FAQ page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    
    <?php get_template_part( 'template-parts/faq/hero' ); ?>
    
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-3/4">
                <?php get_template_part( 'template-parts/faq/categories' ); ?>
                
                <div class="mt-12">
                    <?php get_template_part( 'template-parts/faq/shipping' ); ?>
                </div>
                
                <div class="mt-12">
                    <?php get_template_part( 'template-parts/faq/care' ); ?>
                </div>
                
                <div class="mt-12">
                    <?php get_template_part( 'template-parts/faq/purchasing' ); ?>
                </div>
                
                <div class="mt-12">
                    <?php get_template_part( 'template-parts/faq/export-import' ); ?>
                </div>
            </div>
            
            <div class="w-full lg:w-1/4">
                <?php get_template_part( 'template-parts/faq/sidebar' ); ?>
            </div>
        </div>
    </div>
    
    <?php get_template_part( 'template-parts/faq/contact' ); ?>

</main><!-- #primary -->

<?php
get_footer();