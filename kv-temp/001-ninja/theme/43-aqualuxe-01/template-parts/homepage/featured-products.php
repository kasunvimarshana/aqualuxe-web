<?php
/**
 * Template part for displaying featured products on the homepage
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// Get featured products settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_featured_products_title', __( 'Featured Products', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_featured_products_description', __( 'Discover our premium selection of rare and exotic aquatic species and supplies.', 'aqualuxe' ) );
$number_of_products = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
$product_type = get_theme_mod( 'aqualuxe_featured_products_type', 'featured' );
$view_all_text = get_theme_mod( 'aqualuxe_featured_products_view_all_text', __( 'View All Products', 'aqualuxe' ) );
$view_all_url = get_theme_mod( 'aqualuxe_featured_products_view_all_url', wc_get_page_permalink( 'shop' ) );
$show_section = get_theme_mod( 'aqualuxe_featured_products_show', true );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Get products based on selected type
switch ( $product_type ) {
    case 'featured':
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $number_of_products,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ),
            ),
        );
        break;
    case 'sale':
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $number_of_products,
            'meta_query'     => array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
                array( // Variable products type
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            ),
        );
        break;
    case 'best_selling':
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $number_of_products,
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        );
        break;
    case 'new':
    default:
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $number_of_products,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        break;
}

// Get products
$products = new WP_Query( $args );

// If no products found, return
if ( ! $products->have_posts() ) {
    return;
}
?>

<section class="featured-products py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto"><?php echo esc_html( $section_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="products-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            while ( $products->have_posts() ) :
                $products->the_post();
                global $product;
                ?>
                <div class="product-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                    <a href="<?php the_permalink(); ?>" class="product-link block">
                        <div class="product-image relative">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-64 object-cover' ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-64 object-cover" />
                            <?php endif; ?>
                            
                            <?php if ( $product->is_on_sale() ) : ?>
                                <span class="sale-badge absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( ! $product->is_in_stock() ) : ?>
                                <div class="out-of-stock-overlay absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg"><?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-details p-4">
                            <h3 class="product-title text-lg font-bold mb-2"><?php the_title(); ?></h3>
                            
                            <div class="product-price mb-3">
                                <?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            
                            <?php if ( $product->get_average_rating() > 0 ) : ?>
                                <div class="product-rating flex items-center mb-3">
                                    <?php echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <span class="rating-count text-sm text-gray-600 dark:text-gray-400 ml-2">
                                        <?php echo esc_html( $product->get_review_count() ); ?> <?php esc_html_e( 'reviews', 'aqualuxe' ); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <div class="product-actions p-4 pt-0">
                        <?php if ( $product->is_in_stock() ) : ?>
                            <?php if ( $product->is_type( 'simple' ) ) : ?>
                                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" class="add-to-cart-button button w-full bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded text-center block" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php echo esc_attr__( 'Add to cart', 'aqualuxe' ); ?>">
                                    <?php esc_html_e( 'Add to Cart', 'aqualuxe' ); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>" class="view-product-button w-full bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded text-center block">
                                    <?php esc_html_e( 'View Product', 'aqualuxe' ); ?>
                                </a>
                            <?php endif; ?>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="view-product-button w-full bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-2 px-4 rounded text-center block">
                                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $view_all_text && $view_all_url ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <?php echo esc_html( $view_all_text ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>