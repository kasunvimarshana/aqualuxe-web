<?php
/**
 * Template part for displaying the default footer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<footer id="colophon" class="site-footer footer-default">
    <?php
    // Footer widgets
    if ( get_theme_mod( 'aqualuxe_footer_widgets', true ) ) {
        $footer_columns = get_theme_mod( 'aqualuxe_footer_columns', 4 );
        
        if ( $footer_columns > 0 ) {
            $has_active_sidebar = false;
            
            for ( $i = 1; $i <= $footer_columns; $i++ ) {
                if ( is_active_sidebar( 'footer-' . $i ) ) {
                    $has_active_sidebar = true;
                    break;
                }
            }
            
            if ( $has_active_sidebar ) {
                ?>
                <div class="footer-widgets">
                    <div class="container">
                        <div class="row">
                            <?php
                            $column_class = 'col-md-' . ( 12 / $footer_columns );
                            
                            for ( $i = 1; $i <= $footer_columns; $i++ ) {
                                echo '<div class="' . esc_attr( $column_class ) . '">';
                                
                                if ( is_active_sidebar( 'footer-' . $i ) ) {
                                    dynamic_sidebar( 'footer-' . $i );
                                }
                                
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
    
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="footer-bottom-left">
                    <?php aqualuxe_footer_copyright(); ?>
                </div>
                
                <div class="footer-bottom-right">
                    <?php
                    // Footer menu
                    if ( has_nav_menu( 'footer' ) ) {
                        aqualuxe_footer_navigation();
                    }
                    
                    // Payment icons
                    if ( get_theme_mod( 'aqualuxe_footer_payment_icons', true ) ) {
                        $payment_icons = get_theme_mod( 'aqualuxe_payment_icons', array( 'visa', 'mastercard', 'amex', 'paypal' ) );
                        
                        if ( ! empty( $payment_icons ) ) {
                            echo '<div class="payment-icons">';
                            
                            foreach ( $payment_icons as $icon ) {
                                echo '<span class="payment-icon ' . esc_attr( $icon ) . '"></span>';
                            }
                            
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<?php
// Back to top button
if ( get_theme_mod( 'aqualuxe_back_to_top', true ) ) {
    aqualuxe_back_to_top();
}
?>