<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

// Get product card style from theme options
$product_card_style = aqualuxe_get_option('product_card_style', 'default');
$show_rating = aqualuxe_get_option('product_show_rating', true);
$show_price = aqualuxe_get_option('product_show_price', true);
$show_category = aqualuxe_get_option('product_show_category', true);
$show_quick_view = aqualuxe_get_option('product_show_quick_view', true);
$show_wishlist = aqualuxe_get_option('product_show_wishlist', true);
$show_compare = aqualuxe_get_option('product_show_compare', true);

// Add custom classes
$classes = array('aqualuxe-product', 'card-style-' . $product_card_style);
?>

<li <?php wc_product_class($classes, $product); ?>>
    <div class="product-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="product-thumbnail">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>

            <?php if ($show_quick_view || $show_wishlist || $show_compare) : ?>
                <div class="product-actions">
                    <?php if ($show_quick_view) : ?>
                        <a href="#" class="quick-view" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                            <span class="quick-view-icon"></span>
                            <span class="screen-reader-text"><?php esc_html_e('Quick View', 'aqualuxe'); ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if ($show_wishlist && function_exists('aqualuxe_wishlist_button')) : ?>
                        <?php aqualuxe_wishlist_button(); ?>
                    <?php endif; ?>

                    <?php if ($show_compare && function_exists('aqualuxe_compare_button')) : ?>
                        <?php aqualuxe_compare_button(); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-details">
            <?php if ($show_category) : ?>
                <div class="product-category">
                    <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="product-category-list">', '</span>'); ?>
                </div>
            <?php endif; ?>

            <div class="product-title-wrap">
                <?php
                /**
                 * Hook: woocommerce_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_product_title - 10
                 */
                do_action('woocommerce_shop_loop_item_title');
                ?>
            </div>

            <?php if ($show_rating) : ?>
                <div class="product-rating">
                    <?php woocommerce_template_loop_rating(); ?>
                </div>
            <?php endif; ?>

            <?php if ($show_price) : ?>
                <div class="product-price">
                    <?php
                    /**
                     * Hook: woocommerce_after_shop_loop_item_title.
                     *
                     * @hooked woocommerce_template_loop_price - 10
                     * @hooked woocommerce_template_loop_rating - 5
                     */
                    do_action('woocommerce_after_shop_loop_item_title');
                    ?>
                </div>
            <?php endif; ?>

            <div class="product-buttons">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action('woocommerce_after_shop_loop_item');
                ?>
            </div>
        </div>
    </div>
</li>