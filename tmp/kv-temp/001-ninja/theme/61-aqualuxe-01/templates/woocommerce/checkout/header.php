<?php
/**
 * Template part for displaying checkout header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Return if WooCommerce is not active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}
?>

<div class="checkout-header">
    <h1 class="checkout-title"><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></h1>
    
    <div class="checkout-steps">
        <div class="checkout-step">
            <span class="checkout-step-number">1</span>
            <span class="checkout-step-label"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
        </div>
        <div class="checkout-step checkout-step-active">
            <span class="checkout-step-number">2</span>
            <span class="checkout-step-label"><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></span>
        </div>
        <div class="checkout-step">
            <span class="checkout-step-number">3</span>
            <span class="checkout-step-label"><?php esc_html_e( 'Order Complete', 'aqualuxe' ); ?></span>
        </div>
    </div>
</div>