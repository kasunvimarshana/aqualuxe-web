<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/checkout.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

// Get checkout display options
$enable_order_notes = get_theme_mod('aqualuxe_enable_order_notes', true);
$enable_coupon = get_theme_mod('aqualuxe_enable_checkout_coupon', true);
$show_shipping_tab = get_theme_mod('aqualuxe_show_shipping_tab', true);
$show_payment_tab = get_theme_mod('aqualuxe_show_payment_tab', true);
$show_order_review_tab = get_theme_mod('aqualuxe_show_order_review_tab', true);
$layout_style = get_theme_mod('aqualuxe_checkout_layout', 'standard');

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'aqualuxe')));
    return;
}

?>

<div class="woocommerce-checkout-wrapper">
    <?php if ($layout_style === 'multi-step') : ?>
    <div class="checkout-steps mb-8" x-data="{ step: 1 }">
        <div class="checkout-steps-nav flex border-b border-gray-200 dark:border-gray-700 mb-6">
            <button 
                @click="step = 1" 
                :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': step === 1, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': step !== 1 }"
                class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none flex items-center"
            >
                <span class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 inline-flex items-center justify-center mr-2 text-sm" :class="{ 'bg-primary-600 text-white dark:bg-primary-500': step === 1 }">1</span>
                <?php esc_html_e('Customer Info', 'aqualuxe'); ?>
            </button>
            
            <?php if ($show_shipping_tab) : ?>
            <button 
                @click="step = 2" 
                :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': step === 2, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': step !== 2 }"
                class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none flex items-center"
            >
                <span class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 inline-flex items-center justify-center mr-2 text-sm" :class="{ 'bg-primary-600 text-white dark:bg-primary-500': step === 2 }">2</span>
                <?php esc_html_e('Shipping', 'aqualuxe'); ?>
            </button>
            <?php endif; ?>
            
            <?php if ($show_payment_tab) : ?>
            <button 
                @click="step = 3" 
                :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': step === 3, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': step !== 3 }"
                class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none flex items-center"
            >
                <span class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 inline-flex items-center justify-center mr-2 text-sm" :class="{ 'bg-primary-600 text-white dark:bg-primary-500': step === 3 }">3</span>
                <?php esc_html_e('Payment', 'aqualuxe'); ?>
            </button>
            <?php endif; ?>
            
            <?php if ($show_order_review_tab) : ?>
            <button 
                @click="step = 4" 
                :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': step === 4, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': step !== 4 }"
                class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none flex items-center"
            >
                <span class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 inline-flex items-center justify-center mr-2 text-sm" :class="{ 'bg-primary-600 text-white dark:bg-primary-500': step === 4 }">4</span>
                <?php esc_html_e('Review', 'aqualuxe'); ?>
            </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <?php if ($layout_style === 'multi-step') : ?>
            <div x-show="step === 1">
        <?php endif; ?>

        <?php if ($checkout->get_checkout_fields()) : ?>

            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="customer-details" id="customer_details">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="billing-details bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6">
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Billing Details', 'aqualuxe'); ?></h3>
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>

                    <div class="shipping-details bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6">
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Shipping Details', 'aqualuxe'); ?></h3>
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>
                </div>
            </div>

            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

        <?php endif; ?>

        <?php if ($layout_style === 'multi-step') : ?>
            <div class="flex justify-end mt-6">
                <button type="button" @click="step = 2" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md transition-colors">
                    <?php esc_html_e('Continue to Shipping', 'aqualuxe'); ?>
                </button>
            </div>
            </div>

            <?php if ($show_shipping_tab) : ?>
            <div x-show="step === 2" x-cloak>
                <div class="shipping-methods bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6 mb-8">
                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Shipping Method', 'aqualuxe'); ?></h3>
                    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                        <?php wc_cart_totals_shipping_html(); ?>
                    <?php else : ?>
                        <p class="text-gray-600 dark:text-gray-300"><?php esc_html_e('Your order does not require shipping.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" @click="step = 1" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white py-2 px-6 rounded-md transition-colors">
                        <?php esc_html_e('Back to Customer Info', 'aqualuxe'); ?>
                    </button>
                    <button type="button" @click="step = 3" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md transition-colors">
                        <?php esc_html_e('Continue to Payment', 'aqualuxe'); ?>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_payment_tab) : ?>
            <div x-show="step === 3" x-cloak>
                <div class="payment-methods bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6 mb-8">
                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Payment Method', 'aqualuxe'); ?></h3>
                    <?php woocommerce_checkout_payment(); ?>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" @click="step = 2" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white py-2 px-6 rounded-md transition-colors">
                        <?php esc_html_e('Back to Shipping', 'aqualuxe'); ?>
                    </button>
                    <button type="button" @click="step = 4" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md transition-colors">
                        <?php esc_html_e('Review Order', 'aqualuxe'); ?>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_order_review_tab) : ?>
            <div x-show="step === 4" x-cloak>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
        
        <div class="order-review bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6 mt-8">
            <h3 id="order_review_heading" class="text-xl font-bold mb-4"><?php esc_html_e('Your Order', 'aqualuxe'); ?></h3>
            
            <?php do_action('woocommerce_checkout_before_order_review'); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action('woocommerce_checkout_order_review'); ?>
            </div>

            <?php do_action('woocommerce_checkout_after_order_review'); ?>
        </div>

        <?php if ($layout_style === 'multi-step' && $show_order_review_tab) : ?>
            <div class="flex justify-between mt-6">
                <button type="button" @click="step = 3" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white py-2 px-6 rounded-md transition-colors">
                    <?php esc_html_e('Back to Payment', 'aqualuxe'); ?>
                </button>
            </div>
            </div>
        <?php endif; ?>

    </form>

    <?php if ($layout_style === 'multi-step') : ?>
    </div>
    <?php endif; ?>

    <?php if ($enable_coupon && wc_coupons_enabled()) : ?>
        <div class="checkout-coupon bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden p-6 mt-8">
            <div x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center justify-between w-full text-left focus:outline-none">
                    <h3 class="text-lg font-bold"><?php esc_html_e('Have a coupon?', 'aqualuxe'); ?></h3>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="open" x-cloak class="mt-4">
                    <form class="checkout_coupon woocommerce-form-coupon" method="post">
                        <p class="text-gray-600 dark:text-gray-300 mb-4"><?php esc_html_e('If you have a coupon code, please apply it below.', 'aqualuxe'); ?></p>
                        
                        <div class="flex">
                            <input type="text" name="coupon_code" class="input-text flex-grow border border-gray-300 dark:border-gray-600 rounded-l-md py-2 px-4 dark:bg-gray-700 dark:text-white" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" id="coupon_code" value="" />
                            <button type="submit" class="button bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-r-md transition-colors" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>"><?php esc_html_e('Apply', 'aqualuxe'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>