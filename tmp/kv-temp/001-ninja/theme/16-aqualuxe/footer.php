<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-primary-dark text-white pt-16 pb-8">
        <div class="container-fluid">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Footer Widget Area 1 -->
                <div class="footer-widget">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php else : ?>
                        <div class="site-info">
                            <?php if (has_custom_logo()) : ?>
                                <div class="footer-logo mb-4">
                                    <?php
                                    $custom_logo_id = get_theme_mod('custom_logo');
                                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                                    if ($logo) {
                                        echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="max-h-16">';
                                    }
                                    ?>
                                </div>
                            <?php else : ?>
                                <h3 class="text-xl font-bold mb-4"><?php bloginfo('name'); ?></h3>
                            <?php endif; ?>
                            <p class="mb-4 text-gray-300"><?php echo get_theme_mod('aqualuxe_footer_description', esc_html__('Premium ornamental fish farming business offering high-quality aquatic species and aquarium supplies for enthusiasts and collectors.', 'aqualuxe')); ?></p>
                            
                            <div class="social-links flex space-x-3 mt-6">
                                <?php
                                $social_networks = [
                                    'facebook' => [
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
                                    ],
                                    'twitter' => [
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
                                    ],
                                    'instagram' => [
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
                                    ],
                                    'youtube' => [
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>',
                                    ],
                                    'pinterest' => [
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>',
                                    ],
                                ];
                                
                                foreach ($social_networks as $network => $data) {
                                    $url = get_theme_mod("aqualuxe_{$network}_url");
                                    if ($url) {
                                        echo '<a href="' . esc_url($url) . '" class="hover:text-accent transition-colors duration-300" target="_blank" rel="noopener noreferrer">' . $data['icon'] . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget Area 2 -->
                <div class="footer-widget">
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <?php dynamic_sidebar('footer-2'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                        <ul class="space-y-2">
                            <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-accent transition-colors duration-300"><?php esc_html_e('Home', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="hover:text-accent transition-colors duration-300"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="hover:text-accent transition-colors duration-300"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/fish-species/')); ?>" class="hover:text-accent transition-colors duration-300"><?php esc_html_e('Fish Species', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/care-guides/')); ?>" class="hover:text-accent transition-colors duration-300"><?php esc_html_e('Care Guides', 'aqualuxe'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="hover:text-accent transition-colors duration-300"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget Area 3 -->
                <div class="footer-widget">
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <?php dynamic_sidebar('footer-3'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Contact Info', 'aqualuxe'); ?></h3>
                        <ul class="space-y-3">
                            <?php if (get_theme_mod('aqualuxe_address')) : ?>
                                <li class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span><?php echo esc_html(get_theme_mod('aqualuxe_address')); ?></span>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_phone')) : ?>
                                <li class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <a href="tel:<?php echo esc_attr(get_theme_mod('aqualuxe_phone')); ?>" class="hover:text-accent transition-colors duration-300">
                                        <?php echo esc_html(get_theme_mod('aqualuxe_phone')); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_email')) : ?>
                                <li class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_email')); ?>" class="hover:text-accent transition-colors duration-300">
                                        <?php echo esc_html(get_theme_mod('aqualuxe_email')); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_hours')) : ?>
                                <li class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span><?php echo esc_html(get_theme_mod('aqualuxe_hours')); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget Area 4 -->
                <div class="footer-widget">
                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <?php dynamic_sidebar('footer-4'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
                        <p class="mb-4 text-gray-300"><?php esc_html_e('Subscribe to our newsletter to receive updates on new fish species, care guides, and special offers.', 'aqualuxe'); ?></p>
                        
                        <form id="newsletter-form" class="mt-4">
                            <div class="flex">
                                <input type="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" required>
                                <button type="submit" class="bg-accent hover:bg-accent-dark text-primary-dark px-4 py-2 rounded-r-md transition-colors duration-300">
                                    <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                </button>
                            </div>
                            <div class="response-message mt-2"></div>
                        </form>
                        
                        <?php if (get_theme_mod('aqualuxe_show_payment_icons', true)) : ?>
                            <div class="payment-methods mt-6">
                                <h4 class="text-sm font-semibold mb-2"><?php esc_html_e('Payment Methods', 'aqualuxe'); ?></h4>
                                <div class="flex space-x-2">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/payment/visa.svg'); ?>" alt="Visa" class="h-8">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/payment/mastercard.svg'); ?>" alt="Mastercard" class="h-8">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/payment/paypal.svg'); ?>" alt="PayPal" class="h-8">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/payment/apple-pay.svg'); ?>" alt="Apple Pay" class="h-8">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="border-t border-primary pt-6 mt-10">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="copyright text-sm text-gray-300 mb-4 md:mb-0">
                        <?php
                        $copyright_text = get_theme_mod('aqualuxe_copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
                        echo wp_kses_post($copyright_text);
                        ?>
                    </div>
                    
                    <nav class="footer-menu">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'menu_class'     => 'flex flex-wrap justify-center space-x-4 text-sm',
                                'fallback_cb'    => false,
                                'depth'          => 1,
                            )
                        );
                        ?>
                    </nav>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php if (get_theme_mod('aqualuxe_show_back_to_top', true)) : ?>
    <button id="back-to-top" class="fixed bottom-8 right-8 bg-primary hover:bg-primary-dark text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>