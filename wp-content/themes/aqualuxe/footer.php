<?php
/**
 * The footer template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

    </main><!-- #main-content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            
            <!-- Footer Widgets -->
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
                <div class="footer-widgets">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                            <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                                <div class="footer-widget-area footer-widget-<?php echo esc_attr( $i ); ?>">
                                    <?php dynamic_sidebar( 'footer-' . $i ); ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    
                    <div class="footer-info">
                        <!-- Copyright -->
                        <div class="copyright">
                            <p>
                                <?php
                                printf(
                                    esc_html__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ),
                                    esc_html( date( 'Y' ) ),
                                    esc_html( get_bloginfo( 'name' ) )
                                );
                                ?>
                            </p>
                        </div>
                        
                        <!-- Theme Credit -->
                        <div class="theme-credit">
                            <p>
                                <?php esc_html_e( 'Bringing elegance to aquatic life – globally.', 'aqualuxe' ); ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Footer Navigation -->
                    <?php if ( has_nav_menu( 'footer' ) ) : ?>
                        <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'aqualuxe' ); ?>">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                            ) );
                            ?>
                        </nav>
                    <?php endif; ?>
                    
                </div>
            </div>
            
        </div>
    </footer><!-- #colophon -->

    <!-- Back to Top Button -->
    <button type="button" id="back-to-top" class="back-to-top btn-fab opacity-0 translate-y-16 transition-all duration-300" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>