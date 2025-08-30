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
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-single', $product); ?>>
    <div class="product-single__inner">
        <div class="product-single__gallery-wrapper">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action('woocommerce_before_single_product_summary');
            ?>

            <?php
            /**
             * Hook: aqualuxe_after_product_gallery.
             *
             * @hooked aqualuxe_product_video - 10
             * @hooked aqualuxe_product_360_view - 20
             */
            do_action('aqualuxe_after_product_gallery');
            ?>
        </div>

        <div class="product-single__summary-wrapper">
            <div class="summary entry-summary product-single__summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action('woocommerce_single_product_summary');
                ?>

                <?php
                /**
                 * Hook: aqualuxe_after_product_summary.
                 *
                 * @hooked aqualuxe_product_countdown - 10
                 * @hooked aqualuxe_product_stock_progress - 20
                 * @hooked aqualuxe_product_shipping_info - 30
                 * @hooked aqualuxe_product_guarantee - 40
                 */
                do_action('aqualuxe_after_product_summary');
                ?>
            </div>
        </div>
    </div>

    <div class="product-single__tabs-wrapper">
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

    <?php
    /**
     * Hook: aqualuxe_single_product_additional_content.
     *
     * @hooked aqualuxe_product_features - 10
     * @hooked aqualuxe_product_specifications - 20
     * @hooked aqualuxe_product_faq - 30
     */
    do_action('aqualuxe_single_product_additional_content');
    ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>