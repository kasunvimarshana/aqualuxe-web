<?php
/**
 * Template Name: Homepage
 *
 * The template for displaying the homepage with all custom sections.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    get_template_part( 'template-parts/homepage/hero' );
    
    // Featured Products Section (only if WooCommerce is active)
    if ( class_exists( 'WooCommerce' ) ) {
        get_template_part( 'template-parts/homepage/featured-products' );
    }
    
    // About Section
    get_template_part( 'template-parts/homepage/about' );
    
    // Services Section
    get_template_part( 'template-parts/homepage/services' );
    
    // Testimonials Section
    get_template_part( 'template-parts/homepage/testimonials' );
    
    // Latest Blog Posts Section
    get_template_part( 'template-parts/homepage/latest-posts' );
    
    // Newsletter Section
    get_template_part( 'template-parts/homepage/newsletter' );
    
    // Partners/Brands Section
    get_template_part( 'template-parts/homepage/partners' );
    
    // Custom Content Section (from page content)
    while ( have_posts() ) :
        the_post();
        
        if ( '' !== get_the_content() ) :
            ?>
            <section class="custom-content py-16">
                <div class="container mx-auto px-4">
                    <div class="prose max-w-none">
                        <?php the_content(); ?>
                    </div>
                </div>
            </section>
            <?php
        endif;
        
    endwhile;
    ?>
</main><!-- #main -->

<?php
get_footer();