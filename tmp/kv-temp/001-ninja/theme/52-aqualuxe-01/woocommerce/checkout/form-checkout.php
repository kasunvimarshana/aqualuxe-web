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

	<div class="flex flex-wrap -mx-4">
		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<div class="w-full lg:w-7/12 px-4 mb-8 lg:mb-0">
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
					<div class="p-6">
						<h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Billing Details', 'aqualuxe' ); ?></h3>

						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div id="customer_details">
							<div class="mb-8">
								<?php do_action( 'woocommerce_checkout_billing' ); ?>
							</div>

							<div>
								<?php do_action( 'woocommerce_checkout_shipping' ); ?>
							</div>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					</div>
				</div>
			</div>

		<?php endif; ?>
		
		<div class="w-full lg:w-5/12 px-4">
			<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
				<div class="p-6">
					<h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Your Order', 'aqualuxe' ); ?></h3>
					
					<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
					
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
			</div>
			
			<!-- Trust Badges Section -->
			<div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
				<div class="p-6">
					<h4 class="text-lg font-bold mb-4"><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></h4>
					
					<div class="flex flex-wrap justify-center items-center gap-4">
						<div class="text-center">
							<div class="mb-2">
								<svg class="w-8 h-8 mx-auto text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
							</div>
							<span class="text-sm"><?php esc_html_e( 'Secure Payment', 'aqualuxe' ); ?></span>
						</div>
						
						<div class="text-center">
							<div class="mb-2">
								<svg class="w-8 h-8 mx-auto text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
							</div>
							<span class="text-sm"><?php esc_html_e( 'Money Back', 'aqualuxe' ); ?></span>
						</div>
						
						<div class="text-center">
							<div class="mb-2">
								<svg class="w-8 h-8 mx-auto text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
							</div>
							<span class="text-sm"><?php esc_html_e( 'Safe & Trusted', 'aqualuxe' ); ?></span>
						</div>
						
						<div class="text-center">
							<div class="mb-2">
								<svg class="w-8 h-8 mx-auto text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-5h2.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3z"></path></svg>
							</div>
							<span class="text-sm"><?php esc_html_e( 'Fast Shipping', 'aqualuxe' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>