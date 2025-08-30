<?php
/**
 * Template part for displaying the mobile menu
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="mobile-menu-container fixed inset-0 bg-white dark:bg-dark z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="mobile-menu-header flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="site-logo">
            <?php aqualuxe_site_logo(); ?>
        </div>
        <button class="mobile-menu-close text-2xl text-gray-600 dark:text-gray-300 focus:outline-none" aria-label="<?php esc_attr_e( 'Close Menu', 'aqualuxe' ); ?>">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="mobile-menu-content overflow-y-auto h-full pb-20">
        <div class="mobile-search p-4">
            <?php get_search_form(); ?>
        </div>
        
        <nav class="mobile-navigation">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                    'menu_id'        => 'mobile-menu',
                    'fallback_cb'    => false,
                    'depth'          => 3,
                )
            );
            ?>
        </nav>
        
        <div class="mobile-menu-extras p-4 border-t border-gray-200 dark:border-gray-700 mt-4">
            <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                <div class="mobile-account mb-4">
                    <h4 class="text-lg font-medium mb-2"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></h4>
                    <ul class="mobile-account-links">
                        <?php if ( is_user_logged_in() ) : ?>
                            <li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><?php esc_html_e( 'Dashboard', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"><?php esc_html_e( 'Orders', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>"><?php esc_html_e( 'Account Details', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( wc_logout_url() ); ?>"><?php esc_html_e( 'Logout', 'aqualuxe' ); ?></a></li>
                        <?php else : ?>
                            <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Login / Register', 'aqualuxe' ); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="mobile-language-currency flex flex-wrap gap-4 mb-4">
                <?php aqualuxe_language_switcher(); ?>
                <?php aqualuxe_currency_switcher(); ?>
            </div>
            
            <div class="mobile-dark-mode mb-4">
                <?php aqualuxe_dark_mode_toggle(); ?>
            </div>
            
            <div class="mobile-social">
                <?php aqualuxe_social_links(); ?>
            </div>
        </div>
    </div>
</div>