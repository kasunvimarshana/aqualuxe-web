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

    <footer id="colophon" class="site-footer bg-dark text-white">
        <div class="footer-widgets py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h3>
                                <p><?php esc_html_e( 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and custom aquarium solutions for enthusiasts and professionals.', 'aqualuxe' ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'footer',
                                        'menu_id'        => 'footer-menu',
                                        'container'      => false,
                                        'menu_class'     => 'footer-menu',
                                        'fallback_cb'    => false,
                                        'depth'          => 1,
                                    )
                                );
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
                                <ul class="contact-info">
                                    <li class="address"><?php esc_html_e( '123 Aquarium Way, Ocean City, CA 90210', 'aqualuxe' ); ?></li>
                                    <li class="phone"><?php esc_html_e( '+1 (555) 123-4567', 'aqualuxe' ); ?></li>
                                    <li class="email"><a href="mailto:info@aqualuxe.com"><?php esc_html_e( 'info@aqualuxe.com', 'aqualuxe' ); ?></a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="footer-widget">
                        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                            <?php dynamic_sidebar( 'footer-4' ); ?>
                        <?php else : ?>
                            <div class="widget">
                                <h3 class="widget-title"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h3>
                                <p><?php esc_html_e( 'Subscribe to our newsletter for the latest updates on rare species, aquascaping tips, and exclusive offers.', 'aqualuxe' ); ?></p>
                                <form class="newsletter-form mt-4">
                                    <div class="flex">
                                        <input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" class="w-full px-4 py-2 rounded-l" required>
                                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom py-4 border-t border-gray-700">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="copyright mb-4 md:mb-0">
                        <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'aqualuxe' ); ?></p>
                    </div>
                    
                    <div class="footer-social">
                        <ul class="social-links flex">
                            <li><a href="#" class="mx-2" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#" class="mx-2" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#" class="mx-2" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#" class="mx-2" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>"><i class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>