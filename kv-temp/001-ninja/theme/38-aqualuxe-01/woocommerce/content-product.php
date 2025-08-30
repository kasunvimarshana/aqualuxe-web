<?php
/**
 * The template for displaying product content within loops
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Extra post classes
$classes = array( 'product-card' );

// Add class for product stock status
if ( ! $product->is_in_stock() ) {
    $classes[] = 'out-of-stock';
}

// Add class for product on sale
if ( $product->is_on_sale() ) {
    $classes[] = 'on-sale';
}

// Add class for featured products
if ( $product->is_featured() ) {
    $classes[] = 'featured';
}
?>

<li <?php wc_product_class( $classes, $product ); ?>>
    <div class="product-card-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action( 'woocommerce_before_shop_loop_item' );
        ?>
        
        <div class="product-card-media">
            <?php
            /**
             * Hook: aqualuxe_before_shop_loop_item_thumbnail.
             */
            do_action( 'aqualuxe_before_shop_loop_item_thumbnail' );
            
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            
            /**
             * Hook: aqualuxe_after_shop_loop_item_thumbnail.
             */
            do_action( 'aqualuxe_after_shop_loop_item_thumbnail' );
            ?>
            
            <div class="product-card-actions">
                <?php
                /**
                 * Hook: aqualuxe_product_card_actions.
                 *
                 * @hooked aqualuxe_template_loop_quick_view - 10
                 * @hooked aqualuxe_template_loop_wishlist - 20
                 * @hooked aqualuxe_template_loop_compare - 30
                 */
                do_action( 'aqualuxe_product_card_actions' );
                ?>
            </div>
        </div>
        
        <div class="product-card-content">
            <?php
            /**
             * Hook: aqualuxe_before_shop_loop_item_title.
             */
            do_action( 'aqualuxe_before_shop_loop_item_title' );
            ?>
            
            <div class="product-card-categories">
                <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-card-category">', '</span>' ); ?>
            </div>
            
            <h2 class="woocommerce-loop-product__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            
            <?php
            /**
             * Hook: aqualuxe_after_shop_loop_item_title.
             */
            do_action( 'aqualuxe_after_shop_loop_item_title' );
            
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
            
            <div class="product-card-description">
                <?php echo wp_trim_words( $product->get_short_description(), 10, '...' ); ?>
            </div>
            
            <div class="product-card-footer">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item' );
                
                /**
                 * Hook: aqualuxe_after_shop_loop_item.
                 */
                do_action( 'aqualuxe_after_shop_loop_item' );
                ?>
            </div>
        </div>
    </div>
</li>