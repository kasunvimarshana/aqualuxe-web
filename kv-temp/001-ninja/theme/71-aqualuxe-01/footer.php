    <?php do_action('aqualuxe_before_footer'); ?>

    <footer id="colophon" class="site-footer bg-gray-900 text-white" role="contentinfo">
        <div class="footer-main py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Footer Widget 1 -->
                    <div class="footer-widget">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <?php dynamic_sidebar('footer-1'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4 text-white">
                                    <?php bloginfo('name'); ?>
                                </h3>
                                <div class="widget-content">
                                    <p class="text-gray-300 mb-4">
                                        <?php echo get_bloginfo('description'); ?>
                                    </p>
                                    <p class="text-gray-400 text-sm">
                                        <?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Footer Widget 2 -->
                    <div class="footer-widget">
                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <?php dynamic_sidebar('footer-2'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4 text-white">
                                    <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                                </h3>
                                <ul class="widget-content space-y-2">
                                    <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo esc_url(home_url('/services')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                                    <?php if (class_exists('WooCommerce')) : ?>
                                        <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                                    <?php endif; ?>
                                    <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Footer Widget 3 -->
                    <div class="footer-widget">
                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <?php dynamic_sidebar('footer-3'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4 text-white">
                                    <?php esc_html_e('Contact Info', 'aqualuxe'); ?>
                                </h3>
                                <div class="widget-content space-y-3">
                                    <?php if ($address = get_theme_mod('aqualuxe_contact_address')) : ?>
                                        <div class="flex items-start space-x-3">
                                            <svg class="w-5 h-5 text-primary-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-300"><?php echo esc_html($address); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($phone = get_theme_mod('aqualuxe_contact_phone')) : ?>
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                            </svg>
                                            <a href="tel:<?php echo esc_attr($phone); ?>" class="text-gray-300 hover:text-white transition-colors">
                                                <?php echo esc_html($phone); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($email = get_theme_mod('aqualuxe_contact_email')) : ?>
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                            <a href="mailto:<?php echo esc_attr($email); ?>" class="text-gray-300 hover:text-white transition-colors">
                                                <?php echo esc_html($email); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Footer Widget 4 -->
                    <div class="footer-widget">
                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <?php dynamic_sidebar('footer-4'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4 text-white">
                                    <?php esc_html_e('Follow Us', 'aqualuxe'); ?>
                                </h3>
                                <div class="widget-content">
                                    <p class="text-gray-300 mb-4">
                                        <?php esc_html_e('Stay connected for the latest updates and aquatic inspiration.', 'aqualuxe'); ?>
                                    </p>
                                    
                                    <div class="social-links flex space-x-3 mb-6">
                                        <?php
                                        $social_links = [
                                            'facebook' => get_theme_mod('aqualuxe_social_facebook'),
                                            'twitter' => get_theme_mod('aqualuxe_social_twitter'),
                                            'instagram' => get_theme_mod('aqualuxe_social_instagram'),
                                            'linkedin' => get_theme_mod('aqualuxe_social_linkedin'),
                                            'youtube' => get_theme_mod('aqualuxe_social_youtube'),
                                        ];
                                        
                                        foreach ($social_links as $platform => $url) :
                                            if ($url) :
                                        ?>
                                            <a href="<?php echo esc_url($url); ?>" 
                                               class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-300 hover:bg-primary-500 hover:text-white transition-all duration-300"
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               aria-label="<?php printf(esc_attr__('Follow us on %s', 'aqualuxe'), ucfirst($platform)); ?>">
                                                <?php echo aqualuxe_get_social_icon($platform, 'w-5 h-5'); ?>
                                            </a>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                    
                                    <!-- Newsletter Signup -->
                                    <div class="newsletter-signup">
                                        <form class="newsletter-form flex flex-col sm:flex-row gap-2" action="#" method="post">
                                            <input type="email" 
                                                   name="newsletter_email" 
                                                   placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>"
                                                   class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                                   required>
                                            <button type="submit" class="btn bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-md transition-colors duration-300">
                                                <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom py-6 border-t border-gray-800">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-400">
                    <div class="copyright mb-4 md:mb-0">
                        <p>
                            <?php
                            printf(
                                esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
                                date('Y'),
                                get_bloginfo('name')
                            );
                            ?>
                        </p>
                    </div>
                    
                    <div class="footer-links">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'menu_class'     => 'flex flex-wrap items-center space-x-6',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ]);
                        ?>
                        
                        <?php if (!has_nav_menu('footer')) : ?>
                            <ul class="flex flex-wrap items-center space-x-6">
                                <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/terms-conditions')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Terms & Conditions', 'aqualuxe'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/sitemap')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Sitemap', 'aqualuxe'); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php do_action('aqualuxe_after_footer'); ?>

</div><!-- #page -->

<!-- Back to Top Button -->
<button id="back-to-top" 
        class="fixed bottom-6 right-6 w-12 h-12 bg-primary-500 hover:bg-primary-600 text-white rounded-full shadow-lg opacity-0 invisible transition-all duration-300 z-40 flex items-center justify-center"
        aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>

<!-- Loading Spinner -->
<div id="loading-spinner" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center opacity-0 invisible transition-all duration-300">
    <div class="loading-animation">
        <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
