<?php
/**
 * Checkout shipping information form
 *
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-shipping-fields">
    <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

        <h3 id="ship-to-different-address" class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-center">
                <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox h-5 w-5 text-primary-600 transition duration-150 ease-in-out" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
                <span class="ml-2 text-gray-900 dark:text-white"><?php esc_html_e( 'Ship to a different address?', 'aqualuxe' ); ?></span>
            </label>
        </h3>

        <div class="shipping_address mt-6" id="shipping_address" <?php echo ( ! apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ) ) ? 'style="display:none"' : ''; ?>>

            <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

            <div class="woocommerce-shipping-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php
                $fields = $checkout->get_checkout_fields( 'shipping' );

                foreach ( $fields as $key => $field ) {
                    $field['class'] = isset($field['class']) ? $field['class'] : array();
                    
                    // Add Tailwind classes to input fields
                    if (isset($field['type']) && $field['type'] === 'select') {
                        $field['input_class'][] = 'w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500';
                    } else {
                        $field['input_class'][] = 'w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500';
                    }
                    
                    // Add column span for full-width fields
                    if (in_array('form-row-wide', $field['class'])) {
                        $field['class'][] = 'md:col-span-2';
                    }
                    
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

        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
            <?php esc_html_e( 'Additional information', 'aqualuxe' ); ?>
        </h3>

        <div class="woocommerce-additional-fields__field-wrapper">
            <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                <?php 
                $field['input_class'][] = 'w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500';
                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); 
                ?>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>