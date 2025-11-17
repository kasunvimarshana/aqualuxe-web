<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="cart-wrapper bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden p-6 md:p-8">
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
            <div class="cart-header hidden md:flex border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <div class="w-1/2 font-bold"><?php esc_html_e('Product', 'aqualuxe'); ?></div>
                <div class="w-1/6 text-center font-bold"><?php esc_html_e('Price', 'aqualuxe'); ?></div>
                <div class="w-1/6 text-center font-bold"><?php esc_html_e('Quantity', 'aqualuxe'); ?></div>
                <div class="w-1/6 text-right font-bold"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></div>
            </div>

            <?php do_action('woocommerce_before_cart_contents'); ?>

            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                    <div class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> flex flex-wrap items-center py-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <div class="product-info flex items-center w-full md:w-1/2 mb-4 md:mb-0">
                            <div class="product-remove mr-4">
                                <?php
                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove text-red-500 hover:text-red-700 transition-colors duration-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                            </div>

                            <div class="product-thumbnail mr-4">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', ['class' => 'w-16 h-16 object-cover rounded-md']), $cart_item, $cart_item_key);

                                if (!$product_permalink) {
                                    echo $thumbnail; // PHPCS: XSS ok.
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                }
                                ?>
                            </div>

                            <div class="product-name" data-title="<?php esc_attr_e('Product', 'aqualuxe'); ?>">
                                <?php
                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="font-medium hover:text-primary transition-colors duration-300">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                }

                                do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                // Meta data.
                                echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                // Backorder notification.
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 mt-1">' . esc_html__('Available on backorder', 'aqualuxe') . '</p>', $product_id));
                                }
                                ?>
                            </div>
                        </div>

                        <div class="product-price w-1/2 md:w-1/6 text-right md:text-center mb-2 md:mb-0" data-title="<?php esc_attr_e('Price', 'aqualuxe'); ?>">
                            <span class="md:hidden font-medium mr-2"><?php esc_html_e('Price:', 'aqualuxe'); ?></span>
                            <?php
                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                            ?>
                        </div>

                        <div class="product-quantity w-1/2 md:w-1/6 text-right md:text-center mb-2 md:mb-0" data-title="<?php esc_attr_e('Quantity', 'aqualuxe'); ?>">
                            <span class="md:hidden font-medium mr-2"><?php esc_html_e('Quantity:', 'aqualuxe'); ?></span>
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
                                        'classes'      => apply_filters('woocommerce_quantity_input_classes', array('input-text', 'qty', 'text', 'w-16', 'rounded-md', 'border-gray-300', 'dark:border-gray-600', 'dark:bg-gray-700', 'focus:ring-primary', 'focus:border-primary'), $product),
                                    ),
                                    $_product,
                                    false
                                );
                            }

                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                            ?>
                        </div>

                        <div class="product-subtotal w-full md:w-1/6 text-right font-medium" data-title="<?php esc_attr_e('Subtotal', 'aqualuxe'); ?>">
                            <span class="md:hidden font-medium mr-2"><?php esc_html_e('Subtotal:', 'aqualuxe'); ?></span>
                            <?php
                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>

            <?php do_action('woocommerce_cart_contents'); ?>

            <div class="actions flex flex-wrap justify-between items-center pt-6">
                <?php if (wc_coupons_enabled()) { ?>
                    <div class="coupon flex flex-wrap md:flex-nowrap mb-4 md:mb-0 w-full md:w-auto">
                        <input type="text" name="coupon_code" class="input-text w-full md:w-auto mb-2 md:mb-0 md:mr-2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" />
                        <button type="submit" class="button w-full md:w-auto bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-md transition-colors duration-300" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>"><?php esc_html_e('Apply coupon', 'aqualuxe'); ?></button>
                        <?php do_action('woocommerce_cart_coupon'); ?>
                    </div>
                <?php } ?>

                <button type="submit" class="button w-full md:w-auto bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 px-6 py-2 rounded-md transition-colors duration-300" name="update_cart" value="<?php esc_attr_e('Update cart', 'aqualuxe'); ?>"><?php esc_html_e('Update cart', 'aqualuxe'); ?></button>

                <?php do_action('woocommerce_cart_actions'); ?>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </div>

            <?php do_action('woocommerce_after_cart_contents'); ?>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <?php do_action('woocommerce_before_cart_collaterals'); ?>

    <div class="cart-collaterals mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
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

<?php do_action('woocommerce_after_cart'); ?>