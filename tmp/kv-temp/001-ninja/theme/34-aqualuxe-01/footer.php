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

?>

    <footer id="colophon" class="site-footer bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/4 px-4 mb-8 md:mb-0">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php echo esc_html(get_bloginfo('name')); ?></h3>
                        <p class="mb-4"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
                        <div class="social-links flex space-x-4">
                            <a href="#" class="text-white hover:text-primary transition-colors">
                                <span class="screen-reader-text"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-primary transition-colors">
                                <span class="screen-reader-text"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-primary transition-colors">
                                <span class="screen-reader-text"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="w-full md:w-1/4 px-4 mb-8 md:mb-0">
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <?php dynamic_sidebar('footer-2'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                        <ul class="footer-links">
                            <li class="mb-2"><a href="#" class="hover:text-primary transition-colors"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                            <li class="mb-2"><a href="#" class="hover:text-primary transition-colors"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                            <li class="mb-2"><a href="#" class="hover:text-primary transition-colors"><?php esc_html_e('Shop', 'aqualuxe'); ?></a></li>
                            <li class="mb-2"><a href="#" class="hover:text-primary transition-colors"><?php esc_html_e('Blog', 'aqualuxe'); ?></a></li>
                            <li class="mb-2"><a href="#" class="hover:text-primary transition-colors"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="w-full md:w-1/4 px-4 mb-8 md:mb-0">
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <?php dynamic_sidebar('footer-3'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></h3>
                        <address class="not-italic">
                            <p class="mb-2"><?php esc_html_e('123 Aquarium Street', 'aqualuxe'); ?></p>
                            <p class="mb-2"><?php esc_html_e('Coral City, Ocean State 12345', 'aqualuxe'); ?></p>
                            <p class="mb-2"><?php esc_html_e('Phone: (123) 456-7890', 'aqualuxe'); ?></p>
                            <p class="mb-2"><?php esc_html_e('Email: info@aqualuxe.example.com', 'aqualuxe'); ?></p>
                        </address>
                    <?php endif; ?>
                </div>
                <div class="w-full md:w-1/4 px-4">
                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <?php dynamic_sidebar('footer-4'); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h3>
                        <p class="mb-4"><?php esc_html_e('Subscribe to our newsletter for the latest updates and exclusive offers.', 'aqualuxe'); ?></p>
                        <form class="newsletter-form">
                            <div class="flex">
                                <input type="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="w-full px-4 py-2 rounded-l focus:outline-none" required>
                                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-r transition-colors">
                                    <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8">
                <div class="flex flex-wrap justify-between">
                    <div class="w-full md:w-auto mb-4 md:mb-0">
                        <p>&copy; <?php echo esc_html(date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="w-full md:w-auto">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'menu_class'     => 'flex flex-wrap',
                                'fallback_cb'    => false,
                                'depth'          => 1,
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>