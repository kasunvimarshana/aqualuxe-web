<?php
/**
 * Footer template
 * 
 * @package AquaLuxe
 */

?>
    <footer id="colophon" class="site-footer mt-auto" role="contentinfo">
        
        <!-- Footer Widgets -->
        <?php aqualuxe_get_template_part('footer/footer-widgets'); ?>
        
        <!-- Site Footer -->
        <?php aqualuxe_get_template_part('footer/site-footer'); ?>
        
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
        
        <!-- Main Footer -->
        <div class="footer-main py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Brand Column -->
                    <div class="footer-brand">
                        <?php if (has_custom_logo()) : ?>
                            <div class="footer-logo mb-4">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php else : ?>
                            <h3 class="text-2xl font-bold text-white mb-4">
                                <?php bloginfo('name'); ?>
                            </h3>
                        <?php endif; ?>
                        
                        <p class="text-gray-400 mb-6">
                            <?php echo esc_html(aqualuxe_get_option('footer_description', 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and luxury aquarium solutions.')); ?>
                        </p>
                        
                        <!-- Social Media -->
                        <div class="flex space-x-4">
                            <?php if (aqualuxe_get_option('facebook_url')) : ?>
                                <a href="<?php echo esc_url(aqualuxe_get_option('facebook_url')); ?>" class="text-gray-400 hover:text-white transition-colors" target="_blank" rel="noopener">
                                    <i class="fab fa-facebook-f text-xl"></i>
                                    <span class="sr-only"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if (aqualuxe_get_option('instagram_url')) : ?>
                                <a href="<?php echo esc_url(aqualuxe_get_option('instagram_url')); ?>" class="text-gray-400 hover:text-white transition-colors" target="_blank" rel="noopener">
                                    <i class="fab fa-instagram text-xl"></i>
                                    <span class="sr-only"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if (aqualuxe_get_option('twitter_url')) : ?>
                                <a href="<?php echo esc_url(aqualuxe_get_option('twitter_url')); ?>" class="text-gray-400 hover:text-white transition-colors" target="_blank" rel="noopener">
                                    <i class="fab fa-twitter text-xl"></i>
                                    <span class="sr-only"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if (aqualuxe_get_option('youtube_url')) : ?>
                                <a href="<?php echo esc_url(aqualuxe_get_option('youtube_url')); ?>" class="text-gray-400 hover:text-white transition-colors" target="_blank" rel="noopener">
                                    <i class="fab fa-youtube text-xl"></i>
                                    <span class="sr-only"><?php esc_html_e('YouTube', 'aqualuxe'); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="footer-links">
                        <h4 class="text-lg font-semibold text-white mb-6"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h4>
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class' => 'space-y-2',
                            'container' => false,
                            'fallback_cb' => false,
                            'walker' => new AquaLuxe_Footer_Nav_Walker()
                        ]);
                        ?>
                    </div>
                    
                    <!-- Products/Services -->
                    <div class="footer-services">
                        <h4 class="text-lg font-semibold text-white mb-6"><?php esc_html_e('Our Services', 'aqualuxe'); ?></h4>
                        <ul class="space-y-2">
                            <li><a href="<?php echo esc_url(home_url('/services/aquarium-design/')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Aquarium Design', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services/maintenance/')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Maintenance', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services/consultation/')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Consultation', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services/breeding/')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Breeding Programs', 'aqualuxe'); ?></a></li>
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Shop Online', 'aqualuxe'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-contact">
                        <h4 class="text-lg font-semibold text-white mb-6"><?php esc_html_e('Contact Info', 'aqualuxe'); ?></h4>
                        <div class="space-y-3">
                            <?php if (aqualuxe_get_option('address')) : ?>
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-primary-500 mt-1 mr-3"></i>
                                    <span class="text-gray-400"><?php echo wp_kses_post(aqualuxe_get_option('address')); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (aqualuxe_get_option('phone')) : ?>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-primary-500 mr-3"></i>
                                    <a href="tel:<?php echo esc_attr(aqualuxe_get_option('phone')); ?>" class="text-gray-400 hover:text-white transition-colors">
                                        <?php echo esc_html(aqualuxe_get_option('phone')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (aqualuxe_get_option('email')) : ?>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-primary-500 mr-3"></i>
                                    <a href="mailto:<?php echo esc_attr(aqualuxe_get_option('email')); ?>" class="text-gray-400 hover:text-white transition-colors">
                                        <?php echo esc_html(aqualuxe_get_option('email')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (aqualuxe_get_option('business_hours')) : ?>
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-primary-500 mt-1 mr-3"></i>
                                    <div class="text-gray-400">
                                        <div class="font-semibold"><?php esc_html_e('Business Hours', 'aqualuxe'); ?></div>
                                        <div><?php echo wp_kses_post(aqualuxe_get_option('business_hours')); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- Newsletter Section -->
        <div class="footer-newsletter bg-gray-800 py-12">
            <div class="container mx-auto px-4 text-center">
                <h3 class="text-2xl font-bold text-white mb-4">
                    <?php esc_html_e('Stay Updated', 'aqualuxe'); ?>
                </h3>
                <p class="text-gray-400 mb-6 max-w-md mx-auto">
                    <?php esc_html_e('Subscribe to our newsletter for the latest aquatic care tips, new arrivals, and exclusive offers.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form max-w-md mx-auto flex gap-2" action="#" method="post">
                    <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>" class="flex-1 px-4 py-3 bg-gray-700 text-white placeholder-gray-400 border border-gray-600 rounded-lg focus:outline-none focus:border-primary-500" required>
                    <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="footer-bottom bg-gray-950 py-6">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                    
                    <div class="copyright mb-4 md:mb-0">
                        <p>
                            &copy; <?php echo date('Y'); ?> 
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-white hover:text-primary-500 transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>. 
                            <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?>
                        </p>
                    </div>
                    
                    <div class="footer-legal">
                        <ul class="flex space-x-6">
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Terms of Service', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Cookie Policy', 'aqualuxe'); ?></a></li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- Back to Top Button -->
        <button id="back-to-top" class="fixed bottom-6 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 opacity-0 invisible" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>
        
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>