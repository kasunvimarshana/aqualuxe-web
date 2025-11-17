<?php
/**
 * Template part for displaying footer bottom content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="footer-bottom">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="footer-bottom-inner">
            <div class="footer-copyright">
                <?php
                // Get footer copyright
                $copyright = get_theme_mod( 'aqualuxe_footer_copyright', sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ) );
                
                if ( ! empty( $copyright ) ) {
                    echo wp_kses_post( $copyright );
                }
                ?>
            </div>
            
            <?php
            // Display payment icons if WooCommerce is active
            if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_footer_payment', true ) ) {
                aqualuxe_footer_payment_icons();
            }
            ?>
        </div>
    </div>
</div>