<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('', $product); ?>>
    <div class="product-inner">
        
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="product-image">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             * @hooked aqualuxe_product_badges - 15
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </div>

        <div class="product-info">
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked aqualuxe_woocommerce_loop_product_title - 10
             */
            do_action('woocommerce_shop_loop_item_title');
            ?>

            <div class="product-meta">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action('woocommerce_after_shop_loop_item_title');
                ?>
            </div>

            <div class="product-excerpt">
                <?php
                $excerpt = apply_filters('the_excerpt', get_the_excerpt());
                if ($excerpt) {
                    echo '<p>' . esc_html($excerpt) . '</p>';
                }
                ?>
            </div>
        </div>

        <div class="product-actions">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_add_to_cart - 10
             * @hooked aqualuxe_add_quick_view_button - 15
             */
            do_action('woocommerce_after_shop_loop_item');
            ?>
        </div>

    </div>
</li>