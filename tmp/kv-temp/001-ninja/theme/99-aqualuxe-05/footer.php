<?php
/**
 * The template for displaying the footer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-900 text-white">
        
        <!-- Main footer content -->
        <div class="footer-main py-12">
            <div class="container mx-auto px-4">
                
                <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
                    <div class="footer-widget-area grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                            <div class="footer-widget-column">
                                <?php dynamic_sidebar( 'footer-4' ); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php else : ?>
                    
                    <!-- Default footer content if no widgets -->
                    <div class="footer-widget-area grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        <!-- About column -->
                        <div class="footer-widget-column">
                            <h3 class="widget-title text-lg font-semibold text-white mb-4">
                                <?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?>
                            </h3>
                            <div class="widget">
                                <p class="text-gray-300 text-sm leading-relaxed">
                                    <?php esc_html_e( 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and custom aquarium solutions.', 'aqualuxe' ); ?>
                                </p>
                                <div class="social-links flex space-x-4 mt-4">
                                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.611-3.197-1.559-.748-.948-1.197-2.25-1.197-3.654 0-1.404.449-2.706 1.197-3.654.749-.948 1.9-1.559 3.197-1.559s2.448.611 3.197 1.559c.748.948 1.197 2.25 1.197 3.654 0 1.404-.449 2.706-1.197 3.654-.749.948-1.9 1.559-3.197 1.559zm7.138 0c-1.297 0-2.448-.611-3.197-1.559-.748-.948-1.197-2.25-1.197-3.654 0-1.404.449-2.706 1.197-3.654.749-.948 1.9-1.559 3.197-1.559s2.448.611 3.197 1.559c.748.948 1.197 2.25 1.197 3.654 0 1.404-.449 2.706-1.197 3.654-.749.948-1.9 1.559-3.197 1.559z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="footer-widget-column">
                            <h3 class="widget-title text-lg font-semibold text-white mb-4">
                                <?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?>
                            </h3>
                            <div class="widget">
                                <ul class="space-y-2 text-sm">
                                    <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'FAQ', 'aqualuxe' ); ?></a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Shop Links (if WooCommerce is active) -->
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <div class="footer-widget-column">
                                <h3 class="widget-title text-lg font-semibold text-white mb-4">
                                    <?php esc_html_e( 'Shop', 'aqualuxe' ); ?>
                                </h3>
                                <div class="widget">
                                    <ul class="space-y-2 text-sm">
                                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'All Products', 'aqualuxe' ); ?></a></li>
                                        <li><a href="<?php echo esc_url( home_url( '/product-category/fish' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Fish', 'aqualuxe' ); ?></a></li>
                                        <li><a href="<?php echo esc_url( home_url( '/product-category/plants' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Plants', 'aqualuxe' ); ?></a></li>
                                        <li><a href="<?php echo esc_url( home_url( '/product-category/equipment' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Equipment', 'aqualuxe' ); ?></a></li>
                                        <li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="text-gray-300 hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Contact Info -->
                        <div class="footer-widget-column">
                            <h3 class="widget-title text-lg font-semibold text-white mb-4">
                                <?php esc_html_e( 'Contact Info', 'aqualuxe' ); ?>
                            </h3>
                            <div class="widget">
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-4 h-4 text-primary-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-gray-300"><?php esc_html_e( '123 Aquarium Street, Ocean City, AC 12345', 'aqualuxe' ); ?></span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-gray-300">+1 (555) 123-4567</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-300">info@aqualuxe.com</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-300"><?php esc_html_e( 'Mon - Sat: 9:00 AM - 6:00 PM', 'aqualuxe' ); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>

                <!-- Newsletter signup -->
                <div class="newsletter-signup mt-12 pt-8 border-t border-gray-700">
                    <div class="max-w-md mx-auto text-center">
                        <h3 class="text-lg font-semibold text-white mb-2">
                            <?php esc_html_e( 'Stay Updated', 'aqualuxe' ); ?>
                        </h3>
                        <p class="text-gray-300 text-sm mb-4">
                            <?php esc_html_e( 'Subscribe to our newsletter for the latest aquatic trends and exclusive offers.', 'aqualuxe' ); ?>
                        </p>
                        <form class="newsletter-form flex" method="post" action="#">
                            <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e( 'Enter your email', 'aqualuxe' ); ?>" class="flex-1 px-4 py-2 bg-gray-800 border border-gray-600 rounded-l-md text-white placeholder-gray-400 focus:outline-none focus:border-primary-500" required>
                            <button type="submit" class="btn-primary rounded-l-none">
                                <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer navigation -->
        <?php if ( has_nav_menu( 'footer' ) ) : ?>
            <div class="footer-navigation py-6 border-t border-gray-700">
                <div class="container mx-auto px-4">
                    <nav class="footer-nav text-center" aria-label="<?php esc_attr_e( 'Footer Navigation', 'aqualuxe' ); ?>">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'menu_class'     => 'footer-nav-menu flex flex-wrap justify-center space-x-6 text-sm',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </nav>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer bottom/copyright -->
        <div class="footer-bottom py-6 border-t border-gray-700">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-400">
                    <div class="copyright mb-4 md:mb-0">
                        <p>
                            <?php
                            printf(
                                esc_html__( '© %1$s %2$s. All rights reserved.', 'aqualuxe' ),
                                date( 'Y' ),
                                get_bloginfo( 'name' )
                            );
                            ?>
                        </p>
                    </div>
                    <div class="footer-links">
                        <ul class="flex space-x-6">
                            <li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Privacy Policy', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/terms-of-service' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Terms of Service', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/cookie-policy' ) ); ?>" class="hover:text-primary-400 transition-colors duration-200"><?php esc_html_e( 'Cookie Policy', 'aqualuxe' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <button class="back-to-top fixed bottom-8 right-8 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg opacity-0 pointer-events-none transition-all duration-300 z-40 focus:outline-none focus:ring-2 focus:ring-primary-500" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>