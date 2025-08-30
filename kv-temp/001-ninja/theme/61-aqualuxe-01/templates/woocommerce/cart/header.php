<?php
/**
 * Template part for displaying cart header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Return if WooCommerce is not active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}
?>

<div class="cart-header">
    <h1 class="cart-title"><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></h1>
    
    <div class="cart-steps">
        <div class="cart-step cart-step-active">
            <span class="cart-step-number">1</span>
            <span class="cart-step-label"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
        </div>
        <div class="cart-step">
            <span class="cart-step-number">2</span>
            <span class="cart-step-label"><?php esc_html_e( 'Checkout', 'aqualuxe' ); ?></span>
        </div>
        <div class="cart-step">
            <span class="cart-step-number">3</span>
            <span class="cart-step-label"><?php esc_html_e( 'Order Complete', 'aqualuxe' ); ?></span>
        </div>
    </div>
</div>