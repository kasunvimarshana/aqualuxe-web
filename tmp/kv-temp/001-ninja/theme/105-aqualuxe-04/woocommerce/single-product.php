<?php
/**
 * Single Product Template
 * 
 * Custom single product layout for AquaLuxe theme
 * This template is called when viewing a single product
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop'); ?>

<div class="woocommerce-single-product-wrapper">
    <div class="container mx-auto px-4 py-8">
        
        <?php
        /**
         * Hook: woocommerce_before_single_product
         *
         * @hooked woocommerce_output_all_notices - 10
         */
        do_action('woocommerce_before_single_product');

        if (post_password_required()) {
            echo get_the_password_form(); // WPCS: XSS ok.
            return;
        }
        ?>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-layout', $product); ?>>
            
            <div class="product-content-grid grid lg:grid-cols-2 gap-8 xl:gap-12">
                
                <!-- Product Images -->
                <div class="product-images-section">
                    <?php
                    /**
                     * Hook: woocommerce_before_single_product_summary
                     *
                     * @hooked woocommerce_show_product_sale_flash - 10
                     * @hooked woocommerce_show_product_images - 20
                     */
                    do_action('woocommerce_before_single_product_summary');
                    ?>
                </div>

                <!-- Product Details -->
                <div class="product-details-section">
                    <div class="summary entry-summary">
                        <?php
                        /**
                         * Hook: woocommerce_single_product_summary
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         */
                        do_action('woocommerce_single_product_summary');
                        ?>
                    </div>
                </div>

            </div>

            <!-- Product Tabs and Related Products -->
            <div class="product-extended-content mt-12">
                <?php
                /**
                 * Hook: woocommerce_after_single_product_summary
                 *
                 * @hooked woocommerce_output_product_data_tabs - 10
                 * @hooked woocommerce_upsell_display - 15
                 * @hooked woocommerce_output_related_products - 20
                 */
                do_action('woocommerce_after_single_product_summary');
                ?>
            </div>

        </div>

        <?php do_action('woocommerce_after_single_product'); ?>

    </div>
</div>

<?php get_footer('shop');