        <footer id="colophon" class="site-footer bg-ocean-900 text-white" role="contentinfo">
            
            <!-- Main footer content -->
            <div class="footer-main py-12">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        
                        <!-- Footer widget area 1 -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-1')) : ?>
                                <?php dynamic_sidebar('footer-1'); ?>
                            <?php else : ?>
                                <!-- Default content -->
                                <div class="widget widget_text">
                                    <h3 class="widget-title text-xl font-heading font-semibold mb-4">
                                        <?php bloginfo('name'); ?>
                                    </h3>
                                    <div class="textwidget">
                                        <p class="text-gray-300 mb-4">
                                            <?php 
                                            $description = get_bloginfo('description');
                                            echo $description ? esc_html($description) : esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe');
                                            ?>
                                        </p>
                                        
                                        <!-- Social media links -->
                                        <div class="social-links flex space-x-4">
                                            <?php
                                            $social_links = array(
                                                'facebook'  => get_theme_mod('aqualuxe_facebook_url', ''),
                                                'twitter'   => get_theme_mod('aqualuxe_twitter_url', ''),
                                                'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
                                                'youtube'   => get_theme_mod('aqualuxe_youtube_url', ''),
                                                'linkedin'  => get_theme_mod('aqualuxe_linkedin_url', ''),
                                            );
                                            
                                            foreach ($social_links as $platform => $url) {
                                                if (!empty($url)) {
                                                    echo '<a href="' . esc_url($url) . '" class="social-link text-gray-300 hover:text-aqua-400 transition-colors" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr(ucfirst($platform)) . '">';
                                                    
                                                    switch ($platform) {
                                                        case 'facebook':
                                                            echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
                                                            break;
                                                        case 'twitter':
                                                            echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>';
                                                            break;
                                                        case 'instagram':
                                                            echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.23 12.017c0-2.089 1.698-3.787 3.787-3.787s3.787 1.698 3.787 3.787-1.698 3.787-3.787 3.787-3.787-1.698-3.787-3.787zm9.965-4.849c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25zm2.31 1.828c-.128-2.688-1.994-4.554-4.682-4.682C13.823 4.187 10.177 4.187 8.177 4.314c-2.688.128-4.554 1.994-4.682 4.682C3.368 10.996 3.368 14.004 3.495 16.004c.128 2.688 1.994 4.554 4.682 4.682 2 .127 5.996.127 7.996 0 2.688-.128 4.554-1.994 4.682-4.682.127-2 .127-5.996 0-7.996z"/></svg>';
                                                            break;
                                                        case 'youtube':
                                                            echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>';
                                                            break;
                                                        case 'linkedin':
                                                            echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
                                                            break;
                                                    }
                                                    
                                                    echo '</a>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer widget area 2 -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-2')) : ?>
                                <?php dynamic_sidebar('footer-2'); ?>
                            <?php else : ?>
                                <!-- Default quick links -->
                                <div class="widget widget_nav_menu">
                                    <h3 class="widget-title text-xl font-heading font-semibold mb-4">
                                        <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                                    </h3>
                                    <ul class="menu space-y-2">
                                        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-300 hover:text-aqua-400 transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(home_url('/services')); ?>" class="text-gray-300 hover:text-aqua-400 transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(home_url('/blog')); ?>" class="text-gray-300 hover:text-aqua-400 transition-colors"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-gray-300 hover:text-aqua-400 transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                            <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="text-gray-300 hover:text-aqua-400 transition-colors"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer widget area 3 -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-3')) : ?>
                                <?php dynamic_sidebar('footer-3'); ?>
                            <?php else : ?>
                                <!-- Default contact info -->
                                <div class="widget widget_text">
                                    <h3 class="widget-title text-xl font-heading font-semibold mb-4">
                                        <?php esc_html_e('Contact Info', 'aqualuxe'); ?>
                                    </h3>
                                    <div class="textwidget space-y-3">
                                        <div class="contact-item flex items-start space-x-3">
                                            <svg class="w-5 h-5 mt-1 text-aqua-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <div class="text-gray-300">
                                                <p><?php echo esc_html(get_theme_mod('aqualuxe_address', '123 Aquatic Avenue, Ocean City, AC 12345')); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item flex items-start space-x-3">
                                            <svg class="w-5 h-5 mt-1 text-aqua-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <div class="text-gray-300">
                                                <p><?php echo esc_html(get_theme_mod('aqualuxe_phone', '+1 (555) 123-4567')); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item flex items-start space-x-3">
                                            <svg class="w-5 h-5 mt-1 text-aqua-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="text-gray-300">
                                                <p><a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_email', 'info@aqualuxe.com')); ?>" class="hover:text-aqua-400 transition-colors"><?php echo esc_html(get_theme_mod('aqualuxe_email', 'info@aqualuxe.com')); ?></a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer widget area 4 -->
                        <div class="footer-widget-area">
                            <?php if (is_active_sidebar('footer-4')) : ?>
                                <?php dynamic_sidebar('footer-4'); ?>
                            <?php else : ?>
                                <!-- Default newsletter signup -->
                                <div class="widget widget_text">
                                    <h3 class="widget-title text-xl font-heading font-semibold mb-4">
                                        <?php esc_html_e('Newsletter', 'aqualuxe'); ?>
                                    </h3>
                                    <div class="textwidget">
                                        <p class="text-gray-300 mb-4">
                                            <?php esc_html_e('Subscribe to our newsletter to get the latest updates on aquatic products and services.', 'aqualuxe'); ?>
                                        </p>
                                        
                                        <form class="newsletter-form" action="#" method="post">
                                            <div class="form-group mb-3">
                                                <label for="newsletter-email" class="sr-only"><?php esc_html_e('Email address', 'aqualuxe'); ?></label>
                                                <input type="email" id="newsletter-email" name="email" class="w-full px-4 py-2 rounded bg-ocean-800 border border-ocean-700 text-white placeholder-gray-400 focus:border-aqua-500 focus:outline-none" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" required>
                                            </div>
                                            <button type="submit" class="btn-primary w-full">
                                                <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <!-- Footer bottom -->
            <div class="footer-bottom bg-ocean-950 py-6">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        
                        <div class="copyright text-gray-400 text-sm mb-4 md:mb-0">
                            <p>&copy; <?php echo esc_html(date('Y')); ?> <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-aqua-400 transition-colors"><?php bloginfo('name'); ?></a>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
                        </div>
                        
                        <div class="footer-menu">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu flex space-x-6 text-sm',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ));
                            ?>
                            
                            <?php if (!has_nav_menu('footer')) : ?>
                                <ul class="footer-menu flex space-x-6 text-sm">
                                    <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="text-gray-400 hover:text-aqua-400 transition-colors"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo esc_url(home_url('/terms-of-service')); ?>" class="text-gray-400 hover:text-aqua-400 transition-colors"><?php esc_html_e('Terms of Service', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo esc_url(home_url('/cookie-policy')); ?>" class="text-gray-400 hover:text-aqua-400 transition-colors"><?php esc_html_e('Cookie Policy', 'aqualuxe'); ?></a></li>
                                </ul>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </footer>
        
    </div><!-- #page -->
    
    <!-- Back to top button -->
    <button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-aqua-500 hover:bg-aqua-600 text-white p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 invisible z-40" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
    
    <?php wp_footer(); ?>
    
</body>
</html>