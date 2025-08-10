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

// Get featured products settings from customizer or use defaults
$featured_title = get_theme_mod( 'aqualuxe_featured_products_title', 'Featured Products' );
$featured_subtitle = get_theme_mod( 'aqualuxe_featured_products_subtitle', 'Our handpicked selection of premium aquatic treasures' );
$featured_count = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
$featured_columns = get_theme_mod( 'aqualuxe_featured_products_columns', 4 );

// Get featured products
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => $featured_count,
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        ),
    ),
);

$featured_products = new WP_Query( $args );

// If no featured products, get recent products instead
if ( ! $featured_products->have_posts() ) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $featured_count,
    );
    
    $featured_products = new WP_Query( $args );
}

// Return if no products found
if ( ! $featured_products->have_posts() ) {
    return;
}
?>

<section id="featured-products" class="featured-products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $featured_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $featured_subtitle ); ?></div>
        </div>
        
        <div class="products-grid columns-<?php echo esc_attr( $featured_columns ); ?>">
            <?php
            while ( $featured_products->have_posts() ) :
                $featured_products->the_post();
                global $product;
                ?>
                <div class="product-item">
                    <div class="product-inner">
                        <div class="product-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo woocommerce_get_product_thumbnail(); ?>
                            </a>
                            <div class="product-actions">
                                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-quantity="1"><?php esc_html_e( 'Add to Cart', 'aqualuxe' ); ?></a>
                                <a href="<?php the_permalink(); ?>" class="button product-details"><?php esc_html_e( 'Details', 'aqualuxe' ); ?></a>
                            </div>
                        </div>
                        <div class="product-content">
                            <h3 class="product-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="product-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="section-footer">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'View All Products', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>