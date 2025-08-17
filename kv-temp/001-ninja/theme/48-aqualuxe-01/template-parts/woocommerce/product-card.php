<?php
/**
 * Template part for displaying product card
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $product;

// Ensure $product is valid
if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
    return;
}

// Get product data
$product_id = $product->get_id();
$product_url = get_permalink( $product_id );
$product_title = $product->get_name();
$product_price_html = $product->get_price_html();
$product_image_id = $product->get_image_id();
$product_image = $product_image_id ? wp_get_attachment_image_src( $product_image_id, 'woocommerce_thumbnail' ) : '';
$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src( 'woocommerce_thumbnail' );
$product_rating = $product->get_average_rating();
$product_rating_count = $product->get_rating_count();
$product_is_on_sale = $product->is_on_sale();
$product_is_featured = $product->is_featured();
$product_is_in_stock = $product->is_in_stock();

// Check if product is new (within the last 30 days)
$new_days = aqualuxe_get_option( 'new_product_days', 30 );
$product_date = strtotime( $product->get_date_created() );
$now = time();
$days_diff = floor( ( $now - $product_date ) / ( 60 * 60 * 24 ) );
$product_is_new = $days_diff < $new_days;
?>

<div class="product-card relative bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
        <?php if ( $product_is_on_sale ) : ?>
            <span class="product-badge product-badge-sale inline-block px-2 py-1 bg-accent text-dark text-xs font-medium rounded">
                <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( $product_is_new ) : ?>
            <span class="product-badge product-badge-new inline-block px-2 py-1 bg-primary text-white text-xs font-medium rounded">
                <?php esc_html_e( 'New', 'aqualuxe' ); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( $product_is_featured ) : ?>
            <span class="product-badge product-badge-featured inline-block px-2 py-1 bg-secondary text-primary text-xs font-medium rounded">
                <?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
            </span>
        <?php endif; ?>
        
        <?php if ( ! $product_is_in_stock ) : ?>
            <span class="product-badge product-badge-outofstock inline-block px-2 py-1 bg-gray-600 text-white text-xs font-medium rounded">
                <?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <div class="product-actions absolute top-4 right-4 z-10 flex flex-col gap-2">
        <?php if ( aqualuxe_get_option( 'enable_quick_view', true ) ) : ?>
            <button class="product-action-button product-quick-view w-8 h-8 flex items-center justify-center bg-white dark:bg-dark-light text-primary hover:text-primary-dark rounded-full shadow-md transition-colors" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Quick View', 'aqualuxe' ); ?>">
                <i class="fas fa-eye"></i>
            </button>
        <?php endif; ?>
        
        <?php if ( aqualuxe_get_option( 'enable_wishlist', true ) ) : ?>
            <button class="product-action-button product-wishlist w-8 h-8 flex items-center justify-center bg-white dark:bg-dark-light text-primary hover:text-primary-dark rounded-full shadow-md transition-colors" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Add to Wishlist', 'aqualuxe' ); ?>">
                <i class="fas fa-heart"></i>
            </button>
        <?php endif; ?>
        
        <?php if ( aqualuxe_get_option( 'enable_compare', true ) ) : ?>
            <button class="product-action-button product-compare w-8 h-8 flex items-center justify-center bg-white dark:bg-dark-light text-primary hover:text-primary-dark rounded-full shadow-md transition-colors" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Compare', 'aqualuxe' ); ?>">
                <i class="fas fa-exchange-alt"></i>
            </button>
        <?php endif; ?>
    </div>
    
    <a href="<?php echo esc_url( $product_url ); ?>" class="product-image-wrapper block relative overflow-hidden aspect-w-1 aspect-h-1">
        <img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo esc_attr( $product_title ); ?>" class="product-image w-full h-full object-cover transition-transform duration-500 hover:scale-105">
    </a>
    
    <div class="product-content p-4">
        <?php if ( aqualuxe_get_option( 'show_product_categories', true ) ) : ?>
            <div class="product-categories mb-1">
                <?php echo wc_get_product_category_list( $product_id, ', ', '<span class="text-xs text-gray-600 dark:text-gray-400">', '</span>' ); ?>
            </div>
        <?php endif; ?>
        
        <h3 class="product-title text-lg font-medium mb-2">
            <a href="<?php echo esc_url( $product_url ); ?>" class="hover:text-primary transition-colors">
                <?php echo esc_html( $product_title ); ?>
            </a>
        </h3>
        
        <?php if ( $product_rating_count > 0 ) : ?>
            <div class="product-rating flex items-center mb-2">
                <div class="star-rating" role="img" aria-label="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'aqualuxe' ), $product_rating ); ?>">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <i class="<?php echo $i <= $product_rating ? 'fas' : 'far'; ?> fa-star text-accent text-sm"></i>
                    <?php endfor; ?>
                </div>
                <span class="rating-count text-xs text-gray-600 dark:text-gray-400 ml-2">(<?php echo esc_html( $product_rating_count ); ?>)</span>
            </div>
        <?php endif; ?>
        
        <div class="product-price text-lg font-bold text-primary mb-3">
            <?php echo wp_kses_post( $product_price_html ); ?>
        </div>
        
        <div class="product-add-to-cart">
            <?php
            echo apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf(
                    '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                    esc_attr( isset( $args['class'] ) ? $args['class'] : 'button add_to_cart_button ajax_add_to_cart w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white text-center rounded transition-colors' ),
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