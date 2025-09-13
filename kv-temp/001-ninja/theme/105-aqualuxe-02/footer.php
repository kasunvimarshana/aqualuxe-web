    </main><!-- #main -->

    <footer id="colophon" class="site-footer bg-gray-900 text-white">
        <div class="footer-content">
            <!-- Main Footer Content -->
            <div class="footer-main section-padding">
                <div class="container mx-auto px-4">
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                        
                        <!-- Footer Widget Area 1 - About -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-1')) : ?>
                                <?php dynamic_sidebar('footer-1'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-semibold mb-4 text-primary-400">
                                        <?php bloginfo('name'); ?>
                                    </h3>
                                    <div class="widget-content text-gray-300">
                                        <p class="mb-4"><?php bloginfo('description'); ?></p>
                                        <p class="text-sm italic text-primary-300">
                                            <?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer Widget Area 2 - Quick Links -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-2')) : ?>
                                <?php dynamic_sidebar('footer-2'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-semibold mb-4 text-primary-400">
                                        <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                                    </h3>
                                    <div class="widget-content">
                                        <ul class="space-y-2 text-gray-300">
                                            <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="hover:text-primary-400 transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                                            <li><a href="<?php echo esc_url(home_url('/services')); ?>" class="hover:text-primary-400 transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                                            <li><a href="<?php echo esc_url(home_url('/events')); ?>" class="hover:text-primary-400 transition-colors"><?php esc_html_e('Events', 'aqualuxe'); ?></a></li>
                                            <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="hover:text-primary-400 transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                                            <?php if (class_exists('WooCommerce')) : ?>
                                                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="hover:text-primary-400 transition-colors"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer Widget Area 3 - Services -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-3')) : ?>
                                <?php dynamic_sidebar('footer-3'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-semibold mb-4 text-primary-400">
                                        <?php esc_html_e('Our Services', 'aqualuxe'); ?>
                                    </h3>
                                    <div class="widget-content">
                                        <ul class="space-y-2 text-gray-300">
                                            <li><span class="hover:text-primary-400 transition-colors"><?php esc_html_e('Aquarium Design', 'aqualuxe'); ?></span></li>
                                            <li><span class="hover:text-primary-400 transition-colors"><?php esc_html_e('Maintenance Services', 'aqualuxe'); ?></span></li>
                                            <li><span class="hover:text-primary-400 transition-colors"><?php esc_html_e('Rare Fish Breeding', 'aqualuxe'); ?></span></li>
                                            <li><span class="hover:text-primary-400 transition-colors"><?php esc_html_e('Consultation', 'aqualuxe'); ?></span></li>
                                            <li><span class="hover:text-primary-400 transition-colors"><?php esc_html_e('Training Programs', 'aqualuxe'); ?></span></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer Widget Area 4 - Contact Info -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-4')) : ?>
                                <?php dynamic_sidebar('footer-4'); ?>
                            <?php else : ?>
                                <div class="widget">
                                    <h3 class="widget-title text-xl font-semibold mb-4 text-primary-400">
                                        <?php esc_html_e('Contact Info', 'aqualuxe'); ?>
                                    </h3>
                                    <div class="widget-content text-gray-300">
                                        <div class="contact-info space-y-3">
                                            <div class="contact-item flex items-start space-x-3">
                                                <svg class="w-5 h-5 mt-1 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span><?php esc_html_e('123 Aquatic Lane, Ocean City, AC 12345', 'aqualuxe'); ?></span>
                                            </div>
                                            <div class="contact-item flex items-start space-x-3">
                                                <svg class="w-5 h-5 mt-1 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <span>+1 (555) 123-4567</span>
                                            </div>
                                            <div class="contact-item flex items-start space-x-3">
                                                <svg class="w-5 h-5 mt-1 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>info@aqualuxe.com</span>
                                            </div>
                                        </div>

                                        <!-- Social Media Links -->
                                        <div class="social-links mt-6">
                                            <h4 class="text-sm font-semibold mb-3 text-primary-400"><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h4>
                                            <div class="social-icons flex space-x-3">
                                                <a href="#" class="social-icon text-gray-400 hover:text-primary-400 transition-colors" aria-label="Facebook">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="social-icon text-gray-400 hover:text-primary-400 transition-colors" aria-label="Twitter">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="social-icon text-gray-400 hover:text-primary-400 transition-colors" aria-label="Instagram">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.826-1.297C3.321 14.394 2.423 12.946 2.423 11.987c0-.959.898-2.407 2.2-3.704 1.378-.807 2.529-1.297 3.826-1.297s2.448.49 3.826 1.297c1.302 1.297 2.2 2.745 2.2 3.704 0 .959-.898 2.407-2.2 3.704-1.378.807-2.529 1.297-3.826 1.297z"/>
                                                    </svg>
                                                </a>
                                                <a href="#" class="social-icon text-gray-400 hover:text-primary-400 transition-colors" aria-label="LinkedIn">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom bg-gray-800 py-6">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                        
                        <!-- Copyright -->
                        <div class="copyright text-sm text-gray-400">
                            <p>
                                <?php
                                printf(
                                    /* translators: %1$s: current year, %2$s: site name */
                                    esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
                                    date('Y'),
                                    esc_html(get_bloginfo('name'))
                                );
                                ?>
                            </p>
                        </div>

                        <!-- Footer Navigation -->
                        <div class="footer-navigation">
                            <?php
                            if (has_nav_menu('footer')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'footer',
                                    'menu_id'        => 'footer-menu',
                                    'menu_class'     => 'footer-menu flex flex-wrap items-center space-x-6 text-sm',
                                    'container'      => false,
                                    'depth'          => 1,
                                ));
                            } else {
                                echo '<nav class="footer-menu">';
                                echo '<ul class="flex flex-wrap items-center space-x-6 text-sm text-gray-400">';
                                echo '<li><a href="' . esc_url(home_url('/privacy-policy')) . '" class="hover:text-primary-400 transition-colors">' . esc_html__('Privacy Policy', 'aqualuxe') . '</a></li>';
                                echo '<li><a href="' . esc_url(home_url('/terms-of-service')) . '" class="hover:text-primary-400 transition-colors">' . esc_html__('Terms of Service', 'aqualuxe') . '</a></li>';
                                echo '<li><a href="' . esc_url(home_url('/cookie-policy')) . '" class="hover:text-primary-400 transition-colors">' . esc_html__('Cookie Policy', 'aqualuxe') . '</a></li>';
                                echo '</ul>';
                                echo '</nav>';
                            }
                            ?>
                        </div>

                        <!-- Back to Top -->
                        <div class="back-to-top">
                            <button type="button" 
                                    class="back-to-top-button hidden p-2 bg-primary-600 hover:bg-primary-700 text-white rounded-full transition-colors"
                                    aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>