<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>

    </div><!-- #content -->

    <?php do_action( 'aqualuxe_before_footer' ); ?>

    <footer id="colophon" class="site-footer bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/4 px-4 mb-8 md:mb-0">
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        <?php else : ?>
                            <h3 class="text-xl font-bold mb-4"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h3>
                            <p class="mb-4"><?php esc_html_e( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ); ?></p>
                            <div class="social-icons flex space-x-4">
                                <a href="#" class="text-white hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>
                                </a>
                                <a href="#" class="text-white hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>
                                </a>
                                <a href="#" class="text-white hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 1.802c-2.67 0-2.986.01-4.04.059-.976.045-1.505.207-1.858.344-.466.182-.8.398-1.15.748-.35.35-.566.684-.748 1.15-.137.353-.3.882-.344 1.857-.048 1.055-.058 1.37-.058 4.041 0 2.67.01 2.986.058 4.04.045.977.207 1.505.344 1.858.182.466.399.8.748 1.15.35.35.684.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058 2.67 0 2.987-.01 4.04-.058.977-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.684.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041 0-2.67-.01-2.986-.058-4.04-.045-.977-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.055-.048-1.37-.058-4.041-.058zm0 3.063a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666zm6.538-3.11a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/></svg>
                                </a>
                                <a href="#" class="text-white hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full md:w-1/4 px-4 mb-8 md:mb-0">
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        <?php else : ?>
                            <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
                            <ul class="footer-links">
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full md:w-1/4 px-4 mb-8 md:mb-0">
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        <?php else : ?>
                            <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Products', 'aqualuxe' ); ?></h3>
                            <ul class="footer-links">
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Freshwater Fish', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Marine Fish', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Aquatic Plants', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Aquariums', 'aqualuxe' ); ?></a></li>
                                <li class="mb-2"><a href="#" class="text-gray-300 hover:text-white"><?php esc_html_e( 'Equipment', 'aqualuxe' ); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full md:w-1/4 px-4">
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-4' ); ?>
                        <?php else : ?>
                            <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h3>
                            <p class="mb-4"><?php esc_html_e( 'Subscribe to our newsletter to receive updates and special offers.', 'aqualuxe' ); ?></p>
                            <form class="newsletter-form">
                                <div class="flex">
                                    <input type="email" placeholder="<?php esc_attr_e( 'Your Email', 'aqualuxe' ); ?>" class="w-full px-4 py-2 rounded-l-lg focus:outline-none text-gray-900">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg">
                                        <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-wrap justify-between">
                    <div class="w-full md:w-auto mb-4 md:mb-0">
                        <p class="text-gray-400">
                            &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. 
                            <?php esc_html_e( 'All rights reserved.', 'aqualuxe' ); ?>
                        </p>
                    </div>
                    <div class="w-full md:w-auto">
                        <ul class="flex flex-wrap space-x-4">
                            <li><a href="#" class="text-gray-400 hover:text-white"><?php esc_html_e( 'Privacy Policy', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white"><?php esc_html_e( 'Terms of Service', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white"><?php esc_html_e( 'Shipping & Returns', 'aqualuxe' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->

    <?php do_action( 'aqualuxe_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>