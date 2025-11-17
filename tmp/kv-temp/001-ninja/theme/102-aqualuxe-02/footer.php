<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-900 text-white">
        
        <!-- Footer Widgets -->
        <?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
            <div class="footer-widgets py-12 border-b border-gray-800">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php dynamic_sidebar( 'footer-widgets' ); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Footer Content -->
        <div class="footer-main py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Company Info -->
                    <div class="footer-section">
                        <div class="footer-logo mb-6">
                            <?php if ( has_custom_logo() ) : ?>
                                <?php the_custom_logo(); ?>
                            <?php else : ?>
                                <h3 class="text-xl font-bold text-white mb-2">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-white no-underline hover:text-primary-400 transition-colors">
                                        <?php bloginfo( 'name' ); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-gray-400 mb-4 leading-relaxed">
                            <?php 
                            $footer_description = get_theme_mod( 'aqualuxe_footer_description', __( 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and professional aquascaping services.', 'aqualuxe' ) );
                            echo esc_html( $footer_description );
                            ?>
                        </p>
                        
                        <!-- Social Media -->
                        <div class="social-links flex space-x-4">
                            <?php
                            $social_links = array(
                                'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
                                'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
                                'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
                                'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
                                'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
                            );
                            
                            foreach ( $social_links as $platform => $url ) :
                                if ( ! empty( $url ) ) :
                            ?>
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="social-link text-gray-400 hover:text-primary-400 transition-colors" aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
                                    <?php echo aqualuxe_get_social_icon( $platform ); // Custom function to get SVG icons ?>
                                </a>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h4 class="text-lg font-semibold text-white mb-6"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h4>
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu space-y-3',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => 'aqualuxe_footer_menu_fallback',
                            )
                        );
                        ?>
                    </div>

                    <!-- Services -->
                    <div class="footer-section">
                        <h4 class="text-lg font-semibold text-white mb-6"><?php esc_html_e( 'Our Services', 'aqualuxe' ); ?></h4>
                        <ul class="footer-services space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Aquarium Design', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Maintenance Services', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Fish Breeding', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Consultation', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Wholesale Program', 'aqualuxe' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="footer-section">
                        <h4 class="text-lg font-semibold text-white mb-6"><?php esc_html_e( 'Contact Info', 'aqualuxe' ); ?></h4>
                        <div class="contact-info space-y-4">
                            <?php 
                            $contact_address = get_theme_mod( 'aqualuxe_contact_address', '' );
                            if ( $contact_address ) :
                            ?>
                                <div class="contact-item flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-primary-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-gray-400 text-sm leading-relaxed">
                                        <?php echo wp_kses_post( nl2br( $contact_address ) ); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            $contact_phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
                            if ( $contact_phone ) :
                            ?>
                                <div class="contact-item flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $contact_phone ) ); ?>" class="text-gray-400 hover:text-primary-400 transition-colors no-underline">
                                        <?php echo esc_html( $contact_phone ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            $contact_email = get_theme_mod( 'aqualuxe_contact_email', '' );
                            if ( $contact_email ) :
                            ?>
                                <div class="contact-item flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="text-gray-400 hover:text-primary-400 transition-colors no-underline">
                                        <?php echo esc_html( $contact_email ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Newsletter Signup -->
                        <div class="newsletter-signup mt-6">
                            <h5 class="text-sm font-semibold text-white mb-3"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h5>
                            <form class="newsletter-form flex">
                                <input type="email" placeholder="<?php esc_attr_e( 'Your email', 'aqualuxe' ); ?>" class="flex-1 px-3 py-2 text-sm bg-gray-800 border border-gray-700 rounded-l-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <button type="submit" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-r-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                    <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom py-6 border-t border-gray-800">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    
                    <!-- Copyright -->
                    <div class="footer-copyright text-center md:text-left">
                        <p class="text-sm text-gray-400">
                            <?php
                            printf(
                                /* translators: 1: Copyright year, 2: Site name */
                                esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ),
                                esc_html( date_i18n( 'Y' ) ),
                                esc_html( get_bloginfo( 'name' ) )
                            );
                            ?>
                        </p>
                    </div>

                    <!-- Footer Links -->
                    <div class="footer-links">
                        <ul class="flex flex-wrap justify-center md:justify-end space-x-6 text-sm">
                            <li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Privacy Policy', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Terms of Service', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Shipping Policy', 'aqualuxe' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-primary-400 transition-colors no-underline"><?php esc_html_e( 'Returns', 'aqualuxe' ); ?></a></li>
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