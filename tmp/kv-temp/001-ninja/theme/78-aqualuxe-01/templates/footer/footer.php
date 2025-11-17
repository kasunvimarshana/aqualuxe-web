<?php
/**
 * Footer template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$footer_text = get_theme_mod('aqualuxe_footer_text', '© 2024 AquaLuxe. All rights reserved.');
$social_links = aqualuxe_get_social_links();
?>

<footer class="site-footer bg-gray-900 text-white">
    <div class="footer-main py-12">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Footer Widget 1 -->
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h3>
                        <p class="text-gray-300 mb-4">
                            <?php esc_html_e('Bringing elegance to aquatic life through premium fish, plants, and equipment. Your trusted partner for aquarium excellence.', 'aqualuxe'); ?>
                        </p>
                        <?php aqualuxe_contact_info(); ?>
                    </div>
                <?php endif; ?>

                <!-- Footer Widget 2 -->
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                        <ul class="footer-links space-y-2">
                            <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/faq')); ?>" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('FAQ', 'aqualuxe'); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Footer Widget 3 -->
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Categories', 'aqualuxe'); ?></h3>
                        <ul class="footer-links space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Fish', 'aqualuxe'); ?></a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Plants', 'aqualuxe'); ?></a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Equipment', 'aqualuxe'); ?></a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors"><?php esc_html_e('Supplies', 'aqualuxe'); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Footer Widget 4 -->
                <?php if (is_active_sidebar('footer-4')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                <?php else : ?>
                    <div class="footer-widget">
                        <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
                        <p class="text-gray-300 mb-4">
                            <?php esc_html_e('Stay updated with our latest products and aquarium tips.', 'aqualuxe'); ?>
                        </p>
                        <form class="newsletter-form" action="#" method="post">
                            <div class="flex">
                                <input type="email" name="email" placeholder="<?php esc_attr_e('Your email', 'aqualuxe'); ?>" class="flex-1 px-4 py-2 rounded-l-lg text-gray-900 focus:outline-none" required>
                                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-r-lg hover:bg-primary-dark transition-colors">
                                    <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                </button>
                            </div>
                            <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="footer-bottom border-t border-gray-800 py-6">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="flex flex-col md:flex-row justify-between items-center">
                
                <!-- Copyright -->
                <div class="footer-copyright text-gray-400 text-sm mb-4 md:mb-0">
                    <?php echo wp_kses_post($footer_text); ?>
                </div>

                <!-- Social Links -->
                <?php if ($social_links) : ?>
                    <div class="footer-social flex space-x-4">
                        <?php foreach ($social_links as $network => $data) : ?>
                            <a href="<?php echo esc_url($data['url']); ?>" target="_blank" rel="noopener noreferrer" class="social-link text-gray-400 hover:text-white transition-colors" title="<?php echo esc_attr($data['label']); ?>">
                                <?php echo aqualuxe_get_social_icon($network); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Footer Menu -->
                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'aqualuxe'); ?>">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-nav-menu flex space-x-6 text-sm',
                            'container' => false,
                            'depth' => 1,
                        ]);
                        ?>
                    </nav>
                <?php else : ?>
                    <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'aqualuxe'); ?>">
                        <ul class="footer-nav-menu flex space-x-6 text-sm">
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/terms-conditions')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Terms & Conditions', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/shipping-returns')); ?>" class="text-gray-400 hover:text-white transition-colors"><?php esc_html_e('Shipping & Returns', 'aqualuxe'); ?></a></li>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</footer>
