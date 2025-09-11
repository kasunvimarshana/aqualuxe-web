<?php if (! defined('ABSPATH')) { exit; } ?>
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Company Info -->
                <div class="footer-section">
                    <h3 class="footer-title"><?php bloginfo('name'); ?></h3>
                    <p class="footer-description"><?php bloginfo('description'); ?></p>
                    <p class="footer-tagline"><?php esc_html_e('Bringing elegance to aquatic life – globally', 'aqualuxe'); ?></p>
                    
                    <!-- Social Links -->
                    <div class="social-links">
                        <?php 
                        $social_links = [
                            'facebook' => get_theme_mod('aqualuxe_facebook_url', ''),
                            'twitter' => get_theme_mod('aqualuxe_twitter_url', ''),
                            'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
                            'youtube' => get_theme_mod('aqualuxe_youtube_url', ''),
                        ];
                        
                        foreach ($social_links as $platform => $url) :
                            if ($url) : ?>
                                <a href="<?php echo esc_url($url); ?>" class="social-link" aria-label="<?php echo esc_attr(ucfirst($platform)); ?>" target="_blank" rel="noopener">
                                    <?php echo aqualuxe_get_social_icon($platform); ?>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h3 class="footer-title"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                    <?php 
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ]); 
                    ?>
                </div>

                <!-- Contact Info -->
                <div class="footer-section">
                    <h3 class="footer-title"><?php esc_html_e('Contact', 'aqualuxe'); ?></h3>
                    <div class="contact-info">
                        <?php 
                        $contact_info = [
                            'address' => get_theme_mod('aqualuxe_address', ''),
                            'phone' => get_theme_mod('aqualuxe_phone', ''),
                            'email' => get_theme_mod('aqualuxe_email', ''),
                        ];
                        
                        if ($contact_info['address']) : ?>
                            <p class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <?php echo esc_html($contact_info['address']); ?>
                            </p>
                        <?php endif;
                        
                        if ($contact_info['phone']) : ?>
                            <p class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:<?php echo esc_attr($contact_info['phone']); ?>"><?php echo esc_html($contact_info['phone']); ?></a>
                            </p>
                        <?php endif;
                        
                        if ($contact_info['email']) : ?>
                            <p class="contact-item">
                                <svg class="contact-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:<?php echo esc_attr($contact_info['email']); ?>"><?php echo esc_html($contact_info['email']); ?></a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Newsletter Signup -->
                <div class="footer-section">
                    <h3 class="footer-title"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
                    <p class="newsletter-description"><?php esc_html_e('Get the latest updates on new arrivals, care guides, and special offers.', 'aqualuxe'); ?></p>
                    
                    <form class="newsletter-form" method="post" action="<?php echo esc_url(home_url('/')); ?>">
                        <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
                        <div class="newsletter-input-group">
                            <label for="newsletter-email" class="sr-only"><?php esc_html_e('Email Address', 'aqualuxe'); ?></label>
                            <input 
                                type="email" 
                                id="newsletter-email" 
                                name="newsletter_email" 
                                placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>"
                                required
                                class="newsletter-input"
                            >
                            <button type="submit" class="newsletter-button">
                                <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">
                        © <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?>. 
                        <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="footer-legal">
                        <a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a>
                        <a href="<?php echo esc_url(home_url('/terms')); ?>"><?php esc_html_e('Terms of Service', 'aqualuxe'); ?></a>
                        <a href="<?php echo esc_url(home_url('/shipping')); ?>"><?php esc_html_e('Shipping & Returns', 'aqualuxe'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
