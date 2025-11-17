<?php
/**
 * Front Page: Featured Products Section
 *
 * @package AquaLuxe
 */

$featured_products_query = new WP_Query( array(
    'post_type' => 'product',
    'posts_per_page' => 6,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
        ),
    ),
) );
?>
<section id="featured-products" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12"><?php esc_html_e( 'Featured Collection', 'aqualuxe' ); ?></h2>
        <?php if ( $featured_products_query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ( $featured_products_query->have_posts() ) : $featured_products_query->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'product' ); ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="text-center"><?php esc_html_e( 'No featured products found.', 'aqualuxe' ); ?></p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
        <div class="text-center mt-12">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-8 rounded-full transition duration-300">
                <?php esc_html_e( 'View All Products', 'aqualuxe' ); ?>
            </a>
        </div>
    </div>
</section>
