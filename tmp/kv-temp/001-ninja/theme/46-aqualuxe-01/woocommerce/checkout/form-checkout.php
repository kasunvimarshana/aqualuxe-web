<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
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

	<div class="checkout-wrapper grid grid-cols-1 lg:grid-cols-12 gap-8">
		<div class="checkout-details lg:col-span-7">
			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div class="customer-details" id="customer_details">
					<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
						<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Billing details', 'aqualuxe' ); ?></h3>
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
						<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Shipping details', 'aqualuxe' ); ?></h3>
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
		</div>

		<div class="checkout-order lg:col-span-5">
			<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
				<h3 id="order_review_heading" class="text-xl font-bold mb-4"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>

				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>