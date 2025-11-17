<?php
/**
 * Template part for displaying featured products on the homepage
 *
 * @package AquaLuxe
 */

// Exit if WooCommerce is not active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'featured_products_title', 'Featured Products' );
$section_subtitle = aqualuxe_get_option( 'featured_products_subtitle', 'Discover our premium selection of aquatic products' );
$products_count = aqualuxe_get_option( 'featured_products_count', 4 );
$products_columns = aqualuxe_get_option( 'featured_products_columns', 4 );
$show_view_all = aqualuxe_get_option( 'featured_products_show_view_all', true );
$view_all_text = aqualuxe_get_option( 'featured_products_view_all_text', 'View All Products' );
$view_all_url = aqualuxe_get_option( 'featured_products_view_all_url', get_permalink( wc_get_page_id( 'shop' ) ) );
$featured_product_type = aqualuxe_get_option( 'featured_product_type', 'featured' );

// Set up the query arguments based on the product type
switch ( $featured_product_type ) {
    case 'featured':
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $products_count,
            'tax_query'           => array(
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
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $products_count,
            'meta_query'          => array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric',
                ),
                array( // Variable products type
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric',
                ),
            ),
        );
        break;
    case 'best_selling':
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $products_count,
            'meta_key'            => 'total_sales',
            'orderby'             => 'meta_value_num',
            'order'               => 'DESC',
        );
        break;
    case 'top_rated':
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $products_count,
            'meta_key'            => '_wc_average_rating',
            'orderby'             => 'meta_value_num',
            'order'               => 'DESC',
        );
        break;
    case 'newest':
    default:
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $products_count,
            'orderby'             => 'date',
            'order'               => 'DESC',
        );
        break;
}

// Run the query
$products = new WP_Query( $args );

// Only display the section if we have products
if ( ! $products->have_posts() ) {
    return;
}

// Set up grid columns class
$grid_columns_class = '';
switch ( $products_columns ) {
    case 2:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2';
        break;
    case 3:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
        break;
    case 4:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
        break;
    case 5:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-5';
        break;
    case 6:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6';
        break;
    default:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
}
?>

<section id="featured-products" class="featured-products py-16 bg-gray-50 dark:bg-dark-400">
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="products-grid grid <?php echo esc_attr( $grid_columns_class ); ?> gap-6">
            <?php
            while ( $products->have_posts() ) :
                $products->the_post();
                global $product;
                ?>
                <div class="product-card card overflow-hidden">
                    <div class="product-thumbnail relative">
                        <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                            <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-64 object-cover hover:scale-105 transition-transform duration-300' ) );
                            } else {
                                echo wc_placeholder_img( 'woocommerce_thumbnail' );
                            }
                            ?>
                        </a>
                        
                        <?php if ( $product->is_on_sale() ) : ?>
                            <span class="sale-badge absolute top-2 left-2 bg-secondary-500 text-white text-xs font-bold px-2 py-1 rounded">
                                <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ( aqualuxe_get_option( 'show_quick_view', true ) ) : ?>
                            <button class="aqualuxe-quick-view-button absolute bottom-2 left-2 bg-white dark:bg-dark-500 text-dark-500 dark:text-white hover:bg-primary-500 hover:text-white dark:hover:bg-primary-500 rounded-full w-10 h-10 flex items-center justify-center transition-colors duration-200" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                <span class="sr-only"><?php esc_html_e( 'Quick View', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        <?php endif; ?>
                        
                        <?php if ( aqualuxe_get_option( 'show_wishlist', true ) ) : ?>
                            <button class="aqualuxe-wishlist-button absolute top-2 right-2 bg-white dark:bg-dark-500 text-gray-400 hover:text-primary-500 dark:hover:text-primary-400 rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-200" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                <span class="sr-only"><?php esc_html_e( 'Add to Wishlist', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-details p-4">
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <?php if ( $product->get_rating_count() > 0 ) : ?>
                            <div class="product-rating flex items-center mb-2">
                                <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                <span class="rating-count text-sm text-gray-500 dark:text-gray-400 ml-1">(<?php echo esc_html( $product->get_rating_count() ); ?>)</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-price mb-3">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                        
                        <div class="product-actions">
                            <?php
                            echo apply_filters(
                                'woocommerce_loop_add_to_cart_link',
                                sprintf(
                                    '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                    esc_url( $product->add_to_cart_url() ),
                                    esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                    esc_attr( 'btn-primary w-full text-center' ),
                                    isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                    esc_html( $product->add_to_cart_text() )
                                ),
                                $product,
                                $args
                            );
                            ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $show_view_all ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="btn-outline">
                    <?php echo esc_html( $view_all_text ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>