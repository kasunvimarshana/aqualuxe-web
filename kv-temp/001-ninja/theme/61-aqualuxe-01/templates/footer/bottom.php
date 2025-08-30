<?php
/**
 * Template part for displaying the footer bottom
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get footer width
$footer_width = get_theme_mod( 'aqualuxe_footer_width', 'container' );

// Get copyright text
$copyright = get_theme_mod( 'aqualuxe_footer_copyright', '© ' . date( 'Y' ) . ' AquaLuxe. All rights reserved.' );

// Get payment icons
$payment_icons = get_theme_mod( 'aqualuxe_footer_payment_icons', true );
?>

<div class="footer-bottom">
    <div class="<?php echo esc_attr( $footer_width ); ?>">
        <div class="footer-bottom-inner">
            <div class="footer-copyright">
                <?php echo wp_kses_post( $copyright ); ?>
            </div>

            <?php if ( $payment_icons ) : ?>
                <div class="footer-payment-icons">
                    <img src="<?php echo esc_url( AQUALUXE_ASSETS_URI . 'images/payment-icons.png' ); ?>" alt="<?php esc_attr_e( 'Payment Methods', 'aqualuxe' ); ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>