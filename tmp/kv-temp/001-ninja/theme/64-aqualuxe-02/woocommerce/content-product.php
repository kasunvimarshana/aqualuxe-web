<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( 'product-card', $product ); ?>>
    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    do_action( 'woocommerce_before_shop_loop_item' );
    ?>

    <div class="product-image">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item_title.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action( 'woocommerce_before_shop_loop_item_title' );

        // Out of stock overlay
        if ( ! $product->is_in_stock() ) : ?>
            <div class="outofstock-overlay">
                <span><?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?></span>
            </div>
        <?php endif; ?>

        <div class="product-actions">
            <?php
            /**
             * Hook: aqualuxe_product_actions.
             *
             * @hooked aqualuxe_quick_view_button - 10
             * @hooked aqualuxe_wishlist_button - 20
             */
            do_action( 'aqualuxe_product_actions' );
            ?>
        </div>
    </div>

    <div class="product-details">
        <?php
        // Product categories
        $categories = wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-category">', '</div>' );
        if ( $categories ) {
            echo wp_kses_post( $categories );
        }
        ?>

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
</li>