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

	<div class="checkout-container grid grid-cols-1 lg:grid-cols-12 gap-8">
		<div class="checkout-details lg:col-span-7">
			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div class="customer-details bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6 mb-8">
					<h3 class="text-2xl font-serif font-bold text-dark-900 dark:text-white mb-6"><?php esc_html_e( 'Customer Details', 'aqualuxe' ); ?></h3>

					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="shipping-details bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6">
					<h3 class="text-2xl font-serif font-bold text-dark-900 dark:text-white mb-6"><?php esc_html_e( 'Shipping Details', 'aqualuxe' ); ?></h3>

					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
		</div>

		<div class="checkout-order lg:col-span-5">
			<div class="order-review bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6 sticky top-8">
				<h3 id="order_review_heading" class="text-2xl font-serif font-bold text-dark-900 dark:text-white mb-6"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

				<?php if ( apply_filters( 'aqualuxe_show_secure_checkout_notice', true ) ) : ?>
					<div class="secure-checkout flex items-center justify-center mt-6 pt-6 border-t border-gray-200 dark:border-dark-700 text-dark-500 dark:text-dark-400 text-sm">
						<svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
						</svg>
						<?php esc_html_e( 'Secure Checkout - Your data is protected', 'aqualuxe' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>