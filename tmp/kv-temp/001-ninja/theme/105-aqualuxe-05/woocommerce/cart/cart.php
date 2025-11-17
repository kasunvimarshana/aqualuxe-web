<?php
/**
 * Cart Page Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="cart-page-wrapper">
    
    <!-- Cart Header -->
    <div class="cart-header mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            <?php esc_html_e('Shopping Cart', 'aqualuxe'); ?>
        </h1>
        
        <?php if (!WC()->cart->is_empty()) : ?>
            <p class="text-gray-600 dark:text-gray-400">
                <?php
                printf(
                    _n(
                        'You have %d item in your cart',
                        'You have %d items in your cart',
                        WC()->cart->get_cart_contents_count(),
                        'aqualuxe'
                    ),
                    WC()->cart->get_cart_contents_count()
                );
                ?>
            </p>
        <?php endif; ?>
    </div>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <?php if (!WC()->cart->is_empty()) : ?>
            
            <!-- Cart Table -->
            <div class="cart-table-wrapper bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="product-remove sr-only">&nbsp;</th>
                            <th class="product-thumbnail sr-only">&nbsp;</th>
                            <th class="product-name py-4 px-6 text-left text-sm font-medium text-gray-900 dark:text-white">
                                <?php esc_html_e('Product', 'aqualuxe'); ?>
                            </th>
                            <th class="product-price py-4 px-6 text-left text-sm font-medium text-gray-900 dark:text-white">
                                <?php esc_html_e('Price', 'aqualuxe'); ?>
                            </th>
                            <th class="product-quantity py-4 px-6 text-center text-sm font-medium text-gray-900 dark:text-white">
                                <?php esc_html_e('Quantity', 'aqualuxe'); ?>
                            </th>
                            <th class="product-subtotal py-4 px-6 text-right text-sm font-medium text-gray-900 dark:text-white">
                                <?php esc_html_e('Subtotal', 'aqualuxe'); ?>
                            </th>
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
                                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    
                                    <!-- Remove Button -->
                                    <td class="product-remove py-4 px-6">
                                        <?php
                                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove text-red-600 hover:text-red-700 transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s" title="%s">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </a>',
                                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                esc_html__('Remove this item', 'aqualuxe'),
                                                esc_attr($product_id),
                                                esc_attr($_product->get_sku()),
                                                esc_html__('Remove this item', 'aqualuxe')
                                            ),
                                            $cart_item_key
                                        );
                                        ?>
                                    </td>

                                    <!-- Product Image -->
                                    <td class="product-thumbnail py-4">
                                        <?php
                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail', array('class' => 'w-16 h-16 object-cover rounded-lg')), $cart_item, $cart_item_key);

                                        if (!$product_permalink) {
                                            echo $thumbnail; // PHPCS: XSS ok.
                                        } else {
                                            printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                        }
                                        ?>
                                    </td>

                                    <!-- Product Name -->
                                    <td class="product-name py-4 px-6" data-title="<?php esc_attr_e('Product', 'aqualuxe'); ?>">
                                        <div class="product-info">
                                            <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="font-medium text-gray-900 dark:text-white hover:text-primary-600 transition-colors">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                            }

                                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                            // Meta data.
                                            echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                            // Backorder notification.
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 dark:text-amber-400 mt-1">' . esc_html__('Available on backorder', 'aqualuxe') . '</p>', $product_id));
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <!-- Price -->
                                    <td class="product-price py-4 px-6" data-title="<?php esc_attr_e('Price', 'aqualuxe'); ?>">
                                        <div class="text-gray-900 dark:text-white font-medium">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                    </td>

                                    <!-- Quantity -->
                                    <td class="product-quantity py-4 px-6 text-center" data-title="<?php esc_attr_e('Quantity', 'aqualuxe'); ?>">
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

                                    <!-- Subtotal -->
                                    <td class="product-subtotal py-4 px-6 text-right" data-title="<?php esc_attr_e('Subtotal', 'aqualuxe'); ?>">
                                        <div class="text-gray-900 dark:text-white font-semibold">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action('woocommerce_cart_contents'); ?>

                        <!-- Cart Actions Row -->
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td colspan="6" class="actions py-4 px-6">
                                
                                <?php if (wc_coupons_enabled()) : ?>
                                    <div class="coupon flex flex-wrap items-center space-x-4 mb-4 lg:mb-0">
                                        <label for="coupon_code" class="sr-only"><?php esc_html_e('Coupon:', 'aqualuxe'); ?></label>
                                        <input type="text" name="coupon_code" class="input-text flex-1 max-w-xs px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" />
                                        <button type="submit" class="button btn btn-secondary" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>">
                                            <?php esc_html_e('Apply coupon', 'aqualuxe'); ?>
                                        </button>
                                        <?php do_action('woocommerce_cart_coupon'); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="cart-update-actions flex items-center space-x-4">
                                    <button type="submit" class="button btn btn-outline" name="update_cart" value="<?php esc_attr_e('Update cart', 'aqualuxe'); ?>">
                                        <?php esc_html_e('Update cart', 'aqualuxe'); ?>
                                    </button>

                                    <?php do_action('woocommerce_cart_actions'); ?>

                                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                </div>
                            </td>
                        </tr>

                        <?php do_action('woocommerce_after_cart_contents'); ?>
                    </tbody>
                </table>
            </div>

        <?php else : ?>
            
            <!-- Empty Cart -->
            <div class="cart-empty text-center py-12">
                <div class="empty-cart-icon mb-6">
                    <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0H17m-7.5 0v.01M19.5 18v.01"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">
                    <?php esc_html_e('Your cart is currently empty', 'aqualuxe'); ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    <?php esc_html_e('Discover our exclusive collection of luxury aquatic products and accessories.', 'aqualuxe'); ?>
                </p>
                <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="btn btn-primary">
                    <?php esc_html_e('Return to shop', 'aqualuxe'); ?>
                </a>
            </div>

        <?php endif; ?>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <?php if (!WC()->cart->is_empty()) : ?>
        
        <!-- Cart Collaterals -->
        <div class="cart-collaterals lg:flex lg:space-x-8">
            
            <!-- Continue Shopping -->
            <div class="continue-shopping lg:w-1/2 mb-8 lg:mb-0">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <?php esc_html_e('Continue Shopping', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        <?php esc_html_e('Explore more products from our luxury aquatic collection.', 'aqualuxe'); ?>
                    </p>
                    <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="btn btn-outline">
                        <?php esc_html_e('Browse Products', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>

            <!-- Cart Totals -->
            <div class="cart-totals-wrapper lg:w-1/2">
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

    <?php endif; ?>

</div>

<?php do_action('woocommerce_after_cart'); ?>