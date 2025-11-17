    </main><!-- #main -->

    <footer id="colophon" class="site-footer bg-luxe-900 text-white">
        
        <?php if (is_active_sidebar('sidebar-footer') || has_nav_menu('footer')) : ?>
        <div class="footer-main py-16">
            <div class="container mx-auto px-4">
                <div class="footer-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Company Info -->
                    <div class="footer-section">
                        <div class="site-branding mb-6">
                            <?php
                            $custom_logo_id = get_theme_mod('custom_logo');
                            if ($custom_logo_id) :
                                $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                                $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                                ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                                    <img src="<?php echo esc_url($logo_url); ?>" 
                                         alt="<?php echo esc_attr($logo_alt ?: get_bloginfo('name')); ?>"
                                         class="custom-logo h-10 w-auto filter brightness-0 invert">
                                </a>
                                <?php
                            else :
                                ?>
                                <h2 class="site-title text-xl font-bold">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-white hover:text-accent-400 transition-colors">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h2>
                                <?php
                            endif;
                            ?>
                        </div>
                        
                        <p class="text-gray-300 mb-4">
                            <?php 
                            $description = get_bloginfo('description', 'display');
                            echo $description ?: esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe');
                            ?>
                        </p>
                        
                        <!-- Social Media Links -->
                        <div class="social-media flex space-x-4">
                            <?php
                            $social_links = array(
                                'facebook' => get_theme_mod('aqualuxe_facebook_url', ''),
                                'twitter' => get_theme_mod('aqualuxe_twitter_url', ''),
                                'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
                                'youtube' => get_theme_mod('aqualuxe_youtube_url', ''),
                                'linkedin' => get_theme_mod('aqualuxe_linkedin_url', ''),
                            );
                            
                            foreach ($social_links as $platform => $url) :
                                if (!empty($url)) :
                                    ?>
                                    <a href="<?php echo esc_url($url); ?>" 
                                       class="text-gray-400 hover:text-white transition-colors" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       aria-label="<?php echo esc_attr(ucfirst($platform)); ?>">
                                        <?php aqualuxe_social_icon($platform); ?>
                                    </a>
                                    <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h3 class="footer-title text-lg font-semibold mb-4"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu space-y-2',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => 'aqualuxe_default_footer_menu',
                            'walker'         => new AquaLuxe_Walker_Footer_Nav_Menu(),
                        ));
                        ?>
                    </div>

                    <!-- Contact Info -->
                    <div class="footer-section">
                        <h3 class="footer-title text-lg font-semibold mb-4"><?php esc_html_e('Contact Info', 'aqualuxe'); ?></h3>
                        <div class="contact-info space-y-3">
                            <?php if ($address = get_theme_mod('aqualuxe_contact_address')) : ?>
                            <div class="contact-item flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 text-accent-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-300"><?php echo wp_kses_post($address); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($phone = get_theme_mod('aqualuxe_contact_phone')) : ?>
                            <div class="contact-item flex items-center space-x-3">
                                <svg class="w-5 h-5 text-accent-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^+0-9]/', '', $phone)); ?>" class="text-gray-300 hover:text-white transition-colors">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php if ($email = get_theme_mod('aqualuxe_contact_email')) : ?>
                            <div class="contact-item flex items-center space-x-3">
                                <svg class="w-5 h-5 text-accent-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="text-gray-300 hover:text-white transition-colors">
                                    <?php echo esc_html($email); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php if ($hours = get_theme_mod('aqualuxe_business_hours')) : ?>
                            <div class="contact-item flex items-start space-x-3">
                                <svg class="w-5 h-5 mt-1 text-accent-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-gray-300">
                                    <div class="font-medium"><?php esc_html_e('Business Hours', 'aqualuxe'); ?></div>
                                    <div class="text-sm"><?php echo wp_kses_post($hours); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="footer-section">
                        <h3 class="footer-title text-lg font-semibold mb-4"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
                        <p class="text-gray-300 mb-4">
                            <?php esc_html_e('Subscribe to our newsletter for the latest updates and exclusive offers.', 'aqualuxe'); ?>
                        </p>
                        
                        <form class="newsletter-form" method="post">
                            <div class="flex">
                                <input type="email" 
                                       name="newsletter_email" 
                                       placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" 
                                       required
                                       class="flex-1 px-4 py-2 bg-luxe-800 border border-luxe-700 rounded-l-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <button type="submit" 
                                        class="px-6 py-2 bg-primary-600 text-white rounded-r-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-luxe-900 transition-colors">
                                    <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer Bottom -->
        <div class="footer-bottom bg-luxe-950 py-6">
            <div class="container mx-auto px-4">
                <div class="footer-bottom-content flex flex-col md:flex-row justify-between items-center">
                    
                    <!-- Copyright -->
                    <div class="copyright text-sm text-gray-400 mb-4 md:mb-0">
                        <?php
                        $copyright_text = get_theme_mod('aqualuxe_copyright_text', '© ' . date('Y') . ' AquaLuxe. All rights reserved.');
                        echo wp_kses_post($copyright_text);
                        ?>
                    </div>

                    <!-- Footer Legal Links -->
                    <div class="footer-legal">
                        <ul class="flex space-x-6 text-sm">
                            <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a></li>
                            <li><a href="/terms-conditions/" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Terms & Conditions', 'aqualuxe'); ?></a></li>
                            <li><a href="/shipping-returns/" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Shipping & Returns', 'aqualuxe'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top fixed bottom-6 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all transform translate-y-16 opacity-0" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
