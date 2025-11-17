<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-billing-fields">
	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3 class="text-xl font-medium mb-4"><?php esc_html_e( 'Billing &amp; Shipping', 'aqualuxe' ); ?></h3>

	<?php else : ?>

		<h3 class="text-xl font-medium mb-4"><?php esc_html_e( 'Billing details', 'aqualuxe' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {
			$field_classes = isset( $field['class'] ) ? $field['class'] : array();
			
			// Add custom classes for styling
			$field['class'][] = 'form-row';
			
			// Add column span classes based on field
			if ( in_array( $key, array( 'billing_address_1', 'billing_address_2', 'billing_company', 'billing_state', 'billing_city' ), true ) ) {
				$field['class'][] = 'md:col-span-2';
			}
			
			// Add custom input classes
			$field['input_class'][] = 'block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50';
			
			// Add custom label classes
			$field['label_class'][] = 'block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1';
			
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields mt-8">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-center">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox rounded border-gray-300 dark:border-dark-600 dark:bg-dark-700 text-primary-600 focus:ring-primary-500 mr-2" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'aqualuxe' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account bg-gray-50 dark:bg-dark-750 p-4 rounded-md mt-4 <?php echo ( ! $checkout->is_registration_required() && ! $checkout->get_value( 'createaccount' ) ) ? 'hidden' : ''; ?>">
				<p class="text-sm text-dark-600 dark:text-dark-300 mb-4"><?php esc_html_e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'aqualuxe' ); ?></p>

				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php
					// Add custom input classes
					$field['input_class'][] = 'block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50';
					
					// Add custom label classes
					$field['label_class'][] = 'block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1';
					
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					?>
				<?php endforeach; ?>

				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>