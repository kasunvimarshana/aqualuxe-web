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

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

// Get product card style
$product_card_style = get_theme_mod('woocommerce_product_card_style', 'default');

// Extra classes
$classes = array('product-card', 'product-card--' . $product_card_style);

// Add hover effect class
if (get_theme_mod('woocommerce_product_card_hover', true)) {
    $classes[] = 'product-card--hover';
}

// Add classes for product features
if ($product->is_featured()) {
    $classes[] = 'product-card--featured';
}

if ($product->is_on_sale()) {
    $classes[] = 'product-card--sale';
}

if (!$product->is_in_stock()) {
    $classes[] = 'product-card--out-of-stock';
}

if ($product->get_average_rating() >= 4.5) {
    $classes[] = 'product-card--top-rated';
}
?>

<li <?php wc_product_class($classes, $product); ?>>
    <div class="product-card__inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="product-card__thumbnail">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-card__thumbnail-link">
                <?php
                /**
                 * Hook: woocommerce_before_shop_loop_item_title.
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                 */
                do_action('woocommerce_before_shop_loop_item_title');
                ?>

                <?php if (get_theme_mod('woocommerce_product_card_second_image', true)) : ?>
                    <?php
                    // Get gallery images
                    $attachment_ids = $product->get_gallery_image_ids();
                    if (!empty($attachment_ids)) {
                        echo wp_get_attachment_image($attachment_ids[0], 'woocommerce_thumbnail', false, array('class' => 'product-card__thumbnail-hover'));
                    }
                    ?>
                <?php endif; ?>
            </a>

            <?php if (get_theme_mod('woocommerce_product_card_quick_actions', true)) : ?>
                <div class="product-card__actions">
                    <?php
                    /**
                     * Hook: aqualuxe_product_card_actions.
                     *
                     * @hooked aqualuxe_quick_view_button - 10
                     * @hooked aqualuxe_wishlist_button - 20
                     * @hooked aqualuxe_compare_button - 30
                     */
                    do_action('aqualuxe_product_card_actions');
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-card__content">
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             * @hooked aqualuxe_show_product_categories - 5 (added in woocommerce-setup.php)
             */
            do_action('woocommerce_shop_loop_item_title');
            ?>

            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             * @hooked aqualuxe_show_product_excerpt - 15 (added in woocommerce-setup.php)
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>
        </div>

        <div class="product-card__footer">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_product_link_close - 5
             * @hooked woocommerce_template_loop_add_to_cart - 10
             * @hooked aqualuxe_view_product_button - 15 (added in woocommerce-setup.php)
             * @hooked aqualuxe_quick_view_button - 20 (added in woocommerce-setup.php)
             * @hooked aqualuxe_wishlist_button - 25 (added in woocommerce-setup.php)
             * @hooked aqualuxe_compare_button - 30 (added in woocommerce-setup.php)
             */
            do_action('woocommerce_after_shop_loop_item');
            ?>
        </div>
    </div>
</li>