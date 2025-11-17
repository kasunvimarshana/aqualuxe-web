<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-shipping-fields">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h3 id="ship-to-different-address" class="text-xl font-medium mb-4">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-center">
				<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox rounded border-gray-300 dark:border-dark-600 dark:bg-dark-700 text-primary-600 focus:ring-primary-500 mr-2" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'aqualuxe' ); ?></span>
			</label>
		</h3>

		<div class="shipping_address bg-gray-50 dark:bg-dark-750 p-4 rounded-md mt-4 <?php echo ( ! apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ) ) ? 'hidden' : ''; ?>">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="woocommerce-shipping-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
				<?php
				$fields = $checkout->get_checkout_fields( 'shipping' );

				foreach ( $fields as $key => $field ) {
					$field_classes = isset( $field['class'] ) ? $field['class'] : array();
					
					// Add custom classes for styling
					$field['class'][] = 'form-row';
					
					// Add column span classes based on field
					if ( in_array( $key, array( 'shipping_address_1', 'shipping_address_2', 'shipping_company', 'shipping_state', 'shipping_city' ), true ) ) {
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

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>
</div>
<div class="woocommerce-additional-fields mt-8">
	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<h3 class="text-xl font-medium mb-4"><?php esc_html_e( 'Additional information', 'aqualuxe' ); ?></h3>

		<div class="woocommerce-additional-fields__field-wrapper">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php
				// Add custom input classes
				$field['input_class'][] = 'block w-full rounded-md border-gray-300 dark:border-dark-600 dark:bg-dark-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50';
				
				// Add custom label classes
				$field['label_class'][] = 'block text-sm font-medium text-dark-700 dark:text-dark-200 mb-1';
				
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				?>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>