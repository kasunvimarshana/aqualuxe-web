<?php
/**
 * The template for displaying product content within loops
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-item', $product); ?>>
    <div class="product-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');

        /**
         * Hook: woocommerce_before_shop_loop_item_title.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action('woocommerce_before_shop_loop_item_title');

        /**
         * Hook: woocommerce_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        do_action('woocommerce_shop_loop_item_title');

        /**
         * Hook: woocommerce_after_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action('woocommerce_after_shop_loop_item_title');

        /**
         * Hook: woocommerce_after_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_close - 5
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        do_action('woocommerce_after_shop_loop_item');
        ?>
        
        <div class="product-actions">
            <?php
            // Add quick view button
            echo '<button class="quick-view-btn" data-product-id="' . esc_attr($product->get_id()) . '">';
            echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">';
            echo '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>';
            echo '<circle cx="12" cy="12" r="3"></circle>';
            echo '</svg>';
            echo '</button>';
            
            // Add wishlist button
            echo '<button class="wishlist-btn" data-product-id="' . esc_attr($product->get_id()) . '">';
            echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">';
            echo '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>';
            echo '</svg>';
            echo '</button>';
            ?>
        </div>
    </div>
</li>