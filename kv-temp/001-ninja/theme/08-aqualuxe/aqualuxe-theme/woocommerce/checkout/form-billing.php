<?php
/**
 * Checkout billing information form
 *
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-billing-fields">
    <?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
            <?php esc_html_e( 'Billing &amp; Shipping', 'aqualuxe' ); ?>
        </h3>
    <?php else : ?>
        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
            <?php esc_html_e( 'Billing details', 'aqualuxe' ); ?>
        </h3>
    <?php endif; ?>

    <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

    <div class="woocommerce-billing-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php
        $fields = $checkout->get_checkout_fields( 'billing' );

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

    <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
    <div class="woocommerce-account-fields mt-8">
        <?php if ( ! $checkout->is_registration_required() ) : ?>
            <p class="form-row form-row-wide create-account flex items-center">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox h-5 w-5 text-primary-600 transition duration-150 ease-in-out" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" />
                <label for="createaccount" class="ml-2 text-gray-700 dark:text-gray-300 cursor-pointer">
                    <?php esc_html_e( 'Create an account?', 'aqualuxe' ); ?>
                </label>
            </p>
        <?php endif; ?>

        <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

        <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
            <div class="create-account bg-gray-50 dark:bg-gray-700 p-4 rounded-md mt-4 <?php echo ( ! $checkout->is_registration_required() && ( ! is_user_logged_in() || ! $checkout->is_registration_enabled() ) ) ? 'hidden' : ''; ?>">
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    <?php esc_html_e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'aqualuxe' ); ?>
                </p>
                
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
                        <?php
                        $field['input_class'][] = 'w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500';
                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); 
                        ?>
                    <?php endforeach; ?>
                </div>
                
                <div class="clear"></div>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
    </div>
<?php endif; ?>