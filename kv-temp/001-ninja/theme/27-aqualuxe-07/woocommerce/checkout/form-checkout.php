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
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="checkout-layout grid grid-cols-1 lg:grid-cols-12 gap-8">
		<div class="checkout-details lg:col-span-7">
			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div class="customer-details" id="customer_details">
					<div class="checkout-section bg-white dark:bg-dark-800 rounded-lg shadow-soft p-6 mb-8">
						<h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Billing Details', 'aqualuxe' ); ?></h3>
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="checkout-section bg-white dark:bg-dark-800 rounded-lg shadow-soft p-6 mb-8">
						<h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Shipping Details', 'aqualuxe' ); ?></h3>
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
			
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
		</div>
		
		<div class="checkout-summary lg:col-span-5">
			<div class="checkout-section bg-white dark:bg-dark-800 rounded-lg shadow-soft p-6 sticky top-32">
				<h3 id="order_review_heading" class="text-2xl font-bold mb-6"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
				
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				
				<div class="checkout-security mt-8">
					<div class="flex items-center mb-4">
						<svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
						</svg>
						<span class="font-medium"><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></span>
					</div>
					<div class="flex items-center mb-4">
						<svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
						</svg>
						<span class="font-medium"><?php esc_html_e( 'SSL Encrypted Payment', 'aqualuxe' ); ?></span>
					</div>
					<div class="flex items-center">
						<svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
							<path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
							<path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
						</svg>
						<span class="font-medium"><?php esc_html_e( 'Privacy Protected', 'aqualuxe' ); ?></span>
					</div>
				</div>
				
				<div class="payment-icons mt-6 flex flex-wrap justify-center gap-2">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-visa.svg' ); ?>" alt="Visa" class="h-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-mastercard.svg' ); ?>" alt="Mastercard" class="h-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-amex.svg' ); ?>" alt="American Express" class="h-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-paypal.svg' ); ?>" alt="PayPal" class="h-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-apple.svg' ); ?>" alt="Apple Pay" class="h-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-google.svg' ); ?>" alt="Google Pay" class="h-8">
				</div>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>