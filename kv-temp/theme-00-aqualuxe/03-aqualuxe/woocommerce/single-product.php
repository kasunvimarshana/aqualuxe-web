<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (removed)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<div class="single-product-container">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

            <div class="product-images">
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

            <div class="product-summary">
                <div class="summary entry-summary">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 15
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 25
                     * @hooked aqualuxe_product_meta_custom - 30
                     * @hooked woocommerce_template_single_sharing - 35
                     */
                    do_action('woocommerce_single_product_summary');
                    ?>
                </div>
            </div>

        </div>

        <div class="product-tabs-section">
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

    <?php endwhile; // end of the loop. ?>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (removed)
 */
do_action('woocommerce_after_main_content');
?>

<?php
get_footer('shop');
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */