<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'aqualuxe')));
    return;
}

?>

<div class="checkout-container">
    <?php
    /**
     * Hook: aqualuxe_before_checkout_form.
     *
     * @hooked aqualuxe_checkout_steps - 10
     * @hooked aqualuxe_checkout_login_form - 20
     */
    do_action('aqualuxe_before_checkout_form');
    ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <?php if ($checkout->get_checkout_fields()) : ?>

            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="checkout-columns" id="customer_details">
                <div class="checkout-column checkout-column--details">
                    <?php do_action('woocommerce_checkout_billing'); ?>
                    <?php do_action('woocommerce_checkout_shipping'); ?>

                    <?php
                    /**
                     * Hook: aqualuxe_after_checkout_shipping.
                     *
                     * @hooked aqualuxe_checkout_delivery_options - 10
                     * @hooked aqualuxe_checkout_additional_fields - 20
                     */
                    do_action('aqualuxe_after_checkout_shipping');
                    ?>
                </div>

                <div class="checkout-column checkout-column--order">
                    <div class="checkout-order-summary">
                        <h3 id="order_review_heading"><?php esc_html_e('Your order', 'aqualuxe'); ?></h3>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action('woocommerce_checkout_order_review'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>
                    </div>

                    <?php
                    /**
                     * Hook: aqualuxe_checkout_sidebar.
                     *
                     * @hooked aqualuxe_checkout_guarantee - 10
                     * @hooked aqualuxe_checkout_payment_icons - 20
                     * @hooked aqualuxe_checkout_support_info - 30
                     */
                    do_action('aqualuxe_checkout_sidebar');
                    ?>
                </div>
            </div>

            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

        <?php endif; ?>

        <?php
        /**
         * Hook: aqualuxe_before_checkout_submit.
         *
         * @hooked aqualuxe_checkout_terms_and_conditions - 10
         * @hooked aqualuxe_checkout_newsletter_signup - 20
         */
        do_action('aqualuxe_before_checkout_submit');
        ?>
    </form>

    <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
</div>