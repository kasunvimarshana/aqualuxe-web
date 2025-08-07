<?php
/**
 * The template for displaying product content within loops
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Check if product is purchasable
$is_purchasable = $product->is_purchasable();

// Get product image
$image = $product->get_image( 'aqualuxe-product-card' );

// Get product title
$title = $product->get_name();

// Get product price
$price = $product->get_price_html();

// Get product permalink
$permalink = $product->get_permalink();

// Get product rating
$rating = wc_get_rating_html( $product->get_average_rating() );

// Get product categories
$categories = wc_get_product_category_list( $product->get_id() );
?>

<div <?php wc_product_class( 'product-card', $product ); ?>>
    <?php
    /**
     * woocommerce_before_shop_loop_item hook
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action( 'woocommerce_before_shop_loop_item' );
    ?>
    
    <div class="product-image">
        <?php echo $image; ?>
        
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action( 'woocommerce_before_shop_loop_item_title' );
        ?>
    </div>
    
    <div class="product-info">
        <?php
        /**
         * woocommerce_shop_loop_item_title hook
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        do_action( 'woocommerce_shop_loop_item_title' );
        ?>
        
        <?php if ( $rating ) : ?>
            <div class="product-rating">
                <?php echo $rating; ?>
            </div>
        <?php endif; ?>
        
        <div class="product-price">
            <?php echo $price; ?>
        </div>
        
        <?php if ( $categories ) : ?>
            <div class="product-categories">
                <?php echo $categories; ?>
            </div>
        <?php endif; ?>
        
        <?php
        /**
         * woocommerce_after_shop_loop_item_title hook
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action( 'woocommerce_after_shop_loop_item_title' );
        ?>
        
        <?php if ( $is_purchasable ) : ?>
            <div class="product-add-to-cart">
                <?php
                /**
                 * woocommerce_after_shop_loop_item hook
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item' );
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>