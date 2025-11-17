<?php
/**
 * Footer template for AquaLuxe theme
 *
 * Displays the site footer with widgets, navigation, and copyright information.
 * Implements semantic HTML5, ARIA landmarks, and responsive design.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hook for adding content before footer
 * Used by modules like CTA sections, newsletter signup, etc.
 */
do_action('aqualuxe_before_footer');
?>

    <footer id="colophon" class="site-footer bg-gray-900 text-white" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
        
        <!-- Main footer content -->
        <div class="footer-main py-12">
            <div class="container mx-auto px-4">
                
                <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                    <div class="footer-widgets grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                        
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                <div class="footer-widget-area footer-widget-<?php echo esc_attr($i); ?>">
                                    <?php dynamic_sidebar('footer-' . $i); ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                    </div>
                <?php else : ?>
                    <!-- Default footer content when no widgets are configured -->
                    <div class="default-footer-content grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                        
                        <!-- About section -->
                        <div class="footer-about">
                            <h3 class="text-lg font-semibold mb-4 text-blue-400">
                                <?php bloginfo('name'); ?>
                            </h3>
                            <p class="text-gray-300 mb-4">
                                <?php 
                                $description = get_bloginfo('description');
                                echo $description ? esc_html($description) : esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe');
                                ?>
                            </p>
                            <div class="social-links flex space-x-4">
                                <!-- Social media links will be populated by customizer or modules -->
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200" aria-label="<?php esc_attr_e('Facebook', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200" aria-label="<?php esc_attr_e('Instagram', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.73-3.016-1.8C4.851 14.616 4.6 13.853 4.6 13c0-.853.25-1.616.833-2.188.568-1.07 1.719-1.8 3.016-1.8 1.297 0 2.448.73 3.016 1.8.583.572.833 1.335.833 2.188 0 .853-.25 1.616-.833 2.188-.568 1.07-1.719 1.8-3.016 1.8zm7.138 0c-1.297 0-2.448-.73-3.016-1.8-.583-.572-.833-1.335-.833-2.188 0-.853.25-1.616.833-2.188.568-1.07 1.719-1.8 3.016-1.8 1.297 0 2.448.73 3.016 1.8.583.572.833 1.335.833 2.188 0 .853-.25 1.616-.833 2.188-.568 1.07-1.719 1.8-3.016 1.8z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="footer-links">
                            <h3 class="text-lg font-semibold mb-4 text-blue-400">
                                <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                            </h3>
                            <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer navigation', 'aqualuxe'); ?>">
                                <?php if (has_nav_menu('footer')) : ?>
                                    <?php
                                    wp_nav_menu([
                                        'theme_location' => 'footer',
                                        'menu_class' => 'footer-menu space-y-2',
                                        'container' => false,
                                        'depth' => 1
                                    ]);
                                    ?>
                                <?php else : ?>
                                    <ul class="footer-menu space-y-2">
                                        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(home_url('/services')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(home_url('/blog')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                                    </ul>
                                <?php endif; ?>
                            </nav>
                        </div>
                        
                        <!-- WooCommerce Links (if active) -->
                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                            <div class="footer-shop-links">
                                <h3 class="text-lg font-semibold mb-4 text-blue-400">
                                    <?php esc_html_e('Shop', 'aqualuxe'); ?>
                                </h3>
                                <ul class="shop-menu space-y-2">
                                    <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('All Products', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('My Account', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('Cart', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo esc_url(wc_get_page_permalink('checkout')); ?>" class="text-gray-300 hover:text-blue-400 transition-colors duration-200"><?php esc_html_e('Checkout', 'aqualuxe'); ?></a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Contact Info -->
                        <div class="footer-contact">
                            <h3 class="text-lg font-semibold mb-4 text-blue-400">
                                <?php esc_html_e('Contact Info', 'aqualuxe'); ?>
                            </h3>
                            <div class="contact-info space-y-2 text-gray-300">
                                <div class="contact-item flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span><?php echo esc_html(get_theme_mod('footer_address', '123 Aquatic Street, Ocean City')); ?></span>
                                </div>
                                <div class="contact-item flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:<?php echo esc_attr(get_theme_mod('footer_phone', '+1-234-567-8900')); ?>" class="hover:text-blue-400 transition-colors duration-200">
                                        <?php echo esc_html(get_theme_mod('footer_phone', '+1-234-567-8900')); ?>
                                    </a>
                                </div>
                                <div class="contact-item flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:<?php echo esc_attr(get_theme_mod('footer_email', 'info@aqualuxe.com')); ?>" class="hover:text-blue-400 transition-colors duration-200">
                                        <?php echo esc_html(get_theme_mod('footer_email', 'info@aqualuxe.com')); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Footer bottom -->
        <div class="footer-bottom border-t border-gray-800 py-6">
            <div class="container mx-auto px-4">
                <div class="footer-bottom-content flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    
                    <!-- Copyright -->
                    <div class="footer-copyright text-gray-400 text-sm">
                        <p>
                            <?php
                            printf(
                                /* translators: 1: Copyright symbol, 2: Current year, 3: Site name */
                                esc_html__('%1$s %2$s %3$s. All rights reserved.', 'aqualuxe'),
                                '&copy;',
                                date('Y'),
                                get_bloginfo('name')
                            );
                            ?>
                        </p>
                        <p class="mt-1">
                            <?php
                            printf(
                                /* translators: 1: Theme name, 2: Theme author */
                                esc_html__('Powered by %1$s theme by %2$s', 'aqualuxe'),
                                '<a href="https://aqualuxe.com" class="hover:text-blue-400 transition-colors duration-200">AquaLuxe</a>',
                                '<a href="https://aqualuxe.com" class="hover:text-blue-400 transition-colors duration-200">AquaLuxe Team</a>'
                            );
                            ?>
                        </p>
                    </div>
                    
                    <!-- Legal links -->
                    <div class="footer-legal">
                        <nav class="legal-navigation" aria-label="<?php esc_attr_e('Legal navigation', 'aqualuxe'); ?>">
                            <ul class="legal-menu flex space-x-6 text-sm">
                                <li>
                                    <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                        <?php esc_html_e('Privacy Policy', 'aqualuxe'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/terms-conditions')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                        <?php esc_html_e('Terms & Conditions', 'aqualuxe'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(home_url('/cookies-policy')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                        <?php esc_html_e('Cookies Policy', 'aqualuxe'); ?>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php
/**
 * Hook for adding content before closing body tag
 * Used by modules like analytics, chat widgets, etc.
 */
do_action('aqualuxe_before_body_close');

wp_footer();
?>

<!-- Back to top button -->
<button 
    id="back-to-top" 
    class="back-to-top fixed bottom-6 right-6 z-50 p-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform translate-y-16 opacity-0 transition-all duration-300"
    aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>"
    style="display: none;"
>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>

</body>
</html>
