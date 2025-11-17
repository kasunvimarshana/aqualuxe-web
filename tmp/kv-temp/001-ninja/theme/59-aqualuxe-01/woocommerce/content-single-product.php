<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

// Get product layout style
$product_layout = get_theme_mod('woocommerce_product_layout', 'default');

// Add product layout class
$product_classes = array('product-single', 'product-single--' . $product_layout);

// Add classes for product features
if ($product->is_featured()) {
    $product_classes[] = 'product-single--featured';
}

if ($product->is_on_sale()) {
    $product_classes[] = 'product-single--sale';
}

if (!$product->is_in_stock()) {
    $product_classes[] = 'product-single--out-of-stock';
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class($product_classes, $product); ?>>
    <div class="product-single__row">
        <div class="product-single__gallery">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             * @hooked aqualuxe_product_countdown - 30 (added in woocommerce-setup.php)
             */
            do_action('woocommerce_before_single_product_summary');
            ?>
        </div>

        <div class="product-single__summary">
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked aqualuxe_product_meta - 40 (added in woocommerce-setup.php)
                 * @hooked aqualuxe_product_share - 50 (added in woocommerce-setup.php)
                 * @hooked aqualuxe_product_trust_badges - 55 (added in woocommerce-setup.php)
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action('woocommerce_single_product_summary');
                ?>
            </div>
        </div>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');
    ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>