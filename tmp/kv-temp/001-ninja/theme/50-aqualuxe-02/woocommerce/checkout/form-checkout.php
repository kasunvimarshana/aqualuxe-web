<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
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

	<div class="aqualuxe-checkout-layout">
		<div class="checkout-main">
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<div class="checkout-steps">
					<div class="checkout-step active" id="step-customer-info">
						<div class="step-header">
							<div class="step-number">1</div>
							<h3 class="step-title"><?php esc_html_e( 'Customer Information', 'aqualuxe' ); ?></h3>
						</div>
						
						<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
							<div class="account-section">
								<div class="account-options">
									<div class="account-option">
										<input id="guest-checkout" type="radio" name="checkout_account_option" value="guest" checked>
										<label for="guest-checkout"><?php esc_html_e( 'Checkout as Guest', 'aqualuxe' ); ?></label>
									</div>
									
									<?php if ( $checkout->is_registration_enabled() ) : ?>
										<div class="account-option">
											<input id="create-account" type="radio" name="checkout_account_option" value="create_account">
											<label for="create-account"><?php esc_html_e( 'Create an Account', 'aqualuxe' ); ?></label>
										</div>
									<?php endif; ?>
									
									<div class="account-option">
										<input id="login-account" type="radio" name="checkout_account_option" value="login">
										<label for="login-account"><?php esc_html_e( 'Login', 'aqualuxe' ); ?></label>
									</div>
								</div>
								
								<div class="account-forms">
									<div id="login-form" class="account-form hidden">
										<?php woocommerce_login_form( array( 'redirect' => wc_get_checkout_url() ) ); ?>
									</div>
									
									<?php if ( $checkout->is_registration_enabled() ) : ?>
										<div id="create-account-fields" class="account-form hidden">
											<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>
											
											<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
												<div class="create-account">
													<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
														<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
													<?php endforeach; ?>
													<div class="clear"></div>
												</div>
											<?php endif; ?>
											
											<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
						
						<div class="col2-set" id="customer_details">
							<div class="col-1">
								<div class="billing-details">
									<h4><?php esc_html_e( 'Billing Details', 'aqualuxe' ); ?></h4>
									<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>
									
									<div class="woocommerce-billing-fields__field-wrapper">
										<?php
										$fields = $checkout->get_checkout_fields( 'billing' );
										
										foreach ( $fields as $key => $field ) {
											woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
										}
										?>
									</div>
									
									<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
								</div>
							</div>

							<div class="col-2">
								<div class="shipping-details">
									<h4><?php esc_html_e( 'Shipping Details', 'aqualuxe' ); ?></h4>
									
									<div class="shipping-address-toggle">
										<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
											<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> 
											<span><?php esc_html_e( 'Ship to a different address?', 'aqualuxe' ); ?></span>
										</label>
									</div>

									<div class="shipping-address" id="shipping-address-fields">
										<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
										
										<div class="woocommerce-shipping-fields__field-wrapper">
											<?php
											$fields = $checkout->get_checkout_fields( 'shipping' );
											
											foreach ( $fields as $key => $field ) {
												woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
											}
											?>
										</div>
										
										<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
									</div>
								</div>
								
								<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
								
								<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
									<div class="order-notes">
										<h4><?php esc_html_e( 'Additional Information', 'aqualuxe' ); ?></h4>
										
										<div class="woocommerce-additional-fields__field-wrapper">
											<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
												<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
								
								<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
							</div>
						</div>
						
						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
						
						<div class="step-navigation">
							<button type="button" class="button next-step" data-next="step-shipping-method"><?php esc_html_e( 'Continue to Shipping', 'aqualuxe' ); ?></button>
						</div>
					</div>
					
					<div class="checkout-step" id="step-shipping-method">
						<div class="step-header">
							<div class="step-number">2</div>
							<h3 class="step-title"><?php esc_html_e( 'Shipping Method', 'aqualuxe' ); ?></h3>
						</div>
						
						<div class="shipping-methods">
							<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
								<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
								
								<div class="shipping-method-options">
									<?php wc_cart_totals_shipping_html(); ?>
								</div>
								
								<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
							<?php endif; ?>
						</div>
						
						<div class="step-navigation">
							<button type="button" class="button prev-step" data-prev="step-customer-info"><?php esc_html_e( 'Back to Customer Info', 'aqualuxe' ); ?></button>
							<button type="button" class="button next-step" data-next="step-payment-method"><?php esc_html_e( 'Continue to Payment', 'aqualuxe' ); ?></button>
						</div>
					</div>
					
					<div class="checkout-step" id="step-payment-method">
						<div class="step-header">
							<div class="step-number">3</div>
							<h3 class="step-title"><?php esc_html_e( 'Payment Method', 'aqualuxe' ); ?></h3>
						</div>
						
						<div class="payment-methods">
							<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
							
							<h4 id="order_review_heading"><?php esc_html_e( 'Payment Options', 'aqualuxe' ); ?></h4>
							
							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
							
							<div id="order_review" class="woocommerce-checkout-review-order">
								<?php do_action( 'woocommerce_checkout_order_review' ); ?>
							</div>
							
							<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
						</div>
						
						<div class="step-navigation">
							<button type="button" class="button prev-step" data-prev="step-shipping-method"><?php esc_html_e( 'Back to Shipping', 'aqualuxe' ); ?></button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="checkout-sidebar">
			<div class="order-summary">
				<h3><?php esc_html_e( 'Order Summary', 'aqualuxe' ); ?></h3>
				
				<div class="order-summary-items">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						
						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
							?>
							<div class="order-summary-item">
								<div class="item-image">
									<?php echo $_product->get_image( 'thumbnail' ); ?>
									<span class="item-quantity"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
								</div>
								
								<div class="item-details">
									<h4 class="item-name"><?php echo esc_html( $_product->get_name() ); ?></h4>
									
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
									
									<div class="item-price">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
				
				<div class="order-summary-totals">
					<div class="summary-row subtotal">
						<div class="summary-label"><?php esc_html_e( 'Subtotal', 'aqualuxe' ); ?></div>
						<div class="summary-value"><?php wc_cart_totals_subtotal_html(); ?></div>
					</div>
					
					<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
						<div class="summary-row discount">
							<div class="summary-label"><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
							<div class="summary-value"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
						</div>
					<?php endforeach; ?>
					
					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
						<div class="summary-row shipping">
							<div class="summary-label"><?php esc_html_e( 'Shipping', 'aqualuxe' ); ?></div>
							<div class="summary-value shipping-value"><?php echo WC()->cart->get_cart_shipping_total(); ?></div>
						</div>
					<?php endif; ?>
					
					<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
						<div class="summary-row fee">
							<div class="summary-label"><?php echo esc_html( $fee->name ); ?></div>
							<div class="summary-value"><?php wc_cart_totals_fee_html( $fee ); ?></div>
						</div>
					<?php endforeach; ?>
					
					<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
						<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
							<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
								<div class="summary-row tax">
									<div class="summary-label"><?php echo esc_html( $tax->label ); ?></div>
									<div class="summary-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<div class="summary-row tax">
								<div class="summary-label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></div>
								<div class="summary-value"><?php wc_cart_totals_taxes_total_html(); ?></div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					
					<div class="summary-row total">
						<div class="summary-label"><?php esc_html_e( 'Total', 'aqualuxe' ); ?></div>
						<div class="summary-value"><?php wc_cart_totals_order_total_html(); ?></div>
					</div>
				</div>
				
				<?php if ( ! is_ajax() ) : ?>
					<div class="secure-checkout-info">
						<div class="secure-checkout-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
								<path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" />
							</svg>
						</div>
						<div class="secure-checkout-text">
							<p><?php esc_html_e( 'Your payment information is processed securely.', 'aqualuxe' ); ?></p>
						</div>
					</div>
					
					<div class="payment-icons">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-visa.svg' ); ?>" alt="<?php esc_attr_e( 'Visa', 'aqualuxe' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-mastercard.svg' ); ?>" alt="<?php esc_attr_e( 'Mastercard', 'aqualuxe' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-amex.svg' ); ?>" alt="<?php esc_attr_e( 'American Express', 'aqualuxe' ); ?>">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/payment-paypal.svg' ); ?>" alt="<?php esc_attr_e( 'PayPal', 'aqualuxe' ); ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>
jQuery(document).ready(function($) {
	// Account options toggle
	$('input[name="checkout_account_option"]').on('change', function() {
		var selectedOption = $(this).val();
		
		$('.account-form').addClass('hidden');
		
		if (selectedOption === 'login') {
			$('#login-form').removeClass('hidden');
			$('#createaccount').prop('checked', false);
		} else if (selectedOption === 'create_account') {
			$('#create-account-fields').removeClass('hidden');
			$('#createaccount').prop('checked', true);
		} else {
			$('#createaccount').prop('checked', false);
		}
	});
	
	// Shipping address toggle
	$('#ship-to-different-address-checkbox').on('change', function() {
		if ($(this).is(':checked')) {
			$('#shipping-address-fields').slideDown();
		} else {
			$('#shipping-address-fields').slideUp();
		}
	}).trigger('change');
	
	// Step navigation
	$('.next-step').on('click', function() {
		var currentStep = $(this).closest('.checkout-step');
		var nextStepId = $(this).data('next');
		
		// Validate fields in current step
		var isValid = true;
		currentStep.find('input[required], select[required], textarea[required]').each(function() {
			if (!$(this).val()) {
				isValid = false;
				$(this).addClass('error');
			} else {
				$(this).removeClass('error');
			}
		});
		
		if (!isValid) {
			alert('<?php esc_html_e( 'Please fill in all required fields before proceeding.', 'aqualuxe' ); ?>');
			return;
		}
		
		// Proceed to next step
		currentStep.removeClass('active');
		$('#' + nextStepId).addClass('active');
		
		// Scroll to top of the step
		$('html, body').animate({
			scrollTop: $('#' + nextStepId).offset().top - 100
		}, 500);
		
		// Update shipping method in order summary if needed
		if (nextStepId === 'step-payment-method') {
			var selectedShipping = $('input[name^="shipping_method"]:checked').parent().find('.woocommerce-Price-amount').text();
			if (selectedShipping) {
				$('.shipping-value').text(selectedShipping);
			}
		}
	});
	
	$('.prev-step').on('click', function() {
		var currentStep = $(this).closest('.checkout-step');
		var prevStepId = $(this).data('prev');
		
		currentStep.removeClass('active');
		$('#' + prevStepId).addClass('active');
		
		// Scroll to top of the step
		$('html, body').animate({
			scrollTop: $('#' + prevStepId).offset().top - 100
		}, 500);
	});
});
</script>