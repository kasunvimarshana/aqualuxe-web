<?php
/**
 * Checkout Form
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aqualuxe' ) ) );
    return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <div class="checkout-container">
        <?php if ( $checkout->get_checkout_fields() ) : ?>
            <div class="checkout-details">
                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                
                <div class="checkout-sections" id="customer_details">
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <h3><?php esc_html_e( 'Billing details', 'aqualuxe' ); ?></h3>
                        </div>
                        <div class="checkout-section-content">
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
                    
                    <div class="checkout-section">
                        <div class="checkout-section-header">
                            <h3><?php esc_html_e( 'Shipping details', 'aqualuxe' ); ?></h3>
                        </div>
                        <div class="checkout-section-content">
                            <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
                                <div class="shipping-address-toggle">
                                    <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
                                    <label for="ship-to-different-address-checkbox" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                        <?php esc_html_e( 'Ship to a different address?', 'aqualuxe' ); ?>
                                    </label>
                                </div>
                                
                                <div class="shipping-address" id="shipping_address">
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
                            <?php endif; ?>
                            
                            <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
                            
                            <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                                <div class="order-notes">
                                    <h3><?php esc_html_e( 'Additional information', 'aqualuxe' ); ?></h3>
                                    
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
                </div>
                
                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
            </div>
        <?php endif; ?>
        
        <div class="checkout-order">
            <div class="checkout-section">
                <div class="checkout-section-header">
                    <h3><?php esc_html_e( 'Your order', 'aqualuxe' ); ?></h3>
                </div>
                <div class="checkout-section-content">
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                    
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>
                    
                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                </div>
            </div>
        </div>
    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>