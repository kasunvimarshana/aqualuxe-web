<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

// Check if it's a variable product
$is_variable = $product->is_type('variable');

// Get product categories
$categories = wc_get_product_category_list($product->get_id());

// Get product tags
$tags = wc_get_product_tag_list($product->get_id());

// Get product image
$image = $product->get_image('woocommerce_thumbnail');

// Get product title
$title = $product->get_name();

// Get product price
$price = $product->get_price_html();

// Get product rating
$rating = wc_get_rating_html($product->get_average_rating());

// Get product permalink
$permalink = $product->get_permalink();

// Get product SKU
$sku = $product->get_sku();

// Get product stock status
$stock_status = $product->get_stock_status();

// Get product stock quantity
$stock_quantity = $product->get_stock_quantity();

// Get product description
$description = $product->get_short_description();

// Get product add to cart URL
$add_to_cart_url = $product->add_to_cart_url();

// Get product add to cart text
$add_to_cart_text = $product->add_to_cart_text();

// Get product type
$product_type = $product->get_type();

// Get product ID
$product_id = $product->get_id();
?>
<li <?php wc_product_class('product-item', $product); ?>>
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
         */
        do_action('woocommerce_before_shop_loop_item_title');
        ?>
    </div>

    <div class="product-content">
        <?php
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
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        do_action('woocommerce_after_shop_loop_item');
        ?>
    </div>
</li>