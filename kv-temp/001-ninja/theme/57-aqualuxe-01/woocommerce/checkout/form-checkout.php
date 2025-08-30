<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<div class="aqualuxe-checkout-layout">
	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<div class="checkout-columns">
			<div class="checkout-column checkout-details">
				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="col2-set" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
			</div>

			<div class="checkout-column checkout-summary">
				<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
				
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				
				<?php if ( wc_get_page_id( 'cart' ) > 0 ) : ?>
					<div class="return-to-cart">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
							<?php esc_html_e( 'Return to cart', 'aqualuxe' ); ?>
						</a>
					</div>
				<?php endif; ?>
				
				<div class="checkout-secure-note">
					<div class="secure-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
							<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
						</svg>
					</div>
					<p><?php esc_html_e( 'Secure checkout - Your personal and payment information is protected with SSL encryption', 'aqualuxe' ); ?></p>
				</div>
				
				<?php if ( function_exists( 'aqualuxe_payment_icons' ) ) : ?>
					<div class="checkout-payment-icons">
						<?php aqualuxe_payment_icons(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

	</form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php
// Display trust badges section
?>
<section class="checkout-trust-badges">
	<div class="container">
		<div class="trust-badges-grid">
			<div class="trust-badge">
				<div class="trust-badge-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10"></circle>
						<polyline points="12 6 12 12 16 14"></polyline>
					</svg>
				</div>
				<div class="trust-badge-content">
					<h3><?php esc_html_e( 'Fast Delivery', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Free shipping on orders over $50', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="trust-badge">
				<div class="trust-badge-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
					</svg>
				</div>
				<div class="trust-badge-content">
					<h3><?php esc_html_e( 'Satisfaction Guaranteed', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( '30-day money-back guarantee', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="trust-badge">
				<div class="trust-badge-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
					</svg>
				</div>
				<div class="trust-badge-content">
					<h3><?php esc_html_e( 'Secure Payment', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Your data is protected', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="trust-badge">
				<div class="trust-badge-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10"></circle>
						<path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
						<line x1="9" y1="9" x2="9.01" y2="9"></line>
						<line x1="15" y1="9" x2="15.01" y2="9"></line>
					</svg>
				</div>
				<div class="trust-badge-content">
					<h3><?php esc_html_e( '24/7 Customer Support', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'We\'re here to help', 'aqualuxe' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>