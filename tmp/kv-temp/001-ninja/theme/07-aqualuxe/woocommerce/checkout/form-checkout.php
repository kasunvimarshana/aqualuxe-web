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

	<div class="checkout-wrapper grid grid-cols-1 lg:grid-cols-12 gap-8">
		<div class="checkout-details lg:col-span-7">
			<div class="bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden p-6 mb-8">
				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="col2-set" id="customer_details">
						<div class="col-1 mb-8">
							<h3 class="text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4"><?php esc_html_e( 'Billing details', 'aqualuxe' ); ?></h3>
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="col-2">
							<h3 class="text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4"><?php esc_html_e( 'Shipping details', 'aqualuxe' ); ?></h3>
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
			</div>
			
			<?php if ( get_theme_mod( 'aqualuxe_enable_checkout_features', true ) ) : ?>
				<div class="checkout-features grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
					<div class="feature p-4 bg-white dark:bg-dark-800 rounded-lg shadow-soft text-center">
						<div class="feature-icon text-primary-600 dark:text-primary-400 mx-auto mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
							</svg>
						</div>
						<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1"><?php esc_html_e( 'Secure Checkout', 'aqualuxe' ); ?></h4>
						<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Your payment information is encrypted', 'aqualuxe' ); ?></p>
					</div>
					
					<div class="feature p-4 bg-white dark:bg-dark-800 rounded-lg shadow-soft text-center">
						<div class="feature-icon text-primary-600 dark:text-primary-400 mx-auto mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
							</svg>
						</div>
						<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1"><?php esc_html_e( 'Multiple Payment Options', 'aqualuxe' ); ?></h4>
						<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Credit cards, PayPal, and more', 'aqualuxe' ); ?></p>
					</div>
					
					<div class="feature p-4 bg-white dark:bg-dark-800 rounded-lg shadow-soft text-center">
						<div class="feature-icon text-primary-600 dark:text-primary-400 mx-auto mb-2">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
							</svg>
						</div>
						<h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-1"><?php esc_html_e( 'Fast Shipping', 'aqualuxe' ); ?></h4>
						<p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Quick delivery to your doorstep', 'aqualuxe' ); ?></p>
					</div>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="checkout-sidebar lg:col-span-5">
			<div class="bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden">
				<div class="p-6">
					<h3 id="order_review_heading" class="text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4"><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
				
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
				
				<?php if ( get_theme_mod( 'aqualuxe_enable_checkout_trust_badges', true ) ) : ?>
					<div class="checkout-trust-badges p-6 bg-gray-50 dark:bg-dark-700 border-t border-gray-200 dark:border-dark-600">
						<h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 text-center"><?php esc_html_e( 'Secure Payment Methods', 'aqualuxe' ); ?></h4>
						<div class="flex flex-wrap justify-center gap-3">
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/payment-visa.svg' ) ); ?>" alt="Visa" class="h-8">
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/payment-mastercard.svg' ) ); ?>" alt="Mastercard" class="h-8">
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/payment-amex.svg' ) ); ?>" alt="American Express" class="h-8">
							<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/payment-paypal.svg' ) ); ?>" alt="PayPal" class="h-8">
						</div>
						
						<div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-3">
							<p><?php esc_html_e( 'Your payment information is processed securely. We do not store credit card details nor have access to your credit card information.', 'aqualuxe' ); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>
			
			<?php if ( get_theme_mod( 'aqualuxe_enable_checkout_help', true ) ) : ?>
				<div class="checkout-help mt-6 p-6 bg-white dark:bg-dark-800 rounded-lg shadow-medium">
					<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Need Help?', 'aqualuxe' ); ?></h3>
					<div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
						<p><?php esc_html_e( 'Have questions about your order or the checkout process?', 'aqualuxe' ); ?></p>
						<div class="flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
							</svg>
							<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_phone', '+1 (555) 123-4567' ) ); ?></span>
						</div>
						<div class="flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
							</svg>
							<span><?php echo esc_html( get_theme_mod( 'aqualuxe_contact_email', 'support@aqualuxe.com' ) ); ?></span>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<style>
	/* Checkout form custom styles */
	.woocommerce-checkout .form-row {
		margin-bottom: 1rem;
	}
	
	.woocommerce-checkout .form-row label {
		display: block;
		margin-bottom: 0.5rem;
		font-size: 0.875rem;
		font-weight: 500;
		color: #4b5563;
	}
	
	.dark .woocommerce-checkout .form-row label {
		color: #9ca3af;
	}
	
	.woocommerce-checkout .form-row .required {
		color: #ef4444;
		text-decoration: none;
	}
	
	.woocommerce-checkout .form-row .input-text,
	.woocommerce-checkout .form-row select {
		width: 100%;
		padding: 0.75rem 1rem;
		border: 1px solid #d1d5db;
		border-radius: 0.375rem;
		background-color: #ffffff;
		color: #1f2937;
		transition: all 0.3s ease;
	}
	
	.dark .woocommerce-checkout .form-row .input-text,
	.dark .woocommerce-checkout .form-row select {
		background-color: #1f2937;
		border-color: #374151;
		color: #e5e7eb;
	}
	
	.woocommerce-checkout .form-row .input-text:focus,
	.woocommerce-checkout .form-row select:focus {
		border-color: #0ea5e9;
		outline: none;
		box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
	}
	
	.dark .woocommerce-checkout .form-row .input-text:focus,
	.dark .woocommerce-checkout .form-row select:focus {
		border-color: #38bdf8;
		box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2);
	}
	
	.woocommerce-checkout .form-row .select2-container .select2-selection--single {
		height: auto;
		padding: 0.75rem 1rem;
		border: 1px solid #d1d5db;
		border-radius: 0.375rem;
		background-color: #ffffff;
	}
	
	.dark .woocommerce-checkout .form-row .select2-container .select2-selection--single {
		background-color: #1f2937;
		border-color: #374151;
	}
	
	.woocommerce-checkout .form-row .select2-container .select2-selection--single .select2-selection__rendered {
		padding: 0;
		color: #1f2937;
	}
	
	.dark .woocommerce-checkout .form-row .select2-container .select2-selection--single .select2-selection__rendered {
		color: #e5e7eb;
	}
	
	.woocommerce-checkout .form-row .select2-container .select2-selection--single .select2-selection__arrow {
		height: 100%;
	}
	
	.woocommerce-checkout .form-row-first {
		float: left;
		width: 48%;
		clear: both;
	}
	
	.woocommerce-checkout .form-row-last {
		float: right;
		width: 48%;
	}
	
	.woocommerce-checkout .form-row-wide {
		clear: both;
		width: 100%;
	}
	
	.woocommerce-checkout .woocommerce-billing-fields,
	.woocommerce-checkout .woocommerce-shipping-fields,
	.woocommerce-checkout .woocommerce-additional-fields {
		margin-bottom: 2rem;
	}
	
	.woocommerce-checkout .woocommerce-billing-fields__field-wrapper,
	.woocommerce-checkout .woocommerce-shipping-fields__field-wrapper,
	.woocommerce-checkout .woocommerce-additional-fields__field-wrapper {
		display: flow-root;
	}
	
	.woocommerce-checkout .woocommerce-additional-fields h3 {
		font-size: 1.25rem;
		font-weight: 600;
		margin-bottom: 1rem;
	}
	
	/* Order review styles */
	.woocommerce-checkout-review-order-table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 1.5rem;
	}
	
	.woocommerce-checkout-review-order-table th {
		padding: 0.75rem 0;
		text-align: left;
		font-weight: 500;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce-checkout-review-order-table th {
		border-color: #374151;
	}
	
	.woocommerce-checkout-review-order-table td {
		padding: 0.75rem 0;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce-checkout-review-order-table td {
		border-color: #374151;
	}
	
	.woocommerce-checkout-review-order-table .product-name {
		width: 60%;
	}
	
	.woocommerce-checkout-review-order-table .product-total {
		text-align: right;
	}
	
	.woocommerce-checkout-review-order-table .cart-subtotal th,
	.woocommerce-checkout-review-order-table .order-total th {
		font-weight: 500;
	}
	
	.woocommerce-checkout-review-order-table .cart-subtotal td,
	.woocommerce-checkout-review-order-table .order-total td {
		text-align: right;
	}
	
	.woocommerce-checkout-review-order-table .order-total th,
	.woocommerce-checkout-review-order-table .order-total td {
		font-weight: 600;
		font-size: 1.125rem;
	}
	
	/* Payment methods styles */
	.woocommerce-checkout #payment {
		background: transparent;
		border-radius: 0;
	}
	
	.woocommerce-checkout #payment ul.payment_methods {
		padding: 0;
		border: none;
	}
	
	.woocommerce-checkout #payment ul.payment_methods li {
		margin-bottom: 0.5rem;
	}
	
	.woocommerce-checkout #payment ul.payment_methods li input {
		margin-right: 0.5rem;
	}
	
	.woocommerce-checkout #payment ul.payment_methods li label {
		font-weight: 500;
	}
	
	.woocommerce-checkout #payment div.payment_box {
		background-color: #f3f4f6;
		border-radius: 0.375rem;
		padding: 1rem;
		margin: 0.5rem 0 1rem;
	}
	
	.dark .woocommerce-checkout #payment div.payment_box {
		background-color: #1f2937;
	}
	
	.woocommerce-checkout #payment div.payment_box::before {
		border-color: transparent transparent #f3f4f6 transparent;
	}
	
	.dark .woocommerce-checkout #payment div.payment_box::before {
		border-color: transparent transparent #1f2937 transparent;
	}
	
	.woocommerce-checkout #payment div.form-row {
		padding: 1rem 0 0;
		margin: 0;
	}
	
	.woocommerce-checkout #place_order {
		width: 100%;
		padding: 0.875rem 1.5rem;
		font-size: 1rem;
		font-weight: 500;
	}
	
	/* Mobile styles */
	@media (max-width: 767px) {
		.woocommerce-checkout .form-row-first,
		.woocommerce-checkout .form-row-last {
			float: none;
			width: 100%;
		}
	}
</style>