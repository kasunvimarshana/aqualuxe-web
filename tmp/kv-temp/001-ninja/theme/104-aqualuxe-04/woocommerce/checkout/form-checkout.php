<?php
/**
 * WooCommerce Checkout Template
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <div class="woocommerce-checkout max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Page Header -->
            <header class="checkout-header mb-8">
                <h1 class="checkout-title text-3xl font-bold text-gray-900 dark:text-white">
                    <?php esc_html_e('Checkout', 'aqualuxe'); ?>
                </h1>
                
                <!-- Progress Steps -->
                <div class="checkout-progress mt-6">
                    <div class="flex items-center justify-between max-w-md">
                        <div class="step completed">
                            <div class="step-circle bg-primary-600 text-white">1</div>
                            <div class="step-label text-sm"><?php esc_html_e('Cart', 'aqualuxe'); ?></div>
                        </div>
                        <div class="step-line bg-primary-600"></div>
                        <div class="step active">
                            <div class="step-circle bg-primary-600 text-white">2</div>
                            <div class="step-label text-sm"><?php esc_html_e('Checkout', 'aqualuxe'); ?></div>
                        </div>
                        <div class="step-line bg-gray-300"></div>
                        <div class="step">
                            <div class="step-circle bg-gray-300">3</div>
                            <div class="step-label text-sm"><?php esc_html_e('Complete', 'aqualuxe'); ?></div>
                        </div>
                    </div>
                </div>
            </header>
            
            <?php
            // If checkout registration is disabled and not logged in, the user cannot checkout.
            if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
                echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'aqualuxe')));
                return;
            }
            ?>

            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

                <?php if ($checkout->get_checkout_fields()) : ?>

                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div class="checkout-content grid lg:grid-cols-2 gap-8" id="customer_details">
                        
                        <!-- Billing & Shipping Forms -->
                        <div class="checkout-forms">
                            
                            <div class="billing-fields bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                    <?php esc_html_e('Billing Details', 'aqualuxe'); ?>
                                </h3>
                                <?php do_action('woocommerce_checkout_billing'); ?>
                            </div>

                            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                                <div class="shipping-fields bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                        <?php esc_html_e('Shipping Details', 'aqualuxe'); ?>
                                    </h3>
                                    <?php do_action('woocommerce_checkout_shipping'); ?>
                                </div>

                            <?php endif; ?>

                            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                            <?php if (isset($checkout->checkout_fields['order']) && !empty($checkout->checkout_fields['order'])) : ?>
                                <div class="order-notes bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                        <?php esc_html_e('Additional Information', 'aqualuxe'); ?>
                                    </h3>
                                    <?php foreach ($checkout->checkout_fields['order'] as $key => $field) : ?>
                                        <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                        </div>

                        <!-- Order Review -->
                        <div class="order-review">
                            
                            <div id="order_review" class="woocommerce-checkout-review-order bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-4">
                                
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                                    <?php esc_html_e('Your Order', 'aqualuxe'); ?>
                                </h3>
                                
                                <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
                                
                                <?php do_action('woocommerce_checkout_before_order_review'); ?>

                                <table class="shop_table woocommerce-checkout-review-order-table w-full">
                                    <thead class="border-b border-gray-200 dark:border-gray-600">
                                        <tr>
                                            <th class="product-name text-left py-3"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                                            <th class="product-total text-right py-3"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        do_action('woocommerce_review_order_before_cart_contents');

                                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                                ?>
                                                <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> border-b border-gray-100 dark:border-gray-700">
                                                    <td class="product-name py-3">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="flex-shrink-0">
                                                                <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key)); ?>
                                                            </div>
                                                            <div>
                                                                <h4 class="font-medium text-gray-900 dark:text-white">
                                                                    <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
                                                                </h4>
                                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                    <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                                </p>
                                                                <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="product-total text-right py-3">
                                                        <span class="font-semibold text-gray-900 dark:text-white">
                                                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }

                                        do_action('woocommerce_review_order_after_cart_contents');
                                        ?>
                                    </tbody>
                                    <tfoot class="border-t border-gray-200 dark:border-gray-600">

                                        <tr class="cart-subtotal">
                                            <th class="text-left py-3"><?php esc_html_e('Subtotal', 'aqualuxe'); ?></th>
                                            <td class="text-right py-3 font-semibold"><?php wc_cart_totals_subtotal_html(); ?></td>
                                        </tr>

                                        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                                <th class="text-left py-2"><?php wc_cart_totals_coupon_label($coupon); ?></th>
                                                <td class="text-right py-2"><?php wc_cart_totals_coupon_html($coupon); ?></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                                            <?php do_action('woocommerce_review_order_before_shipping'); ?>

                                            <?php wc_cart_totals_shipping_html(); ?>

                                            <?php do_action('woocommerce_review_order_after_shipping'); ?>

                                        <?php endif; ?>

                                        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                            <tr class="fee">
                                                <th class="text-left py-2"><?php echo esc_html($fee->name); ?></th>
                                                <td class="text-right py-2"><?php wc_cart_totals_fee_html($fee); ?></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                                                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                                                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                                                        <th class="text-left py-2"><?php echo esc_html($tax->label); ?></th>
                                                        <td class="text-right py-2"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr class="tax-total">
                                                    <th class="text-left py-2"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                                                    <td class="text-right py-2"><?php wc_cart_totals_taxes_total_html(); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php do_action('woocommerce_review_order_before_order_total'); ?>

                                        <tr class="order-total bg-gray-50 dark:bg-gray-700">
                                            <th class="text-left py-4 text-lg font-semibold"><?php esc_html_e('Total', 'aqualuxe'); ?></th>
                                            <td class="text-right py-4 text-xl font-bold text-primary-600"><?php wc_cart_totals_order_total_html(); ?></td>
                                        </tr>

                                        <?php do_action('woocommerce_review_order_after_order_total'); ?>

                                    </tfoot>
                                </table>

                                <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                                <div id="payment" class="woocommerce-checkout-payment mt-6">
                                    
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                        <?php esc_html_e('Payment Method', 'aqualuxe'); ?>
                                    </h4>
                                    
                                    <?php if (WC()->cart->needs_payment()) : ?>
                                        <ul class="wc_payment_methods payment_methods methods space-y-3">
                                            <?php
                                            if (!empty($available_gateways)) {
                                                foreach ($available_gateways as $gateway) {
                                                    wc_get_template('checkout/payment-method.php', array('gateway' => $gateway), '', WC()->template_path());
                                                }
                                            } else {
                                                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'aqualuxe') : esc_html__('Please fill in your details above to see available payment methods.', 'aqualuxe')) . '</li>'; // @codingStandardsIgnoreLine
                                            }
                                            ?>
                                        </ul>
                                    <?php endif; ?>
                                    
                                    <div class="form-row place-order mt-6">
                                        <noscript>
                                            <?php
                                            /* translators: $1 and $2 opening and closing emphasis tags respectively */
                                            printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'aqualuxe'), '<em>', '</em>');
                                            ?>
                                            <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'aqualuxe'); ?>"><?php esc_html_e('Update totals', 'aqualuxe'); ?></button>
                                        </noscript>

                                        <?php wc_get_template('checkout/terms.php'); ?>

                                        <?php do_action('woocommerce_review_order_before_submit'); ?>

                                        <button type="submit" class="button alt w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e('Place order', 'aqualuxe'); ?>" data-value="<?php esc_attr_e('Place order', 'aqualuxe'); ?>">
                                            <?php esc_html_e('Place order', 'aqualuxe'); ?>
                                        </button>

                                        <?php do_action('woocommerce_review_order_after_submit'); ?>

                                        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                                    </div>
                                    
                                </div>

                                <?php do_action('woocommerce_checkout_after_order_review'); ?>
                                
                            </div>
                            
                        </div>
                        
                    </div>

                <?php endif; ?>

            </form>

            <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
            
        </div>
        
    </main>
</div>

<?php get_footer(); ?>