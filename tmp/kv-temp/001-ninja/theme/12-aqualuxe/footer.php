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
        <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                                <div class="footer-widget">
                                    <?php dynamic_sidebar( 'footer-1' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                                <div class="footer-widget">
                                    <?php dynamic_sidebar( 'footer-2' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                                <div class="footer-widget">
                                    <?php dynamic_sidebar( 'footer-3' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                                <div class="footer-widget">
                                    <?php dynamic_sidebar( 'footer-4' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="footer-newsletter">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="newsletter-content">
                            <h3><?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) ) ); ?></h3>
                            <p><?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_text', __( 'Stay updated with our latest products, offers, and aquatic care tips.', 'aqualuxe' ) ) ); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="newsletter-form">
                            <?php 
                            // Newsletter form shortcode from theme options
                            $newsletter_shortcode = get_theme_mod( 'aqualuxe_newsletter_shortcode', '' );
                            if ( $newsletter_shortcode ) {
                                echo do_shortcode( $newsletter_shortcode );
                            } else {
                                // Default newsletter form
                                ?>
                                <form class="default-newsletter-form">
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" required>
                                        <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="copyright">
                            <?php
                            $copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '&copy; ' . date( 'Y' ) . ' AquaLuxe. All Rights Reserved.' );
                            echo wp_kses_post( $copyright_text );
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php if ( has_nav_menu( 'footer' ) ) : ?>
                            <nav class="footer-navigation">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'footer',
                                        'menu_id'        => 'footer-menu',
                                        'depth'          => 1,
                                        'container'      => false,
                                        'menu_class'     => 'footer-menu',
                                    )
                                );
                                ?>
                            </nav>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'aqualuxe_show_payment_icons', true ) ) : ?>
                            <div class="payment-methods">
                                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/payment-methods.png' ); ?>" alt="<?php esc_attr_e( 'Payment Methods', 'aqualuxe' ); ?>">
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
        </a>
    <?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>