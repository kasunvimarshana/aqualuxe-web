<?php
/**
 * Checkout Form Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'aqualuxe')));
    return;
}

?>

<div class="checkout-page-wrapper">
    
    <!-- Checkout Header -->
    <div class="checkout-header mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            <?php esc_html_e('Checkout', 'aqualuxe'); ?>
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            <?php esc_html_e('Complete your order with our secure checkout process.', 'aqualuxe'); ?>
        </p>
    </div>

    <!-- Checkout Progress -->
    <div class="checkout-progress mb-8">
        <div class="flex items-center justify-center space-x-4">
            <div class="step completed flex items-center">
                <div class="step-icon bg-primary-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
            </div>
            <div class="step-divider flex-1 border-t border-gray-300 dark:border-gray-600"></div>
            <div class="step active flex items-center">
                <div class="step-icon bg-primary-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">2</div>
                <span class="ml-2 text-sm font-medium text-primary-600"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
            </div>
            <div class="step-divider flex-1 border-t border-gray-300 dark:border-gray-600"></div>
            <div class="step flex items-center">
                <div class="step-icon bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">3</div>
                <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e('Complete', 'aqualuxe'); ?></span>
            </div>
        </div>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <?php if ($checkout->get_checkout_fields()) : ?>

            <div class="checkout-layout lg:flex lg:space-x-8">
                
                <!-- Billing & Shipping Information -->
                <div class="checkout-fields lg:w-2/3 mb-8 lg:mb-0">
                    
                    <div class="checkout-fields-wrapper space-y-8">
                        
                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <!-- Customer Information -->
                        <div class="customer-details bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                            
                            <?php if ($checkout->get_checkout_fields('billing')) : ?>
                                <!-- Billing Fields -->
                                <div class="woocommerce-billing-fields">
                                    <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
                                        <?php esc_html_e('Billing Details', 'aqualuxe'); ?>
                                    </h3>
                                    
                                    <?php do_action('woocommerce_checkout_billing'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($checkout->get_checkout_fields('shipping')) : ?>
                                <!-- Shipping Fields -->
                                <div class="woocommerce-shipping-fields mt-8">
                                    <?php if (WC()->cart->needs_shipping_address() === true) : ?>
                                        
                                        <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
                                            <?php esc_html_e('Shipping Details', 'aqualuxe'); ?>
                                        </h3>

                                        <div class="shipping-same-billing mb-4">
                                            <label class="flex items-center">
                                                <input id="ship-to-different-address-checkbox" class="ship-to-different-address-checkbox mr-2" <?php checked(apply_filters('woocommerce_ship_to_different_address_checked', 'shipping' === get_option('woocommerce_ship_to_destination') ? 1 : 0), 1); ?> type="checkbox" name="ship_to_different_address" value="1" />
                                                <span class="text-gray-700 dark:text-gray-300"><?php esc_html_e('Ship to a different address?', 'aqualuxe'); ?></span>
                                            </label>
                                        </div>

                                        <div class="shipping-address-fields">
                                            <?php do_action('woocommerce_checkout_shipping'); ?>
                                        </div>

                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                        </div>

                        <!-- Additional Information -->
                        <?php if ($checkout->get_checkout_fields('order')) : ?>
                            <div class="woocommerce-additional-fields bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                                <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
                                    <?php esc_html_e('Additional Information', 'aqualuxe'); ?>
                                </h3>
                                <?php do_action('woocommerce_checkout_order_fields'); ?>
                            </div>
                        <?php endif; ?>

                        <?php do_action('woocommerce_checkout_after_order_fields'); ?>

                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary lg:w-1/3">
                    <div class="order-summary-wrapper bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm sticky top-8">
                        
                        <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
                            <?php esc_html_e('Your Order', 'aqualuxe'); ?>
                        </h3>

                        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action('woocommerce_checkout_order_review'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>

                    </div>
                </div>

            </div>

        <?php endif; ?>

    </form>

</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>