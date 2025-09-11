<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-900 text-white">
        <div class="container mx-auto px-4">
            <!-- Footer Widgets -->
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
                <div class="footer-widgets py-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar( 'footer-4' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Footer Menu -->
            <?php if ( has_nav_menu( 'footer' ) ) : ?>
                <div class="footer-navigation border-t border-gray-800 py-6">
                    <nav class="footer-menu">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'menu_class'     => 'flex flex-wrap justify-center space-x-6 text-sm',
                                'depth'          => 1,
                            )
                        );
                        ?>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- Footer Bottom -->
            <div class="footer-bottom border-t border-gray-800 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="footer-info text-sm text-gray-400">
                        <p>
                            <?php
                            /* translators: 1: Theme name, 2: Theme author. */
                            printf( esc_html__( 'Theme: %1$s by %2$s.', 'aqualuxe' ), 'AquaLuxe', '<a href="https://github.com/kasunvimarshana" class="text-aqua-400 hover:text-aqua-300">Kasun Vimarshana</a>' );
                            ?>
                        </p>
                        <p class="mt-1">
                            <?php
                            /* translators: 1: Current year, 2: Site name. */
                            printf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ), date( 'Y' ), get_bloginfo( 'name' ) );
                            ?>
                        </p>
                    </div>

                    <div class="footer-social mt-4 md:mt-0">
                        <?php if ( function_exists( 'aqualuxe_social_links' ) ) : ?>
                            <?php aqualuxe_social_links(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Back to Top Button -->
            <button id="back-to-top" class="back-to-top fixed bottom-6 right-6 bg-aqua-600 hover:bg-aqua-700 text-white p-3 rounded-full shadow-lg opacity-0 pointer-events-none transition-all duration-300 z-50" aria-label="<?php esc_attr_e( 'Back to Top', 'aqualuxe' ); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            </button>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>