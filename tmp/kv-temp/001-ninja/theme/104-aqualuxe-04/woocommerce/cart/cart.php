<?php
/**
 * WooCommerce Cart Template
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <div class="woocommerce-cart max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Page Header -->
            <header class="cart-header mb-8">
                <h1 class="cart-title text-3xl font-bold text-gray-900 dark:text-white">
                    <?php esc_html_e('Shopping Cart', 'aqualuxe'); ?>
                </h1>
            </header>
            
            <?php do_action('woocommerce_before_cart'); ?>
            
            <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                
                <?php do_action('woocommerce_before_cart_table'); ?>
                
                <div class="cart-content grid lg:grid-cols-3 gap-8">
                    
                    <!-- Cart Items -->
                    <div class="cart-items lg:col-span-2">
                        
                        <div class="cart-table bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            
                            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="product-thumbnail text-left p-4"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                                        <th class="product-name text-left p-4"><?php esc_html_e('Details', 'aqualuxe'); ?></th>
                                        <th class="product-price text-left p-4"><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                                        <th class="product-quantity text-center p-4"><?php esc_html_e('Quantity', 'aqualuxe'); ?></th>
                                        <th class="product-subtotal text-right p-4"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></th>
                                        <th class="product-remove text-center p-4">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php do_action('woocommerce_before_cart_contents'); ?>

                                    <?php
                                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                            ?>
                                            <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> border-b border-gray-200 dark:border-gray-600">

                                                <!-- Product Thumbnail -->
                                                <td class="product-thumbnail p-4" data-title="<?php esc_attr_e('Product', 'aqualuxe'); ?>">
                                                    <?php
                                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('aqualuxe-thumbnail'), $cart_item, $cart_item_key);

                                                    if (!$product_permalink) {
                                                        echo $thumbnail; // PHPCS: XSS ok.
                                                    } else {
                                                        printf('<a href="%s" class="block">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Product Name -->
                                                <td class="product-name p-4" data-title="<?php esc_attr_e('Product', 'aqualuxe'); ?>">
                                                    <div class="product-details">
                                                        <?php
                                                        if (!$product_permalink) {
                                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                                        } else {
                                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="font-medium text-gray-900 dark:text-white hover:text-primary-600">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                        }

                                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                                        // Meta data.
                                                        echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                                        // Backorder notification.
                                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-yellow-600">' . esc_html__('Available on backorder', 'aqualuxe') . '</p>', $product_id));
                                                        }
                                                        ?>
                                                    </div>
                                                </td>

                                                <!-- Product Price -->
                                                <td class="product-price p-4" data-title="<?php esc_attr_e('Price', 'aqualuxe'); ?>">
                                                    <span class="font-medium text-gray-900 dark:text-white">
                                                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok. ?>
                                                    </span>
                                                </td>

                                                <!-- Product Quantity -->
                                                <td class="product-quantity p-4" data-title="<?php esc_attr_e('Quantity', 'aqualuxe'); ?>">
                                                    <?php
                                                    if ($_product->is_sold_individually()) {
                                                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                                    } else {
                                                        $product_quantity = woocommerce_quantity_input(
                                                            array(
                                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                                'input_value'  => $cart_item['quantity'],
                                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                                'min_value'    => '0',
                                                                'product_name' => $_product->get_name(),
                                                            ),
                                                            $_product,
                                                            false
                                                        );
                                                    }

                                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                                    ?>
                                                </td>

                                                <!-- Product Subtotal -->
                                                <td class="product-subtotal p-4" data-title="<?php esc_attr_e('Subtotal', 'aqualuxe'); ?>">
                                                    <span class="font-semibold text-lg text-gray-900 dark:text-white">
                                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok. ?>
                                                    </span>
                                                </td>

                                                <!-- Product Remove -->
                                                <td class="product-remove p-4">
                                                    <?php
                                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                        'woocommerce_cart_item_remove_link',
                                                        sprintf(
                                                            '<a href="%s" class="remove text-red-600 hover:text-red-800 p-2" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </a>',
                                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                            esc_html__('Remove this item', 'aqualuxe'),
                                                            esc_attr($product_id),
                                                            esc_attr($_product->get_sku())
                                                        ),
                                                        $cart_item_key
                                                    );
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    <?php do_action('woocommerce_cart_contents'); ?>

                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <td colspan="6" class="actions p-4">
                                            
                                            <?php if (wc_coupons_enabled()) : ?>
                                                <div class="coupon flex flex-col sm:flex-row gap-4 items-end mb-4">
                                                    <div class="flex-1">
                                                        <label for="coupon_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            <?php esc_html_e('Coupon:', 'aqualuxe'); ?>
                                                        </label>
                                                        <input type="text" name="coupon_code" class="coupon_code w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" />
                                                    </div>
                                                    <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>">
                                                        <?php esc_html_e('Apply coupon', 'aqualuxe'); ?>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="update-cart">
                                                <button type="submit" class="button secondary" name="update_cart" value="<?php esc_attr_e('Update cart', 'aqualuxe'); ?>">
                                                    <?php esc_html_e('Update cart', 'aqualuxe'); ?>
                                                </button>
                                            </div>
                                            
                                            <?php do_action('woocommerce_cart_actions'); ?>
                                            <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                        </td>
                                    </tr>

                                    <?php do_action('woocommerce_after_cart_contents'); ?>
                                </tbody>
                            </table>
                            
                        </div>
                        
                    </div>
                    
                    <!-- Cart Totals -->
                    <div class="cart-collaterals lg:col-span-1">
                        <div class="cart-totals bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <?php
                            /**
                             * Cart collaterals hook.
                             *
                             * @hooked woocommerce_cross_sell_display
                             * @hooked woocommerce_cart_totals - 10
                             */
                            do_action('woocommerce_cart_collaterals');
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <?php do_action('woocommerce_after_cart_table'); ?>
                
            </form>
            
            <?php do_action('woocommerce_after_cart'); ?>
            
        </div>
        
    </main>
</div>

<?php get_footer(); ?>