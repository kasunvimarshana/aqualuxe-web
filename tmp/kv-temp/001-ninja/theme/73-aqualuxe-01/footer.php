        </main><!-- #main -->
        
        <!-- Footer -->
        <footer id="colophon" class="site-footer bg-dark-900 text-white" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
            <div class="footer-content py-12">
                <div class="container mx-auto px-4">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        <!-- Footer Widget Area 1 -->
                        <div class="footer-widget">
                            <?php if (is_active_sidebar('footer-1')): ?>
                                <?php dynamic_sidebar('footer-1'); ?>
                            <?php else: ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-bold mb-4"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h3>
                                    <p class="text-gray-300 mb-4">
                                        <?php esc_html_e('Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and luxury aquarium solutions.', 'aqualuxe'); ?>
                                    </p>
                                    <?php if (has_custom_logo()): ?>
                                        <div class="footer-logo">
                                            <?php the_custom_logo(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer Widget Area 2 -->
                        <div class="footer-widget">
                            <?php if (is_active_sidebar('footer-2')): ?>
                                <?php dynamic_sidebar('footer-2'); ?>
                            <?php else: ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-bold mb-4"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Home', 'aqualuxe'); ?></a></li>
                                        <?php if (aqualuxe_is_woocommerce_active()): ?>
                                            <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                                        <?php endif; ?>
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('wholesale'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Wholesale', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('export'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Export', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer Widget Area 3 -->
                        <div class="footer-widget">
                            <?php if (is_active_sidebar('footer-3')): ?>
                                <?php dynamic_sidebar('footer-3'); ?>
                            <?php else: ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-bold mb-4"><?php esc_html_e('Business', 'aqualuxe'); ?></h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('trade-in'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Trade-In Program', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('events'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Events', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                                        <?php if (aqualuxe_is_woocommerce_active()): ?>
                                            <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="text-gray-300 hover:text-primary-400 transition-colors"><?php esc_html_e('My Account', 'aqualuxe'); ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer Widget Area 4 -->
                        <div class="footer-widget">
                            <?php if (is_active_sidebar('footer-4')): ?>
                                <?php dynamic_sidebar('footer-4'); ?>
                            <?php else: ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-bold mb-4"><?php esc_html_e('Contact Info', 'aqualuxe'); ?></h3>
                                    <div class="contact-info space-y-3">
                                        <div class="contact-item flex items-start space-x-3">
                                            <svg class="w-5 h-5 mt-1 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-gray-300"><?php echo esc_html(get_theme_mod('aqualuxe_address', '123 Aquarium Street, Marine City, MC 12345')); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item flex items-start space-x-3">
                                            <svg class="w-5 h-5 mt-1 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <div>
                                                <a href="tel:<?php echo esc_attr(get_theme_mod('aqualuxe_phone', '+1-234-567-8900')); ?>" class="text-gray-300 hover:text-primary-400 transition-colors">
                                                    <?php echo esc_html(get_theme_mod('aqualuxe_phone', '+1-234-567-8900')); ?>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item flex items-start space-x-3">
                                            <svg class="w-5 h-5 mt-1 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_email', 'info@aqualuxe.com')); ?>" class="text-gray-300 hover:text-primary-400 transition-colors">
                                                    <?php echo esc_html(get_theme_mod('aqualuxe_email', 'info@aqualuxe.com')); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Social Media Links -->
                                    <div class="social-links mt-6">
                                        <h4 class="text-sm font-semibold mb-3"><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h4>
                                        <div class="flex space-x-3">
                                            <?php if (get_theme_mod('aqualuxe_facebook_url')): ?>
                                                <a href="<?php echo esc_url(get_theme_mod('aqualuxe_facebook_url')); ?>" target="_blank" rel="noopener" class="social-link p-2 bg-gray-800 hover:bg-primary-600 rounded-full transition-colors" aria-label="<?php esc_attr_e('Facebook', 'aqualuxe'); ?>">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (get_theme_mod('aqualuxe_twitter_url')): ?>
                                                <a href="<?php echo esc_url(get_theme_mod('aqualuxe_twitter_url')); ?>" target="_blank" rel="noopener" class="social-link p-2 bg-gray-800 hover:bg-primary-600 rounded-full transition-colors" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (get_theme_mod('aqualuxe_instagram_url')): ?>
                                                <a href="<?php echo esc_url(get_theme_mod('aqualuxe_instagram_url')); ?>" target="_blank" rel="noopener" class="social-link p-2 bg-gray-800 hover:bg-primary-600 rounded-full transition-colors" aria-label="<?php esc_attr_e('Instagram', 'aqualuxe'); ?>">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.174.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.749-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-12.014C24.007 5.36 18.641.001 12.017.001z"/></svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (get_theme_mod('aqualuxe_linkedin_url')): ?>
                                                <a href="<?php echo esc_url(get_theme_mod('aqualuxe_linkedin_url')); ?>" target="_blank" rel="noopener" class="social-link p-2 bg-gray-800 hover:bg-primary-600 rounded-full transition-colors" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (get_theme_mod('aqualuxe_youtube_url')): ?>
                                                <a href="<?php echo esc_url(get_theme_mod('aqualuxe_youtube_url')); ?>" target="_blank" rel="noopener" class="social-link p-2 bg-gray-800 hover:bg-primary-600 rounded-full transition-colors" aria-label="<?php esc_attr_e('YouTube', 'aqualuxe'); ?>">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom border-t border-gray-800 py-6">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                        
                        <!-- Copyright -->
                        <div class="footer-copyright text-gray-400 text-sm">
                            <p>
                                <?php
                                printf(
                                    /* translators: 1: Copyright year, 2: Site name */
                                    esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
                                    date('Y'),
                                    get_bloginfo('name')
                                );
                                ?>
                            </p>
                        </div>
                        
                        <!-- Footer Menu -->
                        <nav class="footer-nav" role="navigation" aria-label="<?php esc_attr_e('Footer menu', 'aqualuxe'); ?>">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'footer',
                                'menu_class' => 'footer-menu flex items-center space-x-6 text-sm',
                                'container' => false,
                                'depth' => 1,
                                'fallback_cb' => 'aqualuxe_default_footer_menu',
                            ]);
                            ?>
                        </nav>
                        
                        <!-- Theme Credit -->
                        <div class="theme-credit text-gray-500 text-xs">
                            <?php
                            printf(
                                /* translators: %s: Theme name */
                                esc_html__('Powered by %s', 'aqualuxe'),
                                '<a href="https://aqualuxe.com" class="hover:text-primary-400 transition-colors">AquaLuxe</a>'
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer><!-- #colophon -->
        
        <!-- Newsletter Popup (if enabled) -->
        <?php if (get_theme_mod('aqualuxe_newsletter_popup_enabled', false) && !isset($_COOKIE['aqualuxe_newsletter_shown'])): ?>
            <div id="newsletter-popup" class="newsletter-popup fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="newsletter-modal bg-white dark:bg-dark-800 rounded-lg max-w-md w-full p-6 relative">
                    <button type="button" class="newsletter-close absolute top-4 right-4 text-gray-400 hover:text-gray-600" aria-label="<?php esc_attr_e('Close newsletter popup', 'aqualuxe'); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <div class="newsletter-content text-center">
                        <div class="newsletter-icon mb-4">
                            <svg class="w-12 h-12 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_theme_mod('aqualuxe_newsletter_popup_title', __('Stay Updated', 'aqualuxe'))); ?></h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6"><?php echo esc_html(get_theme_mod('aqualuxe_newsletter_popup_description', __('Get the latest updates on new arrivals, care tips, and exclusive offers.', 'aqualuxe'))); ?></p>
                        
                        <form class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                            <div class="flex space-x-2">
                                <input type="email" name="email" placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>" class="flex-1 px-3 py-2 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" required>
                                <button type="submit" class="btn btn-primary px-4 py-2"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                            </div>
                            <input type="hidden" name="action" value="aqualuxe_newsletter_subscribe">
                            <?php wp_nonce_field('aqualuxe_newsletter_nonce', 'newsletter_nonce'); ?>
                        </form>
                        
                        <p class="text-xs text-gray-500 mt-4">
                            <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Back to Top Button -->
        <button type="button" id="back-to-top" class="back-to-top fixed bottom-6 right-6 p-3 bg-primary-600 text-white rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 opacity-0 invisible" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>
        
        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay fixed inset-0 bg-white dark:bg-dark-900 z-50 flex items-center justify-center" style="display: none;">
            <div class="loading-content text-center">
                <div class="loading-spinner mb-4"></div>
                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Loading...', 'aqualuxe'); ?></p>
            </div>
        </div>
        
    </div><!-- #page -->
    
    <?php wp_footer(); ?>
    
    <!-- Remove loading class on DOM ready -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.remove('loading');
            document.body.classList.add('loaded');
        });
    </script>
    
</body>
</html>

<?php
/**
 * Default footer menu fallback
 */
function aqualuxe_default_footer_menu() {
    echo '<ul class="footer-menu flex items-center space-x-6 text-sm">';
    echo '<li><a href="' . esc_url(get_privacy_policy_url()) . '" class="text-gray-400 hover:text-primary-400 transition-colors">' . esc_html__('Privacy Policy', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('terms'))) . '" class="text-gray-400 hover:text-primary-400 transition-colors">' . esc_html__('Terms & Conditions', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('shipping-returns'))) . '" class="text-gray-400 hover:text-primary-400 transition-colors">' . esc_html__('Shipping & Returns', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('contact'))) . '" class="text-gray-400 hover:text-primary-400 transition-colors">' . esc_html__('Contact', 'aqualuxe') . '</a></li>';
    echo '</ul>';
}
?>
