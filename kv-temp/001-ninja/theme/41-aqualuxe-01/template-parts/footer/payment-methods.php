<?php
/**
 * Template part for displaying payment methods in the footer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Only display if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
    return;
}

// Get payment methods to display
$payment_methods = aqualuxe_get_payment_methods();

if ( empty( $payment_methods ) ) {
    return;
}
?>

<div class="payment-methods">
    <div class="flex flex-wrap justify-center items-center gap-4">
        <?php foreach ( $payment_methods as $method ) : ?>
            <div class="payment-method-icon" title="<?php echo esc_attr( $method['name'] ); ?>">
                <?php if ( ! empty( $method['icon'] ) ) : ?>
                    <img 
                        src="<?php echo esc_url( $method['icon'] ); ?>" 
                        alt="<?php echo esc_attr( $method['name'] ); ?>" 
                        class="h-6 w-auto object-contain"
                        loading="lazy"
                    >
                <?php else : ?>
                    <span class="text-sm text-white"><?php echo esc_html( $method['name'] ); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>