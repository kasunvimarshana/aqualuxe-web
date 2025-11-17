<?php
/**
 * Template part for displaying the four-column footer layout
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<div class="footer-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <?php dynamic_sidebar('footer-1'); ?>
        <?php else : ?>
            <div class="widget">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('About AquaLuxe', 'aqualuxe'); ?></h3>
                <div class="widget-content">
                    <p class="mb-4"><?php esc_html_e('Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and custom aquariums for enthusiasts and professionals.', 'aqualuxe'); ?></p>
                    <div class="footer-social flex space-x-3 mt-4">
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-2')) : ?>
            <?php dynamic_sidebar('footer-2'); ?>
        <?php else : ?>
            <div class="widget">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                <div class="widget-content">
                    <ul class="space-y-2">
                        <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('FAQ', 'aqualuxe'); ?></a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-3')) : ?>
            <?php dynamic_sidebar('footer-3'); ?>
        <?php else : ?>
            <div class="widget">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Shop', 'aqualuxe'); ?></h3>
                <div class="widget-content">
                    <ul class="space-y-2">
                        <li><a href="<?php echo esc_url(home_url('/shop/fish/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Ornamental Fish', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/shop/plants/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Aquatic Plants', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/shop/aquariums/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Aquariums', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/shop/equipment/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Equipment', 'aqualuxe'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/shop/accessories/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><?php esc_html_e('Accessories', 'aqualuxe'); ?></a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer-widget">
        <?php if (is_active_sidebar('footer-4')) : ?>
            <?php dynamic_sidebar('footer-4'); ?>
        <?php else : ?>
            <div class="widget">
                <h3 class="widget-title text-lg font-semibold mb-4"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
                <div class="widget-content">
                    <p class="mb-4"><?php esc_html_e('Subscribe to our newsletter for the latest updates, exclusive offers, and aquatic insights.', 'aqualuxe'); ?></p>
                    <form class="newsletter-form">
                        <div class="flex">
                            <input type="email" class="flex-grow border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md focus:ring-primary-500 focus:border-primary-500" placeholder="<?php esc_attr_e('Your email', 'aqualuxe'); ?>" required>
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-r-md">
                                <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                            </button>
                        </div>
                    </form>
                    <p class="text-sm mt-2 text-gray-600 dark:text-gray-400"><?php esc_html_e('By subscribing, you agree to our Privacy Policy.', 'aqualuxe'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>