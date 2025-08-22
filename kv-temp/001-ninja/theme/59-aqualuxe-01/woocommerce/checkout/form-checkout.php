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

// Get checkout layout
$checkout_layout = get_theme_mod('woocommerce_checkout_layout', 'default');

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'aqualuxe')));
    return;
}

?>

<div class="checkout-container checkout-layout--<?php echo esc_attr($checkout_layout); ?>">
    <?php if (get_theme_mod('woocommerce_checkout_steps', true)) : ?>
        <div class="checkout-steps">
            <div class="checkout-step checkout-step--cart <?php echo is_cart() ? 'checkout-step--active' : 'checkout-step--completed'; ?>">
                <div class="checkout-step__number">1</div>
                <div class="checkout-step__title"><?php esc_html_e('Cart', 'aqualuxe'); ?></div>
            </div>
            <div class="checkout-step checkout-step--checkout checkout-step--active">
                <div class="checkout-step__number">2</div>
                <div class="checkout-step__title"><?php esc_html_e('Checkout', 'aqualuxe'); ?></div>
            </div>
            <div class="checkout-step checkout-step--complete">
                <div class="checkout-step__number">3</div>
                <div class="checkout-step__title"><?php esc_html_e('Complete', 'aqualuxe'); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <?php if ($checkout->get_checkout_fields()) : ?>

            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="checkout-columns" id="customer_details">
                <div class="checkout-column checkout-column--details">
                    <?php if (get_theme_mod('woocommerce_checkout_coupon', true) && wc_coupons_enabled()) : ?>
                        <div class="checkout-coupon">
                            <div class="checkout-coupon__toggle">
                                <?php esc_html_e('Have a coupon?', 'aqualuxe'); ?> <a href="#" class="checkout-coupon__toggle-link"><?php esc_html_e('Click here to enter your code', 'aqualuxe'); ?></a>
                            </div>

                            <div class="checkout-coupon__form" style="display: none;">
                                <p><?php esc_html_e('If you have a coupon code, please apply it below.', 'aqualuxe'); ?></p>

                                <div class="coupon-field">
                                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'aqualuxe'); ?>" />
                                    <button type="button" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'aqualuxe'); ?>"><?php esc_html_e('Apply', 'aqualuxe'); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php do_action('woocommerce_checkout_billing'); ?>
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                </div>

                <div class="checkout-column checkout-column--order">
                    <div class="checkout-order-review">
                        <h3 id="order_review_heading"><?php esc_html_e('Your order', 'aqualuxe'); ?></h3>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action('woocommerce_checkout_order_review'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>
                    </div>

                    <?php if (get_theme_mod('woocommerce_checkout_trust_badges', true)) : ?>
                        <div class="checkout-trust-badges">
                            <?php
                            $trust_badges = get_theme_mod('woocommerce_checkout_trust_badges_content', '');
                            echo wp_kses_post($trust_badges);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

        <?php endif; ?>
    </form>

    <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Toggle coupon form
        $('.checkout-coupon__toggle-link').on('click', function(e) {
            e.preventDefault();
            $('.checkout-coupon__form').slideToggle(300);
        });

        // Apply coupon via AJAX
        $('.checkout-coupon__form button[name="apply_coupon"]').on('click', function(e) {
            e.preventDefault();
            
            var coupon_code = $('#coupon_code').val();
            
            if (coupon_code) {
                var data = {
                    action: 'woocommerce_apply_coupon',
                    security: wc_checkout_params.apply_coupon_nonce,
                    coupon_code: coupon_code
                };

                $.ajax({
                    type: 'POST',
                    url: wc_checkout_params.ajax_url,
                    data: data,
                    success: function(code) {
                        $('.woocommerce-error, .woocommerce-message').remove();
                        $('#coupon_code').val('');
                        
                        // Trigger update checkout
                        $('body').trigger('update_checkout');
                        
                        // Show message
                        $('.checkout-coupon').before(code);
                        
                        // Hide coupon form
                        $('.checkout-coupon__form').slideUp(300);
                    },
                    dataType: 'html'
                });
            }
        });
    });
</script>