<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

// Get product layout style
$product_layout = get_theme_mod('woocommerce_product_layout', 'default');

// Add product layout class
$product_classes = array('product-layout', 'product-layout--' . $product_layout);

?>
<div class="<?php echo esc_attr(implode(' ', $product_classes)); ?>">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>

        <?php
        /**
         * Hook: aqualuxe_before_single_product.
         *
         * @hooked aqualuxe_product_navigation - 10 (added in woocommerce-setup.php)
         */
        do_action('aqualuxe_before_single_product');
        ?>

        <?php wc_get_template_part('content', 'single-product'); ?>

    <?php endwhile; // end of the loop. ?>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');

/**
 * Hook: aqualuxe_after_single_product.
 *
 * @hooked aqualuxe_recently_viewed_products - 10 (added in woocommerce-setup.php)
 * @hooked aqualuxe_related_products - 20
 * @hooked aqualuxe_upsell_products - 30
 */
do_action('aqualuxe_after_single_product');

get_footer('shop');