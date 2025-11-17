<?php
/**
 * Homepage Featured Products Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Skip if WooCommerce is not active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// Get featured products settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_featured_products_title', __( 'Featured Products', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_featured_products_subtitle', __( 'Discover our handpicked selection of premium products', 'aqualuxe' ) );
$products_count = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
$columns = get_theme_mod( 'aqualuxe_featured_products_columns', 4 );
$product_type = get_theme_mod( 'aqualuxe_featured_products_type', 'featured' );
$view_all_text = get_theme_mod( 'aqualuxe_featured_products_view_all_text', __( 'View All Products', 'aqualuxe' ) );
$view_all_url = get_theme_mod( 'aqualuxe_featured_products_view_all_url', wc_get_page_permalink( 'shop' ) );

// Product query args
$args = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page'      => $products_count,
    'orderby'             => 'date',
    'order'               => 'DESC',
);

// Set product type query
switch ( $product_type ) {
    case 'featured':
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ),
        );
        break;
    case 'best_selling':
        $args['meta_key'] = 'total_sales';
        $args['orderby']  = 'meta_value_num';
        break;
    case 'sale':
        $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
        break;
    case 'new':
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
        break;
}

// Get products
$products = new WP_Query( $args );

// Skip if no products
if ( ! $products->have_posts() ) {
    return;
}
?>

<section class="featured-products-section section">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="products-grid columns-<?php echo esc_attr( $columns ); ?>">
            <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                <?php wc_get_template_part( 'content', 'product' ); ?>
            <?php endwhile; ?>
        </div>
        
        <?php if ( $view_all_text && $view_all_url ) : ?>
            <div class="section-footer text-center">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="btn btn-outline-primary"><?php echo esc_html( $view_all_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();