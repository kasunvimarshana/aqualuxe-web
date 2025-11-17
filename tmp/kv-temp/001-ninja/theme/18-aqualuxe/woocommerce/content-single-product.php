<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
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
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('p-6 md:p-8', $product); ?>>
    <div class="flex flex-col lg:flex-row -mx-4">
        <div class="product-gallery px-4 w-full lg:w-1/2 mb-8 lg:mb-0">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action('woocommerce_before_single_product_summary');
            ?>
        </div>

        <div class="product-summary px-4 w-full lg:w-1/2">
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
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action('woocommerce_single_product_summary');
                ?>

                <?php if (function_exists('aqualuxe_product_extra_buttons')) : ?>
                    <div class="product-extra-buttons mt-6 flex flex-wrap gap-3">
                        <?php aqualuxe_product_extra_buttons(); ?>
                    </div>
                <?php endif; ?>

                <?php if (function_exists('aqualuxe_product_delivery_info')) : ?>
                    <div class="product-delivery-info mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <?php aqualuxe_product_delivery_info(); ?>
                    </div>
                <?php endif; ?>

                <?php if (function_exists('aqualuxe_product_trust_badges')) : ?>
                    <div class="product-trust-badges mt-8">
                        <?php aqualuxe_product_trust_badges(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="product-details mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
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

    <?php if (function_exists('aqualuxe_product_faq_section')) : ?>
        <div class="product-faq mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <?php aqualuxe_product_faq_section(); ?>
        </div>
    <?php endif; ?>

    <?php if (function_exists('aqualuxe_product_reviews_section') && comments_open()) : ?>
        <div class="product-reviews mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <?php aqualuxe_product_reviews_section(); ?>
        </div>
    <?php endif; ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>