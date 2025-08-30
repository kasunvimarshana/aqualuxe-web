<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <div class="checkout-header">
        <h2><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></h2>
        <div class="checkout-steps">
            <div class="step active">
                <span class="step-number">1</span>
                <span class="step-label"><?php esc_html_e( 'Information', 'aqualuxe' ); ?></span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <span class="step-number">2</span>
                <span class="step-label"><?php esc_html_e( 'Shipping', 'aqualuxe' ); ?></span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <span class="step-number">3</span>
                <span class="step-label"><?php esc_html_e( 'Payment', 'aqualuxe' ); ?></span>
            </div>
        </div>
    </div>

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
                
                <!-- Special instructions for fish shipping -->
                <div class="fish-shipping-instructions">
                    <h3><?php esc_html_e( 'Special Instructions for Live Fish', 'aqualuxe' ); ?></h3>
                    <div class="instructions-content">
                        <p><?php esc_html_e( 'If your order contains live fish or aquatic life, please provide any special delivery instructions below:', 'aqualuxe' ); ?></p>
                        <textarea name="fish_shipping_instructions" id="fish_shipping_instructions" rows="4" placeholder="<?php esc_attr_e( 'E.g., Please call before delivery, leave with neighbor if not home, etc.', 'aqualuxe' ); ?>"></textarea>
                    </div>
                </div>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
	
    <div class="order-review-wrapper">
        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
        
        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

        <div id="order_review" class="woocommerce-checkout-review-order">
            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
        </div>

        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        
        <!-- Checkout trust badges -->
        <div class="checkout-trust-badges">
            <div class="trust-badges-title"><?php esc_html_e( 'Guaranteed Safe Checkout', 'aqualuxe' ); ?></div>
            <div class="trust-badges-icons">
                <span class="payment-icon payment-visa" aria-label="<?php esc_attr_e( 'Visa', 'aqualuxe' ); ?>"></span>
                <span class="payment-icon payment-mastercard" aria-label="<?php esc_attr_e( 'Mastercard', 'aqualuxe' ); ?>"></span>
                <span class="payment-icon payment-amex" aria-label="<?php esc_attr_e( 'American Express', 'aqualuxe' ); ?>"></span>
                <span class="payment-icon payment-discover" aria-label="<?php esc_attr_e( 'Discover', 'aqualuxe' ); ?>"></span>
                <span class="payment-icon payment-paypal" aria-label="<?php esc_attr_e( 'PayPal', 'aqualuxe' ); ?>"></span>
            </div>
        </div>
        
        <!-- Checkout guarantees -->
        <div class="checkout-guarantees">
            <div class="guarantee-item">
                <i class="fas fa-shield-alt"></i>
                <div class="guarantee-content">
                    <h4><?php esc_html_e( 'Live Arrival Guarantee', 'aqualuxe' ); ?></h4>
                    <p><?php esc_html_e( 'All fish are guaranteed to arrive alive and healthy.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            <div class="guarantee-item">
                <i class="fas fa-lock"></i>
                <div class="guarantee-content">
                    <h4><?php esc_html_e( 'Secure Payment', 'aqualuxe' ); ?></h4>
                    <p><?php esc_html_e( 'Your payment information is encrypted and never stored.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            <div class="guarantee-item">
                <i class="fas fa-headset"></i>
                <div class="guarantee-content">
                    <h4><?php esc_html_e( '24/7 Support', 'aqualuxe' ); ?></h4>
                    <p><?php esc_html_e( 'Our team is available to help with any questions or concerns.', 'aqualuxe' ); ?></p>
                </div>
            </div>
        </div>
    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>