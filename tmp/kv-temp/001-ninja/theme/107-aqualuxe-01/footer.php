<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-900 text-white">
        
        <!-- Main Footer Content -->
        <div class="footer-main py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Footer Column 1 -->
                    <div class="footer-column">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <?php dynamic_sidebar('footer-1'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4"><?php bloginfo('name'); ?></h3>
                                <p class="text-gray-300 mb-4">
                                    <?php _e('Bringing elegance to aquatic life – globally. Discover premium aquatic products, expert services, and sustainable solutions.', 'aqualuxe'); ?>
                                </p>
                                <div class="social-links flex space-x-4">
                                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.758-1.378l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.989C24.007 5.367 18.641.001 12.017.001z"/></svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Column 2 -->
                    <div class="footer-column">
                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <?php dynamic_sidebar('footer-2'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4"><?php _e('Quick Links', 'aqualuxe'); ?></h3>
                                <ul class="space-y-2">
                                    <li><a href="<?php echo home_url('/about'); ?>" class="text-gray-300 hover:text-white transition-colors"><?php _e('About Us', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo home_url('/services'); ?>" class="text-gray-300 hover:text-white transition-colors"><?php _e('Our Services', 'aqualuxe'); ?></a></li>
                                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                        <li><a href="<?php echo wc_get_page_permalink('shop'); ?>" class="text-gray-300 hover:text-white transition-colors"><?php _e('Shop', 'aqualuxe'); ?></a></li>
                                    <?php endif; ?>
                                    <li><a href="<?php echo home_url('/blog'); ?>" class="text-gray-300 hover:text-white transition-colors"><?php _e('Blog', 'aqualuxe'); ?></a></li>
                                    <li><a href="<?php echo home_url('/contact'); ?>" class="text-gray-300 hover:text-white transition-colors"><?php _e('Contact', 'aqualuxe'); ?></a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Column 3 -->
                    <div class="footer-column">
                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <?php dynamic_sidebar('footer-3'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4"><?php _e('Contact Info', 'aqualuxe'); ?></h3>
                                <div class="contact-info space-y-3 text-gray-300">
                                    <div class="contact-item flex items-start space-x-3">
                                        <svg class="w-5 h-5 mt-1 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p><?php _e('123 Aquatic Avenue', 'aqualuxe'); ?></p>
                                            <p><?php _e('Ocean City, OC 12345', 'aqualuxe'); ?></p>
                                        </div>
                                    </div>
                                    <div class="contact-item flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                        <p>+1 (555) 123-4567</p>
                                    </div>
                                    <div class="contact-item flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                        <p>info@aqualuxe.com</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Column 4 -->
                    <div class="footer-column">
                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <?php dynamic_sidebar('footer-4'); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title text-xl font-semibold mb-4"><?php _e('Newsletter', 'aqualuxe'); ?></h3>
                                <p class="text-gray-300 mb-4">
                                    <?php _e('Subscribe to receive updates on new products, exclusive offers, and aquatic care tips.', 'aqualuxe'); ?>
                                </p>
                                <form class="newsletter-form">
                                    <div class="flex flex-col space-y-3">
                                        <input type="email" placeholder="<?php _e('Your email address', 'aqualuxe'); ?>" 
                                               class="px-4 py-2 bg-gray-800 text-white border border-gray-700 rounded focus:outline-none focus:border-primary-500">
                                        <button type="submit" class="btn btn-primary">
                                            <?php _e('Subscribe', 'aqualuxe'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom border-t border-gray-800 py-6">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    
                    <div class="copyright text-gray-400 text-sm">
                        <?php
                        printf(
                            __('© %s %s. All rights reserved.', 'aqualuxe'),
                            date('Y'),
                            get_bloginfo('name')
                        );
                        ?>
                    </div>

                    <div class="footer-nav">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu flex space-x-6 text-sm',
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ]);
                        ?>
                        
                        <?php if (!wp_nav_menu(['theme_location' => 'footer', 'echo' => false])) : ?>
                            <ul class="footer-menu flex space-x-6 text-sm text-gray-400">
                                <li><a href="<?php echo home_url('/privacy-policy'); ?>" class="hover:text-white transition-colors"><?php _e('Privacy Policy', 'aqualuxe'); ?></a></li>
                                <li><a href="<?php echo home_url('/terms-conditions'); ?>" class="hover:text-white transition-colors"><?php _e('Terms & Conditions', 'aqualuxe'); ?></a></li>
                                <li><a href="<?php echo home_url('/shipping-returns'); ?>" class="hover:text-white transition-colors"><?php _e('Shipping & Returns', 'aqualuxe'); ?></a></li>
                                <li><a href="<?php echo home_url('/cookie-policy'); ?>" class="hover:text-white transition-colors"><?php _e('Cookie Policy', 'aqualuxe'); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 pointer-events-none z-40">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
</button>

</body>
</html>