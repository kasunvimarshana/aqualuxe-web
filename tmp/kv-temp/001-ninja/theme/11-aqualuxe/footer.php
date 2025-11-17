<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <div class="footer-widget footer-widget-1">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <div class="footer-widget footer-widget-2">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <div class="footer-widget footer-widget-3">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                            <div class="footer-widget footer-widget-4">
                                <?php dynamic_sidebar( 'footer-4' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="site-info">
                            <?php
                            $footer_copyright = get_theme_mod( 'aqualuxe_footer_copyright' );
                            if ( $footer_copyright ) {
                                echo wp_kses_post( $footer_copyright );
                            } else {
                                /* translators: %1$s: Theme name, %2$s: Theme author. */
                                printf( esc_html__( '© %1$s AquaLuxe. Premium WordPress Theme by %2$s.', 'aqualuxe' ), date( 'Y' ), '<a href="https://ninjatech.ai/">NinjaTech AI</a>' );
                            }
                            ?>
                        </div><!-- .site-info -->
                    </div>
                    <div class="col-md-6">
                        <div class="footer-menu">
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'footer',
                                    'menu_id'        => 'footer-menu',
                                    'menu_class'     => 'footer-menu',
                                    'container'      => 'div',
                                    'container_class' => 'footer-menu-container',
                                    'depth'          => 1,
                                    'fallback_cb'    => false,
                                )
                            );
                            ?>
                        </div>
                        
                        <?php if ( get_theme_mod( 'aqualuxe_show_payment_icons', true ) ) : ?>
                            <div class="payment-icons">
                                <?php
                                $payment_methods = array(
                                    'visa'       => esc_html__( 'Visa', 'aqualuxe' ),
                                    'mastercard' => esc_html__( 'Mastercard', 'aqualuxe' ),
                                    'amex'       => esc_html__( 'American Express', 'aqualuxe' ),
                                    'discover'   => esc_html__( 'Discover', 'aqualuxe' ),
                                    'paypal'     => esc_html__( 'PayPal', 'aqualuxe' ),
                                    'apple-pay'  => esc_html__( 'Apple Pay', 'aqualuxe' ),
                                    'google-pay' => esc_html__( 'Google Pay', 'aqualuxe' ),
                                );
                                
                                foreach ( $payment_methods as $method => $label ) :
                                    if ( get_theme_mod( 'aqualuxe_payment_' . $method, true ) ) :
                                        ?>
                                        <span class="payment-icon payment-<?php echo esc_attr( $method ); ?>" aria-label="<?php echo esc_attr( $label ); ?>"></span>
                                        <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
    
    <?php if ( get_theme_mod( 'aqualuxe_back_to_top', true ) ) : ?>
        <a href="#" id="back-to-top" class="back-to-top">
            <i class="fas fa-chevron-up"></i>
            <span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'aqualuxe' ); ?></span>
        </a>
    <?php endif; ?>
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>