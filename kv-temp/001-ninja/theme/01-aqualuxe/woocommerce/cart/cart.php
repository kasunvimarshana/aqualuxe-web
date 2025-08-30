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

// Get cart display options
$enable_cross_sells = get_theme_mod('aqualuxe_enable_cart_cross_sells', true);
$enable_coupon = get_theme_mod('aqualuxe_enable_cart_coupon', true);
$enable_shipping_calculator = get_theme_mod('aqualuxe_enable_shipping_calculator', true);
$show_product_image = get_theme_mod('aqualuxe_show_cart_product_image', true);
$show_product_price = get_theme_mod('aqualuxe_show_cart_product_price', true);
$show_product_quantity = get_theme_mod('aqualuxe_show_cart_product_quantity', true);
$show_product_subtotal = get_theme_mod('aqualuxe_show_cart_product_subtotal', true);
$show_product_remove = get_theme_mod('aqualuxe_show_cart_product_remove', true);

do_action('woocommerce_before_cart');
?>

<div class="woocommerce-cart-wrapper">
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <div class="cart-items bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full" cellspacing="0">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <?php if ($show_product_image) : ?>
                                <th class="product-thumbnail py-4 px-4 text-left">&nbsp;</th>
                            <?php endif; ?>
                            <th class="product-name py-4 px-4 text-left"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                            <?php if ($show_product_price) : ?>
                                <th class="product-price py-4 px-4 text-left"><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                            <?php endif; ?>
                            <?php if ($show_product_quantity) : ?>
                                <th class="product-quantity py-4 px-4 text-left"><?php esc_html_e('Quantity', 'aqualuxe'); ?></th>
                            <?php endif; ?>
                            <?php if ($show_product_subtotal) : ?>
                                <th class="product-subtotal py-4 px-4 text-left"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></th>
                            <?php endif; ?>
                            <?php if ($show_product_remove) : ?>
                                <th class="product-remove py-4 px-4 text-left">&nbsp;</th>
                            <?php endif; ?>
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

                                    <?php if ($show_product_image) : ?>
                                    <td class="product-thumbnail py-4 px-4">
                                        <?php
                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', array('class' => 'w-20 h-auto rounded')), $cart_item, $cart_item_key);

                                        if (!$product_permalink) {
                                            echo $thumbnail; // PHPCS: XSS ok.
                                        } else {
                                            printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                        }
                                        ?>
                                    </td>
                                    <?php endif; ?>

                                    <td class="product-name py-4 px-4" data-title="<?php esc_attr_e('Product', 'aqualuxe'); ?>">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="font-medium hover:text-primary-600 dark:hover:text-primary-400">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }

                                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                        // Meta data.
                                        echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                        // Backorder notification.
                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-sm text-amber-600 dark:text-amber-400 mt-1">' . esc_html__('Available on backorder', 'aqualuxe') . '</p>', $product_id));
                                        }
                                        ?>
                                    </td>

                                    <?php if ($show_product_price) : ?>
                                    <td class="product-price py-4 px-4" data-title="<?php esc_attr_e('Price', 'aqualuxe'); ?>">
                                        <?php
                                        echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                        ?>
                                    </td>
                                    <?php endif; ?>

                                    <?php if ($show_product_quantity) : ?>
                                    <td class="product-quantity py-4 px-4" data-title="<?php esc_attr_e('Quantity', 'aqualuxe'); ?>">
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
                                                    'classes'      => apply_filters('woocommerce_quantity_input_classes', array('form-control', 'w-20', 'text-center', 'border', 'border-gray-300', 'dark:border-gray-600', 'rounded', 'py-1', 'px-2', 'dark:bg-gray-700', 'dark:text-white'), $_product),
                                                ),
                                                $_product,
                                                false
                                            );
                                        }

                                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                        ?>
                                    </td>
                                    <?php endif; ?>

                                    <?php if ($show_product_subtotal) : ?>
                                    <td class="product-subtotal py-4 px-4 font-medium text-primary-600 dark:text-primary-400" data-title="<?php esc_attr_e('Subtotal', 'aqualuxe'); ?>">
                                        <?php
                                        echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                        ?>
                                    </td>
                                    <?php endif; ?>

                                    <?php if ($show_product_remove) : ?>
                                    <td class="product-remove py-4 px-4 text-center">
                                        <?php
                                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                                    <?php endif; ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action('woocommerce_cart_contents'); ?>

                        <tr>
                            <td colspan="<?php echo esc_attr($show_product_image + 1 + $show_product_price + $show_product_quantity + $show_product_subtotal + $show_product_remove); ?>" class="actions py-4 px-4">
                                <div class="flex flex-wrap items-center justify-between">
                                    <?php if ($enable_coupon && wc_coupons_enabled()) : ?>
                                        <div class="coupon mb-4 md:mb-0">
                                            <label for="coupon_code" class="sr-only"><?php esc_html_e('Coupon:', 'aqualuxe'); ?></label>
                                            <div class="flex">
                                                <input type="text" name="coupon_code" class="input-text border border-gray-300 dark:border-gray-600 rounded-l-md py-2 px-4 dark:bg-gray-700 dark:text-white" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" />
                                                <button type="submit" class="button bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white py-2 px-4 rounded-r-md transition-colors" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>"><?php esc_html_e('Apply', 'aqualuxe'); ?></button>
                                            </div>
                                            <?php do_action('woocommerce_cart_coupon'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <button type="submit" class="button bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md transition-colors" name="update_cart" value="<?php esc_attr_e('Update cart', 'aqualuxe'); ?>"><?php esc_html_e('Update cart', 'aqualuxe'); ?></button>

                                    <?php do_action('woocommerce_cart_actions'); ?>

                                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                </div>
                            </td>
                        </tr>

                        <?php do_action('woocommerce_after_cart_contents'); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <div class="cart-collaterals">
        <div class="grid md:grid-cols-2 gap-8">
            <?php if ($enable_cross_sells) : ?>
                <div class="cross-sells">
                    <?php
                    /**
                     * Cart collaterals hook.
                     *
                     * @hooked woocommerce_cross_sell_display
                     */
                    do_action('woocommerce_cart_collaterals');
                    ?>
                </div>
            <?php endif; ?>

            <div class="cart-totals <?php echo !$enable_cross_sells ? 'md:col-start-2' : ''; ?>">
                <?php woocommerce_cart_totals(); ?>

                <div class="wc-proceed-to-checkout mt-6">
                    <?php do_action('woocommerce_proceed_to_checkout'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($enable_shipping_calculator) : ?>
        <?php do_action('woocommerce_before_shipping_calculator'); ?>

        <div class="shipping-calculator-wrapper bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6 mt-8">
            <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Calculate Shipping', 'aqualuxe'); ?></h3>
            <?php woocommerce_shipping_calculator(); ?>
        </div>

        <?php do_action('woocommerce_after_shipping_calculator'); ?>
    <?php endif; ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>