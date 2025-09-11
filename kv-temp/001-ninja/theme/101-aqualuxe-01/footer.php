<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
            <div class="footer-widgets">
                <div class="footer-widget-area">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="footer-widget-area">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="footer-widget-area">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <div class="footer-widget">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'aqualuxe' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'menu_class'     => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>

            <div class="copyright">
                <p>
                    <?php
                    printf(
                        /* translators: 1: Copyright year, 2: Site name */
                        esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ),
                        esc_html( date_i18n( 'Y' ) ),
                        esc_html( get_bloginfo( 'name' ) )
                    );
                    ?>
                </p>
                <p class="theme-credit">
                    <?php
                    printf(
                        /* translators: %s: Theme name */
                        esc_html__( 'Powered by %s', 'aqualuxe' ),
                        '<a href="https://github.com/kasunvimarshana/aqualuxe-web" rel="nofollow">AquaLuxe</a>'
                    );
                    ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>