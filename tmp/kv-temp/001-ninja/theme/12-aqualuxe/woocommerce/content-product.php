<?php
/**
 * The template for displaying product content within loops
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
    <div class="product-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action( 'woocommerce_before_shop_loop_item' );

        /**
         * Hook: woocommerce_before_shop_loop_item_title.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         * @hooked aqualuxe_product_badges - 20 (custom function)
         */
        ?>
        <div class="product-thumbnail">
            <?php
            do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>
            <div class="product-actions">
                <?php
                // Quick View Button
                echo '<a href="#" class="quick-view" data-product-id="' . esc_attr( $product->get_id() ) . '" title="' . esc_attr__( 'Quick View', 'aqualuxe' ) . '"><i class="fas fa-eye"></i></a>';
                
                // Wishlist Button
                echo '<a href="#" class="add-to-wishlist" data-product-id="' . esc_attr( $product->get_id() ) . '" title="' . esc_attr__( 'Add to Wishlist', 'aqualuxe' ) . '"><i class="far fa-heart"></i></a>';
                
                // Compare Button
                echo '<a href="#" class="add-to-compare" data-product-id="' . esc_attr( $product->get_id() ) . '" title="' . esc_attr__( 'Compare', 'aqualuxe' ) . '"><i class="fas fa-exchange-alt"></i></a>';
                ?>
            </div>
        </div>

        <div class="product-details">
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            do_action( 'woocommerce_shop_loop_item_title' );

            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );

            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_product_link_close - 5
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            do_action( 'woocommerce_after_shop_loop_item' );
            ?>
        </div>
    </div>
</li>