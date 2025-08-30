<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
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

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <div class="checkout-wrapper flex flex-col lg:flex-row lg:space-x-8">
        <div class="checkout-details w-full lg:w-7/12">
            <div class="bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden p-6 md:p-8 mb-8">
                <?php if ($checkout->get_checkout_fields()) : ?>

                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div class="col2-set" id="customer_details">
                        <div class="col-1 mb-8">
                            <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Billing details', 'aqualuxe'); ?></h3>
                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>

                        <div class="col-2">
                            <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Shipping details', 'aqualuxe'); ?></h3>
                            <?php do_action('woocommerce_checkout_shipping'); ?>
                        </div>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                <?php endif; ?>
            </div>

            <?php if (wc_get_page_id('terms') > 0 && apply_filters('woocommerce_checkout_show_terms', true)) : ?>
                <div class="checkout-terms bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden p-6 md:p-8 mb-8">
                    <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Terms and conditions', 'aqualuxe'); ?></h3>
                    <div class="woocommerce-terms-and-conditions-wrapper">
                        <?php do_action('woocommerce_checkout_before_terms_and_conditions'); ?>
                        
                        <?php
                        /**
                         * Terms and conditions hook used to inject content.
                         *
                         * @since 3.4.0.
                         * @hooked wc_checkout_privacy_policy_text - 20
                         * @hooked wc_terms_and_conditions_page_content - 30
                         */
                        do_action('woocommerce_checkout_terms_and_conditions');
                        ?>
                        
                        <?php if (wc_terms_and_conditions_checkbox_enabled()) : ?>
                            <p class="form-row validate-required mt-4">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-start">
                                    <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mt-1 mr-2" name="terms" id="terms" />
                                    <span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>&nbsp;<abbr class="required" title="<?php esc_attr_e('required', 'aqualuxe'); ?>">*</abbr>
                                </label>
                                <input type="hidden" name="terms-field" value="1" />
                            </p>
                        <?php endif; ?>
                        
                        <?php do_action('woocommerce_checkout_after_terms_and_conditions'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="checkout-sidebar w-full lg:w-5/12">
            <div class="bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden p-6 md:p-8 sticky top-24">
                <h3 id="order_review_heading" class="text-2xl font-bold mb-6"><?php esc_html_e('Your order', 'aqualuxe'); ?></h3>
                
                <?php do_action('woocommerce_checkout_before_order_review'); ?>

                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action('woocommerce_checkout_order_review'); ?>
                </div>

                <?php do_action('woocommerce_checkout_after_order_review'); ?>
                
                <?php if (function_exists('aqualuxe_checkout_trust_badges')) : ?>
                    <div class="checkout-trust-badges mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <?php aqualuxe_checkout_trust_badges(); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (function_exists('aqualuxe_checkout_payment_icons')) : ?>
                    <div class="checkout-payment-icons mt-6">
                        <?php aqualuxe_checkout_payment_icons(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>