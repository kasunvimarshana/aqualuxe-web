<?php
/**
 * Checkout Payment Section
 *
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment mt-8">
    <div class="payment-methods-section bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
            <?php esc_html_e( 'Payment methods', 'aqualuxe' ); ?>
        </h3>
        
        <?php if ( WC()->cart->needs_payment() ) : ?>
            <ul class="wc_payment_methods payment_methods methods space-y-4">
                <?php
                if ( ! empty( $available_gateways ) ) {
                    foreach ( $available_gateways as $gateway ) {
                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                    }
                } else {
                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200 p-4 rounded-md">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'aqualuxe' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'aqualuxe' ) ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="place-order-section mt-6">
        <noscript>
            <?php
            /* translators: $1 and $2 opening and closing emphasis tags respectively */
            printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'aqualuxe' ), '<em>', '</em>' );
            ?>
            <br/><button type="submit" class="button alt w-full mt-4 px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium rounded transition-colors duration-300" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'aqualuxe' ); ?>"><?php esc_html_e( 'Update totals', 'aqualuxe' ); ?></button>
        </noscript>

        <?php wc_get_template( 'checkout/terms.php' ); ?>

        <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

        <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt w-full mt-4 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded transition-colors duration-300" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

        <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
    </div>
</div>
<?php
if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
}