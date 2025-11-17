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

	<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
		<div class="lg:col-span-7">
			<div class="bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden mb-8">
				<?php if ( $checkout->get_checkout_fields() ) : ?>
					<div class="p-6">
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div class="col2-set" id="customer_details">
							<div class="mb-8">
								<h3 class="text-xl font-medium mb-4 pb-2 border-b border-gray-200 dark:border-dark-600"><?php esc_html_e( 'Billing details', 'aqualuxe' ); ?></h3>
								<?php do_action( 'woocommerce_checkout_billing' ); ?>
							</div>

							<div>
								<h3 class="text-xl font-medium mb-4 pb-2 border-b border-gray-200 dark:border-dark-600"><?php esc_html_e( 'Shipping details', 'aqualuxe' ); ?></h3>
								<?php do_action( 'woocommerce_checkout_shipping' ); ?>
							</div>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="lg:col-span-5">
			<div class="bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden">
				<div class="p-6">
					<h3 id="order_review_heading" class="text-xl font-medium mb-4 pb-2 border-b border-gray-200 dark:border-dark-600"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
					
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
			</div>
			
			<!-- Trust Badges -->
			<div class="trust-badges mt-8">
				<div class="grid grid-cols-2 gap-4">
					<div class="bg-white dark:bg-dark-700 rounded-lg shadow-soft p-4 text-center">
						<div class="bg-gray-100 dark:bg-dark-600 rounded-full p-3 inline-flex items-center justify-center mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
							</svg>
						</div>
						<h4 class="font-medium text-sm mb-1"><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></h4>
						<p class="text-xs text-dark-500 dark:text-dark-300"><?php esc_html_e( '256-bit SSL encryption', 'aqualuxe' ); ?></p>
					</div>
					<div class="bg-white dark:bg-dark-700 rounded-lg shadow-soft p-4 text-center">
						<div class="bg-gray-100 dark:bg-dark-600 rounded-full p-3 inline-flex items-center justify-center mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
							</svg>
						</div>
						<h4 class="font-medium text-sm mb-1"><?php esc_html_e( 'Payment Options', 'aqualuxe' ); ?></h4>
						<p class="text-xs text-dark-500 dark:text-dark-300"><?php esc_html_e( 'All major cards accepted', 'aqualuxe' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>